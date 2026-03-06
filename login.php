<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - IT Inventory System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#3b82f6', secondary: '#1e40af' },
                    fontFamily: { sans: ['"Prompt"', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Prompt', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md p-8 bg-white rounded-2xl shadow-xl animate__animated animate__fadeInUp border border-gray-100">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-4 shadow-inner">
                <i class="fa-solid fa-desktop text-3xl text-primary"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">IT Inventory System</h2>
            <p class="text-sm text-gray-500 mt-2">กรุณาเข้าสู่ระบบเพื่อดำเนินการต่อ</p>
        </div>

        <form id="loginForm" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อผู้ใช้งาน (Username)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-user text-gray-400"></i>
                    </div>
                    <input type="text" name="username" required class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm shadow-sm placeholder-gray-400" placeholder="admin">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่าน (Password)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" name="password" id="password" required class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm shadow-sm placeholder-gray-400" placeholder="••••••••">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" onclick="togglePassword()">
                        <i class="fa-solid fa-eye text-gray-400 hover:text-gray-600" id="toggleIcon"></i>
                    </div>
                </div>
            </div>

            <button type="submit" id="submitBtn" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors mt-6">
                <i class="fa-solid fa-right-to-bracket mr-2 mt-0.5"></i> เข้าสู่ระบบ
            </button>
        </form>
    </div>

    <script>
        function togglePassword() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                pwd.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> กำลังตรวจสอบ...';

            const fd = new FormData(this);
            fd.append('action', 'login');

            fetch('api/login_api.php', { method: 'POST', body: fd })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    Swal.fire({
                        title: 'เข้าสู่ระบบสำเร็จ',
                        text: 'ย้ายไปยังหน้าแดชบอร์ด...',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = 'index.php';
                    });
                } else {
                    Swal.fire('เกิดข้อผิดพลาด', res.message, 'error');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-right-to-bracket mr-2 mt-0.5"></i> เข้าสู่ระบบ';
                }
            })
            .catch(err => {
                Swal.fire('ข้อผิดพลาดเครือข่าย', 'ไม่สามารถเชื่อมต่อระบบได้ ลองอีกครั้ง', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-right-to-bracket mr-2 mt-0.5"></i> เข้าสู่ระบบ';
            });
        });
    </script>
</body>
</html>
