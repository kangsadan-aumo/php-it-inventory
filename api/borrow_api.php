<?php
/**
 * borrow_api.php
 * API สำหรับจัดการการยืม - คืน อุปกรณ์ และดูประวัติ
 */
require_once '../config.php';

$action = $_GET['action'] ?? ($_POST['action'] ?? '');

switch ($action) {
    case 'lookup':
        // ค้นหาอุปกรณ์ด้วยบาร์โค้ด
        try {
            $barcode = trim($_GET['barcode'] ?? '');
            if (empty($barcode))
                responseJson('error', 'ไม่ได้ระบุบาร์โค้ด');

            $stmt = $pdo->prepare("SELECT * FROM equipments WHERE barcode = ?");
            $stmt->execute([$barcode]);
            $equipment = $stmt->fetch();

            if ($equipment) {
                responseJson('success', 'พบอุปกรณ์', $equipment);
            }
            else {
                responseJson('error', 'ไม่พบอุปกรณ์นี้ในระบบ');
            }
        }
        catch (Exception $e) {
            responseJson('error', $e->getMessage());
        }
        break;

    case 'borrow':
        // บันทึกการยืม
        try {
            $equipment_id = $_POST['equipment_id'] ?? 0;
            $employee_id = $_POST['employee_id'] ?? 0; // รับเป็น ID ของตาราง employees
            $location = trim($_POST['location'] ?? '');

            if (empty($equipment_id) || empty($employee_id)) {
                responseJson('error', 'ข้อมูลไม่ครบถ้วน (เลือกรหัสพนักงานและอุปกรณ์)');
            }
            if (empty($location)) {
                responseJson('error', 'กรุณาระบุสถานที่ตั้งอุปกรณ์');
            }

            // เช็คสถานะปัจจุบันของอุปกรณ์
            $stmtCheck = $pdo->prepare("SELECT status FROM equipments WHERE id = ?");
            $stmtCheck->execute([$equipment_id]);
            $eq = $stmtCheck->fetch();

            if (!$eq) {
                responseJson('error', 'ไม่พบอุปกรณ์ในระบบ');
                exit; // Need this exit though responseJson normally might die if I implemented it that way, but let's be safe. Wait, let's keep it as was and return. Actually, responseJson outputs and exits in my previous implementation probably? Let's assume it exits. But wait, previously it was just `responseJson('error', ...)`.
            }
            if ($eq['status'] != 'available') {
                responseJson('error', 'อุปกรณ์นี้ไม่พร้อมให้ยืม (สถานะ: ' . $eq['status'] . ')');
            }

            $borrow_date = trim($_POST['borrow_date'] ?? '');

            $pdo->beginTransaction();

            // Insert การยืม
            if (!empty($borrow_date)) {
                $stmtBorrow = $pdo->prepare("INSERT INTO borrowings (equipment_id, employee_id, location, borrow_date) VALUES (?, ?, ?, ?)");
                $stmtBorrow->execute([$equipment_id, $employee_id, $location, $borrow_date]);
            }
            else {
                $stmtBorrow = $pdo->prepare("INSERT INTO borrowings (equipment_id, employee_id, location, borrow_date) VALUES (?, ?, ?, NOW())");
                $stmtBorrow->execute([$equipment_id, $employee_id, $location]);
            }

            // Update สถานะอุปกรณ์ เป็น borrowed
            $stmtUpdate = $pdo->prepare("UPDATE equipments SET status = 'borrowed' WHERE id = ?");
            $stmtUpdate->execute([$equipment_id]);

            $pdo->commit();
            responseJson('success', 'บันทึกการยืมสำเร็จ');

        }
        catch (Exception $e) {
            $pdo->rollBack();
            responseJson('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
        break;

    case 'return':
        // บันทึกการคืน (โดยเอา barcode มาค้นหา active borrowing)
        try {
            $barcode = trim($_POST['barcode'] ?? '');
            if (empty($barcode))
                responseJson('error', 'ไม่ได้ระบุบาร์โค้ด');

            // หา id ของ equipment ก่อน
            $stmtEq = $pdo->prepare("SELECT id FROM equipments WHERE barcode = ? AND status = 'borrowed'");
            $stmtEq->execute([$barcode]);
            $eq = $stmtEq->fetch();

            if (!$eq)
                responseJson('error', 'ไม่พบอุปกรณ์หรือเครื่องไม่ได้ถูกยืมอยู่');
            $equipment_id = $eq['id'];

            // เตรียม transaction
            $pdo->beginTransaction();

            $return_date = trim($_POST['return_date'] ?? '');

            // Update สถานะการยืมเป็น returned พร้อมระบุเวลาคืน
            if (!empty($return_date)) {
                $stmtBorrow = $pdo->prepare("UPDATE borrowings SET status = 'returned', return_date = ? WHERE equipment_id = ? AND status = 'active'");
                $stmtBorrow->execute([$return_date, $equipment_id]);
            }
            else {
                $stmtBorrow = $pdo->prepare("UPDATE borrowings SET status = 'returned', return_date = NOW() WHERE equipment_id = ? AND status = 'active'");
                $stmtBorrow->execute([$equipment_id]);
            }

            // Update อุปกรณ์ให้พร้อมใช้งาน
            $stmtUpdate = $pdo->prepare("UPDATE equipments SET status = 'available' WHERE id = ?");
            $stmtUpdate->execute([$equipment_id]);

            $pdo->commit();
            responseJson('success', 'ทำรายการคืนสำเร็จ');

        }
        catch (Exception $e) {
            $pdo->rollBack();
            responseJson('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
        break;

    case 'get_history':
        // ดูประวัติการยืมคืนทั้งหมด
        try {
            $sql = "SELECT b.id, emp.emp_code as employee_id, emp.emp_name as employee_name, b.location, b.borrow_date, b.return_date, b.status as borrow_status, e.barcode, e.type, e.brand, e.model, e.serial_number 
                    FROM borrowings b 
                    JOIN equipments e ON b.equipment_id = e.id 
                    JOIN employees emp ON b.employee_id = emp.id
                    ORDER BY b.borrow_date DESC";
            $stmt = $pdo->query($sql);
            $history = $stmt->fetchAll();
            responseJson('success', 'Data retrieved', $history);
        }
        catch (Exception $e) {
            responseJson('error', $e->getMessage());
        }
        break;

    case 'get_equipment_history':
        // ดูประวัติการยืมของอุปกรณ์ชิ้นนั้นๆ
        try {
            $eq_id = $_GET['id'] ?? 0;
            if (empty($eq_id))
                responseJson('error', 'ไม่ระบุรหัสอุปกรณ์');

            $sql = "SELECT b.id, emp.emp_code, emp.emp_name, b.location, b.borrow_date, b.return_date, b.status 
                    FROM borrowings b 
                    JOIN employees emp ON b.employee_id = emp.id
                    WHERE b.equipment_id = ?
                    ORDER BY b.borrow_date DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$eq_id]);
            $history = $stmt->fetchAll();
            responseJson('success', 'Data retrieved', $history);
        }
        catch (Exception $e) {
            responseJson('error', $e->getMessage());
        }
        break;

    default:
        responseJson('error', 'Invalid action');
}
?>
