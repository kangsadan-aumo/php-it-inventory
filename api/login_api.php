<?php
session_start();
require_once '../config.php';

$action = $_POST['action'] ?? '';

if ($action === 'login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        responseJson('error', 'กรุณากรอกข้อมูลให้ครบถ้วน');
    }

    try {
        $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            responseJson('success', 'เข้าสู่ระบบสำเร็จ');
        }
        else {
            responseJson('error', 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง');
        }
    }
    catch (PDOException $e) {
        responseJson('error', 'Error: ' . $e->getMessage());
    }
}
elseif ($action === 'logout') {
    session_destroy();
    responseJson('success', 'ออกจากระบบสำเร็จ');
}

responseJson('error', 'Invalid action');
?>
