<?php
require_once 'config.php';
try {
    // 1. เพิ่มคอลัมน์ storage_json
    $pdo->exec("ALTER TABLE equipments ADD COLUMN storage_json JSON NULL COMMENT 'ข้อมูล storage หลายตัว';");
    echo "Success: added storage_json column to equipments.<br>";

    // 2. ย้ายข้อมูลเดิมจาก hdd_gb, ssd_gb, m2_gb มาเป็น JSON array
    // อ่านข้อมูลทั้งหมด
    $stmt = $pdo->query("SELECT id, hdd_gb, ssd_gb, m2_gb FROM equipments");
    $equipments = $stmt->fetchAll();

    $updateStmt = $pdo->prepare("UPDATE equipments SET storage_json = :json WHERE id = :id");

    foreach ($equipments as $eq) {
        $storage = [];
        if (!empty($eq['hdd_gb'])) {
            $storage[] = ["type" => "HDD", "gb" => $eq['hdd_gb']];
        }
        if (!empty($eq['ssd_gb'])) {
            $storage[] = ["type" => "SSD", "gb" => $eq['ssd_gb']];
        }
        if (!empty($eq['m2_gb'])) {
            $storage[] = ["type" => "M.2", "gb" => $eq['m2_gb']];
        }

        if (count($storage) > 0) {
            $json = json_encode($storage);
            $updateStmt->execute(['json' => $json, 'id' => $eq['id']]);
        }
    }
    echo "Success: migrated existing data to storage_json.<br>";

    // 3. (Optional) ลบคอลัมน์ hdd_gb, ssd_gb, m2_gb ทิ้ง
    $pdo->exec("ALTER TABLE equipments 
                DROP COLUMN hdd_gb, 
                DROP COLUMN ssd_gb, 
                DROP COLUMN m2_gb;");
    echo "Success: removed old columns.<br>";
}
catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
