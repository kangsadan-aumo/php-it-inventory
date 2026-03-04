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

    case 'get_brands':
        // ดึงรายชื่อยี่ห้อ (Brand) ที่เคยบันทึกไว้สำหรับ Auto-complete 
        try {
            $type = $_GET['type'] ?? 'Monitor'; // ค่าเริ่มต้นเป็น Monitor ตามโจทย์
            // Query แบบ Distinct เพื่อไม่ให้ยี่ห้อซ้ำกัน
            $stmt = $pdo->prepare("SELECT DISTINCT brand FROM equipments WHERE type = ? AND brand IS NOT NULL AND brand != '' ORDER BY brand ASC");
            $stmt->execute([$type]);
            $brands = $stmt->fetchAll(PDO::FETCH_COLUMN); // ดึงมาเฉพาะ column brand เป็น array เพียวๆ
            responseJson('success', 'Brands retrieved', $brands);
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

            // Prepare storage JSON
            $storageData = [];
            $storageTypes = $_POST['storage_types'] ?? [];
            $storageGbs = $_POST['storage_gbs'] ?? [];
            if (is_array($storageTypes) && is_array($storageGbs)) {
                for ($i = 0; $i < count($storageTypes); $i++) {
                    if (!empty($storageTypes[$i]) && !empty($storageGbs[$i])) {
                        $storageData[] = [
                            "type" => $storageTypes[$i],
                            "gb" => (int)$storageGbs[$i]
                        ];
                    }
                }
            }
            $storageJson = !empty($storageData) ? json_encode($storageData) : null;

            $sql = "INSERT INTO equipments (barcode, serial_number, type, brand, model, cpu_gen, ram_gb, storage_json, os, location, status, remark) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $barcode,
                $_POST['serial_number'] ?? null,
                $type,
                $_POST['brand'] ?? null,
                $_POST['model'] ?? null,
                $_POST['cpu_gen'] ?? null,
                !empty($_POST['ram_gb']) ? $_POST['ram_gb'] : null,
                $storageJson,
                $_POST['os'] ?? null,
                $_POST['location'] ?? null,
                $_POST['status'] ?? 'available',
                $_POST['remark'] ?? null
            ]);

            responseJson('success', 'เพิ่มอุปกรณ์สำเร็จ');
        }
        catch (Exception $e) {
            responseJson('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
        break;

    case 'get_single':
        // ดึงข้อมูลอุปกรณ์ชิ้นเดียวเพื่อไปแสดงใน Form Edit
        try {
            $id = $_GET['id'] ?? 0;
            if (empty($id)) {
                responseJson('error', 'ไม่พบรหัสอุปกรณ์');
            }
            $stmt = $pdo->prepare("SELECT * FROM equipments WHERE id = ?");
            $stmt->execute([$id]);
            $item = $stmt->fetch();
            if ($item) {
                responseJson('success', 'Data retrieved', $item);
            }
            else {
                responseJson('error', 'ไม่พบข้อมูลอุปกรณ์นี้');
            }
        }
        catch (Exception $e) {
            responseJson('error', $e->getMessage());
        }
        break;

    case 'update':
        // แก้ไขข้อมูลอุปกรณ์
        try {
            $id = $_POST['id'] ?? 0;
            $barcode = trim($_POST['barcode'] ?? '');
            $type = trim($_POST['type'] ?? '');
            $status = $_POST['status'] ?? 'available';

            if (empty($id) || empty($barcode) || empty($type)) {
                responseJson('error', 'โปรดกรอกบาร์โค้ดและประเภทอุปกรณ์');
            }

            // เช็คบาร์โค้ดซ้ำ (ยกเว้นตัวเอง)
            $stmtCheck = $pdo->prepare("SELECT id FROM equipments WHERE barcode = ? AND id != ?");
            $stmtCheck->execute([$barcode, $id]);
            if ($stmtCheck->rowCount() > 0) {
                responseJson('error', 'บาร์โค้ดนี้มีอยู่ในระบบแล้ว');
            }

            // Prepare storage JSON
            $storageData = [];
            $storageTypes = $_POST['storage_types'] ?? [];
            $storageGbs = $_POST['storage_gbs'] ?? [];
            if (is_array($storageTypes) && is_array($storageGbs)) {
                for ($i = 0; $i < count($storageTypes); $i++) {
                    if (!empty($storageTypes[$i]) && !empty($storageGbs[$i])) {
                        $storageData[] = [
                            "type" => $storageTypes[$i],
                            "gb" => (int)$storageGbs[$i]
                        ];
                    }
                }
            }
            $storageJson = !empty($storageData) ? json_encode($storageData) : null;

            $sql = "UPDATE equipments SET 
                    barcode=?, serial_number=?, type=?, brand=?, model=?, 
                    cpu_gen=?, ram_gb=?, storage_json=?, os=?, location=?, status=?, remark=?
                    WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $barcode,
                $_POST['serial_number'] ?? null,
                $type,
                $_POST['brand'] ?? null,
                $_POST['model'] ?? null,
                $_POST['cpu_gen'] ?? null,
                !empty($_POST['ram_gb']) ? $_POST['ram_gb'] : null,
                $storageJson,
                $_POST['os'] ?? null,
                $_POST['location'] ?? null,
                $status,
                $_POST['remark'] ?? null,
                $id
            ]);

            responseJson('success', 'อัปเดตข้อมูลอุปกรณ์สำเร็จ');
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
