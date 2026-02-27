<?php
/**
 * equipment_api.php
 * API สำหรับจัดการอุปกรณ์ (CRUD)
 */
require_once '../config.php';

$action = $_GET['action'] ?? ($_POST['action'] ?? '');

switch ($action) {
    case 'get_all':
        // ดึงข้อมูลอุปกรณ์ทั้งหมด
        try {
            $stmt = $pdo->query("SELECT * FROM equipments ORDER BY id DESC");
            $equipments = $stmt->fetchAll();
            responseJson('success', 'Data retrieved', $equipments);
        }
        catch (Exception $e) {
            responseJson('error', $e->getMessage());
        }
        break;

    case 'add':
        // เพิ่มอุปกรณ์ใหม่
        try {
            $barcode = trim($_POST['barcode'] ?? '');
            $type = trim($_POST['type'] ?? '');

            if (empty($barcode) || empty($type)) {
                responseJson('error', 'โปรดกรอกบาร์โค้ดและประเภทอุปกรณ์');
            }

            // เช็คบาร์โค้ดซ้ำ
            $stmtCheck = $pdo->prepare("SELECT id FROM equipments WHERE barcode = ?");
            $stmtCheck->execute([$barcode]);
            if ($stmtCheck->rowCount() > 0) {
                responseJson('error', 'บาร์โค้ดนี้มีอยู่ในระบบแล้ว');
            }

            $sql = "INSERT INTO equipments (barcode, serial_number, type, brand, model, cpu_gen, ram_gb, storage_type, storage_gb, os, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $barcode,
                $_POST['serial_number'] ?? null,
                $type,
                $_POST['brand'] ?? null,
                $_POST['model'] ?? null,
                $_POST['cpu_gen'] ?? null,
                !empty($_POST['ram_gb']) ? $_POST['ram_gb'] : null,
                $_POST['storage_type'] ?? null,
                !empty($_POST['storage_gb']) ? $_POST['storage_gb'] : null,
                $_POST['os'] ?? null,
                $_POST['status'] ?? 'available'
            ]);

            responseJson('success', 'เพิ่มอุปกรณ์สำเร็จ');
        }
        catch (Exception $e) {
            responseJson('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
        break;

    case 'delete':
        // ลบอุปกรณ์
        try {
            $id = $_POST['id'] ?? 0;
            if (empty($id))
                responseJson('error', 'ไม่พบรหัสอุปกรณ์');

            $stmt = $pdo->prepare("DELETE FROM equipments WHERE id = ?");
            $stmt->execute([$id]);
            responseJson('success', 'ลบอุปกรณ์สำเร็จ');
        }
        catch (Exception $e) {
            responseJson('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
        break;

    case 'update_status':
        // อัปเดตสถานะอุปกรณ์ (เช่น แจ้งชำรุด หรือส่งซ่อม)
        try {
            $id = $_POST['id'] ?? 0;
            $new_status = $_POST['status'] ?? '';

            if (empty($id) || empty($new_status)) {
                responseJson('error', 'ระบุข้อมูลไม่ครบถ้วน');
            }
            if (!in_array($new_status, ['available', 'borrowed', 'maintenance', 'broken'])) {
                responseJson('error', 'สถานะไม่ถูกต้อง');
            }

            // ถ้าเครื่องนั้นกำลังโดนยืมอยู่ แต่จะถูกปรับสถานะเป็นอื่น (ที่ไม่ใช่ borrowed) อาจจะต้องมีการคืนก่อน
            $stmtCheck = $pdo->prepare("SELECT status FROM equipments WHERE id = ?");
            $stmtCheck->execute([$id]);
            $current = $stmtCheck->fetch();

            if ($current['status'] == 'borrowed' && $new_status != 'borrowed') {
                responseJson('error', 'อุปกรณ์ชิ้นนี้กำลังถูกยืมอยู่ ต้องทำรายการคืนก่อนเปลี่ยนสถานะ');
            }

            $stmt = $pdo->prepare("UPDATE equipments SET status = ? WHERE id = ?");
            $stmt->execute([$new_status, $id]);
            responseJson('success', 'เปลี่ยนสถานะอุปกรณ์สำเร็จ');
        }
        catch (Exception $e) {
            responseJson('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
        break;

    default:
        responseJson('error', 'Invalid action');
}
?>
