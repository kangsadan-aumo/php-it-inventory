<?php

// โหลดส่วน Header
require_once 'includes/header.php';

?>

<!-- หัวข้อและแอนิเมชัน -->
<div class="mb-8 animate__animated animate__fadeInLeft">
    <h1 class="text-3xl font-extrabold text-gray-800">แดชบอร์ดสรุปผล <span class="text-primary"><i class="fa-solid fa-chart-pie"></i></span></h1>
    <p class="text-gray-500 mt-1">ภาพรวมของอุปกรณ์ IT ทั้งหมดในระบบ</p>
</div>

<!-- สรุปสถิติ (Stats Cards) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card: อุปกรณ์ทั้งหมด -->
    <div class="glass-effect rounded-xl p-6 flex items-center justify-between border-l-4 border-blue-500 hover:scale-105 transition-transform duration-300">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">อุปกรณ์ทั้งหมด</p>
            <h3 class="text-3xl font-bold text-gray-800" id="stat-total">0</h3>
        </div>
        <div class="p-3 bg-blue-100 rounded-full text-blue-600">
            <i class="fa-solid fa-boxes-stacked text-2xl"></i>
        </div>
    </div>

    <!-- Card: ว่างพร้อมยืม -->
    <div class="glass-effect rounded-xl p-6 flex items-center justify-between border-l-4 border-emerald-500 hover:scale-105 transition-transform duration-300">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">พร้อมใช้งาน</p>
            <h3 class="text-3xl font-bold text-gray-800" id="stat-available">0</h3>
        </div>
        <div class="p-3 bg-emerald-100 rounded-full text-emerald-600">
            <i class="fa-solid fa-check-circle text-2xl"></i>
        </div>
    </div>

    <!-- Card: กำลังถูกยืม -->
    <div class="glass-effect rounded-xl p-6 flex items-center justify-between border-l-4 border-amber-500 hover:scale-105 transition-transform duration-300">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">กำลังถูกยืม</p>
            <h3 class="text-3xl font-bold text-gray-800" id="stat-borrowed">0</h3>
        </div>
        <div class="p-3 bg-amber-100 rounded-full text-amber-600">
            <i class="fa-solid fa-handshake text-2xl"></i>
        </div>
    </div>

    <!-- Card: ส่งซ่อม/มีปัญหา -->
    <div class="glass-effect rounded-xl p-6 flex items-center justify-between border-l-4 border-red-500 hover:scale-105 transition-transform duration-300">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">ซ่อมบำรุง/มีปัญหา</p>
            <h3 class="text-3xl font-bold text-gray-800" id="stat-other">0</h3>
        </div>
        <div class="p-3 bg-red-100 rounded-full text-red-600">
            <i class="fa-solid fa-wrench text-2xl"></i>
        </div>
    </div>
</div>

<!-- ตารางรายการที่ยืมล่าสุด (Latest Borrowings) -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 animate__animated animate__fadeInUp">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-800">รายการยืมล่าสุด (ที่ยังไม่คืน)</h2>
        <a href="history.php" class="text-sm text-primary hover:underline">ดูทั้งหมด <i class="fa-solid fa-arrow-right"></i></a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="latest-table">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm">
                    <th class="py-3 px-4 rounded-tl-lg font-medium">บาร์โค้ด</th>
                    <th class="py-3 px-4 font-medium">อุปกรณ์</th>
                    <th class="py-3 px-4 font-medium">ผู้ยืม</th>
                    <th class="py-3 px-4 rounded-tr-lg font-medium">วันที่ยืม</th>
                </tr>
            </thead>
            <tbody id="latest-tbody" class="text-sm">
                <!-- ข้อมูลจะถูกโหลดด้วย AJAX -->
                <tr>
                    <td colspan="4" class="text-center py-4 text-gray-500"><i class="fa-solid fa-spinner fa-spin text-primary"></i> กำลังโหลดข้อมูล...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- สคริปต์ดึงข้อมูลแบบ AJAX -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    fetchStats();
});

function fetchStats() {
    fetch('api/stats_api.php?action=get_stats')
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                // Animate ตัวเลขเมื่อโหลดเสร็จ สามารถเสริมได้ด้วย JS
                document.getElementById('stat-total').innerText = res.data.total;
                document.getElementById('stat-available').innerText = res.data.available;
                document.getElementById('stat-borrowed').innerText = res.data.borrowed;
                document.getElementById('stat-other').innerText = res.data.other;

                // อัพเดทตาราง
                let tbody = document.getElementById('latest-tbody');
                tbody.innerHTML = '';
                if(res.data.latest_borrowings.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-gray-500">ไม่มีรายการตกค้าง</td></tr>';
                } else {
                    res.data.latest_borrowings.forEach(item => {
                        let html = `
                            <tr class="border-b border-gray-100 hover-table-row">
                                <td class="py-3 px-4 font-mono text-blue-600">${item.barcode}</td>
                                <td class="py-3 px-4">${item.type} ${item.brand || ''}</td>
                                <td class="py-3 px-4 font-medium">${item.employee_name} <span class="text-xs text-gray-400">(${item.employee_id})</span></td>
                                <td class="py-3 px-4">${item.borrow_date}</td>
                            </tr>
                        `;
                        tbody.innerHTML += html;
                    });
                }
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        })
        .catch(err => {
            console.error('Fetch Error:', err);
        });
}
</script>

<?php require_once 'includes/footer.php'; ?>
