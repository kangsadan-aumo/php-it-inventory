<?php
require_once 'config.php';
try {
    $pdo->exec("ALTER TABLE borrowings Add COLUMN location VARCHAR(150) NULL COMMENT 'ห้องที่ตั้งอุปกรณ์' AFTER employee_id;");
    echo "Success";
}
catch (Exception $e) {
    echo $e->getMessage();
}
?>
