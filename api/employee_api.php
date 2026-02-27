<?php
/**
 * employee_api.php
 * API สำหรับจัดการผู้ยืม (พนักงาน)
 */
require_once '../config.php';

$action = $_GET['action'] ?? ($_POST['action'] ?? '');

switch ($action) {
    case 'get_all':
        // ดึงพนักงานทั้งหมด
        try {
            $stmt = $pdo->query("SELECT * FROM employees ORDER BY id DESC");
            $employees = $stmt->fetchAll();
            responseJson('success', 'Data retrieved', $employees);
        }
        catch (Exception $e) {
            responseJson('error', $e->getMessage());
        }
        break;

    case 'add':
        // เพิ่มพนักงานใหม่
        try {
            $emp_code = trim($_POST['emp_code'] ?? '');
            $emp_name = trim($_POST['emp_name'] ?? '');
            $department = trim($_POST['department'] ?? '');
            $position = trim($_POST['position'] ?? '');

            if (empty($emp_code) || empty($emp_name)) {
                responseJson('error', 'โปรดกรอกรหัสพนักงานและชื่อ-นามสกุล');
            }

            // เช็ครหัสพนักงานซ้ำ
            $stmtCheck = $pdo->prepare("SELECT id FROM employees WHERE emp_code = ?");
            $stmtCheck->execute([$emp_code]);
            if ($stmtCheck->rowCount() > 0) {
                responseJson('error', 'รหัสพนักงานนี้มีอยู่ในระบบแล้ว');
            }

            $sql = "INSERT INTO employees (emp_code, emp_name, department, position) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$emp_code, $emp_name, $department, $position]);

            responseJson('success', 'เพิ่มรายชื่อผู้ยืมสำเร็จ');
        }
        catch (Exception $e) {
            responseJson('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
        break;

    case 'delete':
        // ลบพนักงาน
        try {
            $id = $_POST['id'] ?? 0;
            if (empty($id))
                responseJson('error', 'ไม่พบรหัสอ้างอิง');

            $stmt = $pdo->prepare("DELETE FROM employees WHERE id = ?");
            $stmt->execute([$id]);
            responseJson('success', 'ลบข้อมูลสำเร็จ');
        }
        catch (Exception $e) {
            responseJson('error', 'เกิดข้อผิดพลาด (อาจมีประวัติการยืมผูกอยู่): ' . $e->getMessage());
        }
        break;

    case 'get_borrowed_items':
        // ดูรายการอุปกรณ์ที่พนักงานคนนี้กำลังยืมอยู่
        try {
            $emp_id = $_GET['emp_id'] ?? 0;
            $stmt = $pdo->prepare("
                SELECT b.id as borrow_id, b.borrow_date, b.location, e.barcode, e.type, e.brand, e.model, e.serial_number 
                FROM borrowings b
                JOIN equipments e ON b.equipment_id = e.id
                WHERE b.employee_id = ? AND b.status = 'active'
            ");
            $stmt->execute([$emp_id]);
            $items = $stmt->fetchAll();
            responseJson('success', 'Items retrieved', $items);
        }
        catch (Exception $e) {
            responseJson('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
        break;

    default:
        responseJson('error', 'Invalid action');
}
?>
