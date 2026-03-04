<?php
require_once 'config.php';
try {
    $stmt = $pdo->query("SHOW COLUMNS FROM equipments");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
}
catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
