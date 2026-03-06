<?php
require_once 'config.php';

try {
    // 1. สร้างตาราง users
    $sql_create = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL COMMENT 'ชื่อผู้ใช้งาน',
        password VARCHAR(255) NOT NULL COMMENT 'รหัสผ่าน (Hash)',
        role ENUM('admin', 'user') DEFAULT 'admin' COMMENT 'สิทธิ์การใช้งาน',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql_create);
    echo "สร้างตาราง users เรียบร้อยแล้ว<br>";

    // 2. เติมข้อมูล admin:password หากยังไม่มี
    // รหัสผ่านคือ: password
    $password_hash = password_hash('password', PASSWORD_DEFAULT);

    $check = $pdo->query("SELECT id FROM users WHERE username = 'admin'")->fetch();
    if (!$check) {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute(['admin', $password_hash, 'admin']);
        echo "เพิ่มผู้ใช้ admin สำเร็จ<br>";
    }
    else {
        // หากมีอยู่แล้ว ให้ทำการอัปเดตรหัสผ่านใหม่เพื่อความชัวร์
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
        $stmt->execute([$password_hash]);
        echo "มีผู้ใช้ admin อยู่แล้ว ทำการรีเซ็ตรหัสผ่านเป็น 'password' ให้ใหม่สำเร็จ<br>";
    }

    echo "<br><b>อัปเดตฐานข้อมูลสำเร็จ!</b>";
}
catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
