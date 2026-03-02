<?php require_once 'includes/header.php'; ?>

<div class="mb-4 animate__animated animate__fadeInDown">
    <h1 class="text-3xl font-extrabold text-gray-800">ประวัติการยืม-คืน <i class="fa-solid fa-clock-rotate-left text-primary"></i></h1>
    <p class="text-gray-500 mt-1">ดูประวัติทั้งหมด และ Export ข้อมูลเป็น Excel</p>
</div>

<!-- Control Panel (ตรึงด้านบนเวลาเลื่อน) -->
<div class="sticky top-[64px] z-40 bg-white/95 backdrop-blur-md rounded-xl shadow-[0_4px_10px_rgba(0,0,0,0.05)] border border-gray-200 p-4 mb-4 transform transition-all">
    <div class="flex flex-row items-end gap-2">
        <!-- ช่องค้นหา -->
        <div class="flex-grow">
            <label class="block text-xs font-bold text-gray-700 mb-1">ค้นหาประวัติการยืม</label>
            <input type="text" id="customSearch" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary text-sm p-2 border outline-none" placeholder="...">
        </div>
        <!-- ปุ่ม Excel (Icon) -->
        <button id="customExcel" class="shrink-0 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md shadow-sm transition-all focus:outline-none flex items-center justify-center h-[38px] w-[42px] sm:w-auto" title="โหลดข้อมูลเป็น Excel">
            <i class="fa-solid fa-file-excel text-lg"></i>
            <span class="hidden sm:inline font-bold text-sm ml-2">Export</span>
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 relative z-10 animate__animated animate__fadeInUp overflow-hidden">
    <table id="historyTable" class="w-full text-left whitespace-nowrap" style="width:100%">
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
let historyTable;

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

                // ใช้งาน DataTables ตัวเต็มพร้อมปุ่ม Export แต่ซ่อน UI เดิม
                historyTable = $('#historyTable').DataTable({
                    order: [[0, 'desc']], // เรียงตารางตาม วันที่ยืม ล่าสุด
                    responsive: true,
                    // B: Buttons (ซ่อน), r: processing, t: table, i: info, p: paging
                    dom: '<"hidden"B>rt<"flex flex-col md:flex-row justify-between items-center mt-4 text-sm"ip>',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            className: 'custom-excel-dt',
                            text: 'Excel',
                            title: 'Data_History',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7], // บังคับให้โหลดออกมาทุกคอลัมน์ แม้จะถูกซ่อนในจอมือถือ
                                format: {
                                    body: function(data, row, column, node) {
                                        // แทนที่การกดขึ้นบรรทัดใหม่ด้วยเว้นวรรค ให้ดูสวยงามใน Excel
                                        return data.replace(/<br\s*\/?>/ig, ' ').replace(/<[^>]*>?/gm, '').replace(/&nbsp;/g, ' ').trim();
                                    }
                                }
                            }
                        }
                    ],
                    language: {
                        emptyTable: "ไม่พบข้อมูลประวัติการยืม-คืน"
                    }
                });

                // เชื่อมช่องค้นหา Control Panel เข้ากับ DataTables
                $('#customSearch').off('keyup').on('keyup', function() {
                    historyTable.search(this.value).draw();
                });

                // เชื่อมปุ่ม Excel Control Panel เข้ากับ DataTables
                $('#customExcel').off('click').on('click', function() {
                    $('.custom-excel-dt').click();
                });

                // บังคับให้ตารางคำนวณขนาดและจัดคอลัมน์ใหม่เมื่อย่อ/ขยายหน้าจอ
                $(window).on('resize', function() {
                    if (historyTable) {
                        historyTable.columns.adjust().responsive.recalc();
                    }
                });
            }
        });
}
</script>

<?php require_once 'includes/footer.php'; ?>
