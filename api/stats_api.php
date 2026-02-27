<?php
/**
 * stats_api.php
 * API สำหรับดึงข้อมูลสถิติหน้า Dashboard
 */
require_once '../config.php';

$action = $_GET['action'] ?? '';

if ($action === 'get_stats') {
    try {
        // นับจำนวนอุปกรณ์ทั้งหมด
        $stmtTotal = $pdo->query("SELECT COUNT(*) AS total FROM equipments");
        $total = $stmtTotal->fetch()['total'];

        // นับจำนวนที่กำลังยืม
        $stmtBorrowed = $pdo->query("SELECT COUNT(*) AS borrowed FROM equipments WHERE status = 'borrowed'");
        $borrowed = $stmtBorrowed->fetch()['borrowed'];

        // นับจำนวนที่พร้อมยืม
        $stmtAvailable = $pdo->query("SELECT COUNT(*) AS available FROM equipments WHERE status = 'available'");
        $available = $stmtAvailable->fetch()['available'];

        // ซ่อมบำรุง / เสีย
        $stmtOther = $pdo->query("SELECT COUNT(*) AS other FROM equipments WHERE status IN ('maintenance', 'broken')");
        $other = $stmtOther->fetch()['other'];

        // ดึงรายการยืมล่าสุด 5 รายการ
        $stmtLatest = $pdo->query("
            SELECT b.id, emp.emp_code as employee_id, emp.emp_name as employee_name, b.borrow_date, e.barcode, e.type, e.brand 
            FROM borrowings b 
            JOIN equipments e ON b.equipment_id = e.id 
            JOIN employees emp ON b.employee_id = emp.id
            WHERE b.status = 'active' 
            ORDER BY b.borrow_date DESC LIMIT 5
        ");
        $latest_borrowings = $stmtLatest->fetchAll();

        responseJson('success', 'Stats retrieved', [
            'total' => $total,
            'borrowed' => $borrowed,
            'available' => $available,
            'other' => $other,
            'latest_borrowings' => $latest_borrowings
        ]);

    }
    catch (Exception $e) {
        responseJson('error', $e->getMessage());
    }
}
else {
    responseJson('error', 'Invalid action');
}
?>
