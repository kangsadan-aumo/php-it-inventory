<?php
require_once 'config.php';
try {
    $pdo->exec("ALTER TABLE equipments ADD COLUMN location VARCHAR(255) NULL AFTER serial_number");
    echo "Column location added successfully.";
}
catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
