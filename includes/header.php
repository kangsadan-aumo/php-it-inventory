<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Protect all pages except login.php
$current_page = basename($_SERVER['PHP_SELF']);
if (!isset($_SESSION['user_id']) && $current_page !== 'login.php') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Inventory Management</title>
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Animate.css for subtle animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- ตั้งค่าธีม สีของ Tailwind เบื้องต้นให้ดูสบายตา -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6', // blue-500
                        secondary: '#1e40af', // blue-800
                        accent: '#10b981', // emerald-500
                        light: '#f3f4f6', // gray-100
                        dark: '#1f2937', // gray-800
                    },
                    fontFamily: {
                        sans: ['"Prompt"', 'sans-serif'], // ฟอนต์ไทยที่ดูดี
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Prompt', sans-serif; background-color: #f8fafc; color: #334155; padding-bottom: 70px; }
        @media (min-width: 768px) { body { padding-bottom: 0; } }
    </style>
</head>
<body class="flex flex-col min-h-screen">

<!-- Navbar / Header -->
<nav class="bg-white shadow-md sticky top-0 z-50 animate__animated animate__fadeInDown">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="index.php" class="flex items-center gap-2 text-primary font-bold text-xl hover:text-secondary transition-colors">
                    <i class="fa-solid fa-desktop"></i>
                    IT Inventory
                </a>
            </div>
            

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-4">
                <a href="index.php" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-primary hover:bg-blue-50 transition-all font-semibold"><i class="fa-solid fa-chart-line mr-1"></i> แดชบอร์ด</a>
                <a href="equipments.php" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-primary hover:bg-blue-50 transition-all"><i class="fa-solid fa-boxes-stacked mr-1"></i> อุปกรณ์</a>
                <a href="borrow.php" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-primary hover:bg-blue-50 transition-all"><i class="fa-solid fa-hand-holding-hand mr-1"></i> ยืม-คืน</a>
                <a href="history.php" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-primary hover:bg-blue-50 transition-all"><i class="fa-solid fa-clock-rotate-left mr-1"></i> ประวัติ</a>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                <div class="border-l pl-4 flex items-center gap-3 ml-2">
                    <span class="text-sm font-medium text-gray-600"><i class="fa-solid fa-circle-user text-gray-400 mr-1"></i> <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></span>
                    <button onclick="logoutUser()" class="px-3 py-1.5 rounded-md text-sm font-medium text-red-600 hover:text-white hover:bg-red-500 border border-red-200 hover:border-red-500 transition-all"><i class="fa-solid fa-arrow-right-from-bracket mr-1"></i> ออกจากระบบ</button>
                </div>
                <?php
endif; ?>
            </div>
        </div>
    </div>
    
    <script>
    function logoutUser() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'ยืนยันออกจากระบบ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ออกจากระบบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    executeLogout();
                }
            });
        } else {
            if (confirm('ยืนยันออกจากระบบ?')) {
                executeLogout();
            }
        }
    }
    
    function executeLogout() {
        let fd = new FormData();
        fd.append('action', 'logout');
        fetch('api/login_api.php', { method: 'POST', body: fd })
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                window.location.href = 'login.php';
            }
        }).catch(err => {
            window.location.href = 'login.php';
        });
    }
    </script>
</nav>

<!-- Main Container -->
<main class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate__animated animate__fadeIn">
