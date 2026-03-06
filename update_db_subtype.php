<?php
require_once 'config.php';

try {
    // 1. ตรวจสอบว่ามีคอลัมน์ sub_type ในตาราง equipments หรือยัง
    $stmt = $pdo->query("SHOW COLUMNS FROM equipments LIKE 'sub_type'");
    $columnExists = $stmt->fetch();

    if (!$columnExists) {
        // 2. ถ้ายังไม่มี ให้เพิ่มคอลัมน์ใหม่ต่อจาก type
        $sql_alter = "ALTER TABLE equipments ADD COLUMN sub_type VARCHAR(100) NULL COMMENT 'ประเภทย่อย เช่น เมาส์, คีย์บอร์ด (ใช้กับ type=Other)' AFTER type";
        $pdo->exec($sql_alter);
        echo "✅ เพิ่มคอลัมน์ 'sub_type' สำเร็จลุล่วง!<br>";
    }
    else {
        echo "ℹ️ คอลัมน์ 'sub_type' มีอยู่ก่อนแล้ว ไม่ต้องทำอะไรเพิ่ม<br>";
    }

    echo "<br><b>อัปเดตฐานข้อมูลสำเร็จ! กลับไปใช้งานระบบต่อได้เลย</b>";

}
catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
