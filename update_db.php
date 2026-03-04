<?php
require_once 'config.php';
try {
    $pdo->exec("ALTER TABLE equipments ADD COLUMN remark TEXT NULL COMMENT 'หมายเหตุการซ่อมหรืออื่นๆ';");
    echo "Success: added remark column to equipments";
}
catch (Exception $e) {
    echo $e->getMessage();
}
?>
