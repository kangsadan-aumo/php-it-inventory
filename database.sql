-- สร้างฐานข้อมูล
CREATE DATABASE IF NOT EXISTS it_inventory_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE it_inventory_db;

-- ตารางอุปกรณ์ IT
CREATE TABLE IF NOT EXISTS equipments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    barcode VARCHAR(100) UNIQUE NOT NULL COMMENT 'รหัสบาร์โค้ดติดอุปกรณ์ (จำเป็น)',
    serial_number VARCHAR(100) COMMENT 'หมายเลขซีเรียลของหน้าจอ (กันสลับจอ)',
    type VARCHAR(50) NOT NULL COMMENT 'ประเภท เช่น PC, Notebook, Monitor',
    brand VARCHAR(100) COMMENT 'ยี่ห้อ',
    model VARCHAR(100) COMMENT 'รุ่น',
    cpu_gen VARCHAR(100) COMMENT 'สเปค CPU เช่น i5 Gen 12',
    ram_gb INT COMMENT 'ขนาด RAM (GB)',
    storage_json JSON NULL COMMENT 'ข้อมูล storage หลายตัว เช่น [{"type":"SSD", "gb":512}, {"type":"HDD", "gb":1000}]',
    os VARCHAR(50) COMMENT 'ระบบปฏิบัติการ เช่น Windows 11',
    status ENUM('available', 'borrowed', 'maintenance', 'broken') DEFAULT 'available' COMMENT 'สถานะเครื่อง',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ตารางผู้ยืม (พนักงาน)
CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    emp_code VARCHAR(50) UNIQUE NOT NULL COMMENT 'รหัสพนักงาน',
    emp_name VARCHAR(150) NOT NULL COMMENT 'ชื่อ-นามสกุล',
    department VARCHAR(100) COMMENT 'แผนก/ฝ่าย',
    position VARCHAR(100) COMMENT 'ตำแหน่ง',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ตารางการยืม-คืน
CREATE TABLE IF NOT EXISTS borrowings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipment_id INT NOT NULL COMMENT 'อ้างอิง ID อุปกรณ์',
    employee_id INT NOT NULL COMMENT 'อ้างอิง ID พนักงาน',
    location VARCHAR(150) NULL COMMENT 'ห้องที่ตั้งอุปกรณ์',
    borrow_date DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่และเวลายืม',
    return_date DATETIME NULL COMMENT 'วันที่และเวลาคืน (ถ้ายังไม่คืนจะว่าง)',
    status ENUM('active', 'returned') DEFAULT 'active' COMMENT 'สถานะการยืม (active=กำลังยืม, returned=คืนแล้ว)',
    FOREIGN KEY (equipment_id) REFERENCES equipments(id) ON DELETE CASCADE,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
);

-- เพิ่มข้อมูลจำลองเล็กน้อยเผื่อไว้ทดสอบ (ปรับแก้ได้)
INSERT INTO equipments (barcode, serial_number, type, brand, model, cpu_gen, ram_gb, storage_json, os, status) VALUES
('PC-001', 'SN-MON-991', 'PC', 'DELL', 'OptiPlex 7090', 'i7 Gen 11', 16, '[{"type":"M.2","gb":512}]', 'Windows 11 Pro', 'available'),
('NB-002', NULL, 'Notebook', 'Lenovo', 'ThinkPad T14', 'i5 Gen 12', 16, '[{"type":"M.2","gb":512}]', 'Windows 11 Pro', 'available');

INSERT INTO employees (emp_code, emp_name, department, position) VALUES
('EMP001', 'สมชาย ใจดี', 'IT Support', 'IT Officer'),
('EMP002', 'สมหญิง รักงาน', 'HR', 'HR Manager');
