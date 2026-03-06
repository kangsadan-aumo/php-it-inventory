<?php
/**
 * config.php
 * ไฟล์สำหรับตั้งค่าและเชื่อมต่อฐานข้อมูล MySQL ด้วย PDO
 */

$host = 'sql313.infinityfree.com';
$dbname = 'if0_41310422_it_inventory_db';
$user = 'if0_41310422';
$pass = 'Kangsadan45';

try {
    // สร้างการเชื่อมต่อ PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);

    // ตั้งค่า Error Mode ให้แสดง Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // ตั้งค่า Default Fetch Mode เป็น Associative Array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


}
catch (PDOException $e) {
    // ถ้าต่อ Database ไม่ได้ ให้พ่น Error (สามารถปิดใน Production ได้)
    die(json_encode([
        'status' => 'error',
        'message' => 'Database connection failed: ' . $e->getMessage()
    ]));
}

// Helper Function ส่งค่า JSON ง่ายๆ เผื่อใช้สำหรับเรียก API
function responseJson($status, $message, $data = null)
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}
?>
