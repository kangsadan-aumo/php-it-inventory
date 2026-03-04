<?php
require_once 'config.php';
try {
    // 1. เพิ่มคอลัมน์ใหม่
    $pdo->exec("ALTER TABLE equipments 
                ADD COLUMN hdd_gb INT NULL COMMENT 'ขนาดความจุ HDD (GB)',
                ADD COLUMN ssd_gb INT NULL COMMENT 'ขนาดความจุ SSD (GB)',
                ADD COLUMN m2_gb INT NULL COMMENT 'ขนาดความจุ M.2 (GB)';");
    echo "Success: added hdd_gb, ssd_gb, m2_gb columns to equipments.<br>";

    // 2. ย้ายข้อมูลเดิม
    $pdo->exec("UPDATE equipments SET hdd_gb = storage_gb WHERE storage_type = 'HDD'");
    echo "Success: migrated HDD data.<br>";

    $pdo->exec("UPDATE equipments SET ssd_gb = storage_gb WHERE storage_type = 'SSD'");
    echo "Success: migrated SSD data.<br>";

    $pdo->exec("UPDATE equipments SET m2_gb = storage_gb WHERE storage_type = 'M.2'");
    echo "Success: migrated M.2 data.<br>";

// หมายเหตุ: เรายังไม่ลบคอลัมน์ storage_type กับ storage_gb เผื่อเอาไว้ก่อน แต่ข้อมูลปัจจุบันถูก migrate ไปหมดแล้ว
}
catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
