<?php require_once 'includes/header.php'; ?>

<div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4 animate__animated animate__fadeInDown">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-800">ประวัติการยืม-คืน <i class="fa-solid fa-clock-rotate-left text-primary"></i></h1>
        <p class="text-gray-500 mt-1">ดูประวัติทั้งหมด และ Export ข้อมูลเป็น Excel</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 animate__animated animate__fadeInUp">
    <table id="historyTable" class="w-full text-left" style="width:100%">
        <thead class="bg-gray-50 text-gray-700">
            <tr>
                <th class="py-2 px-3">วัน/เวลายืม</th>
                <th class="py-2 px-3">วัน/เวลาคืน</th>
                <th class="py-2 px-3">รหัสพนักงาน</th>
                <th class="py-2 px-3">ชื่อ-นามสกุล</th>
                <th class="py-2 px-3">สถานที่ (ห้อง)</th>
                <th class="py-2 px-3">บาร์โค้ด <span class="text-xs text-gray-400">ซีเรียลจอ</span></th>
                <th class="py-2 px-3">อุปกรณ์ <span class="text-xs text-gray-400">ยี่ห้อ/รุ่น</span></th>
                <th class="py-2 px-3">สถานะ</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded via AJAX/JS -->
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    loadHistory();
});

function loadHistory() {
    fetch('api/borrow_api.php?action=get_history')
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                let tbody = document.querySelector('#historyTable tbody');
                tbody.innerHTML = '';
                
                res.data.forEach(item => {
                    let statusBadge = '';
                    if(item.borrow_status === 'active') {
                        statusBadge = '<span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold"><i class="fa-solid fa-clock mr-1"></i>กำลังยืม</span>';
                    } else {
                        statusBadge = '<span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold"><i class="fa-solid fa-check mr-1"></i>คืนแล้ว</span>';
                    }

                    // จัดรูปแบบวันที่ (ลบวินาทีออกเพื่อความคลีน)
                    let bDate = item.borrow_date ? item.borrow_date.substring(0, 16) : '-';
                    let rDate = item.return_date ? item.return_date.substring(0, 16) : '-';

                    let tr = document.createElement('tr');
                    tr.className = 'hover-table-row';
                    tr.innerHTML = `
                        <td class="py-2 px-3 text-sm">${bDate}</td>
                        <td class="py-2 px-3 text-sm ${!item.return_date ? 'text-gray-400' : ''}">${rDate}</td>
                        <td class="py-2 px-3 font-medium text-blue-600">${item.employee_id}</td>
                        <td class="py-2 px-3">${item.employee_name}</td>
                        <td class="py-2 px-3 text-sm text-gray-600">${item.location || '-'}</td>
                        <td class="py-2 px-3 font-mono text-sm">
                            ${item.barcode} <br>
                            <span class="text-xs text-green-600">${item.serial_number || ''}</span>
                        </td>
                        <td class="py-2 px-3">
                            <span class="font-medium">${item.type}</span> <br>
                            <span class="text-xs text-gray-500">${item.brand || ''} ${item.model || ''}</span>
                        </td>
                        <td class="py-2 px-3">${statusBadge}</td>
                    `;
                    tbody.appendChild(tr);
                });

                // ใช้งาน DataTables ตัวเต็มพร้อมปุ่ม Export
                $('#historyTable').DataTable({
                    order: [[0, 'desc']] // เรียงตารางตาม วันที่ยืม ล่าสุด
                });
            }
        });
}
</script>

<?php require_once 'includes/footer.php'; ?>
