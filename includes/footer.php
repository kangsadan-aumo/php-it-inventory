</main>

<!-- Footer -->
<footer class="bg-white border-t border-gray-200 mt-auto">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <p class="text-center text-sm text-gray-500">
            &copy; 2026 IT Inventory Management System. All rights reserved.
        </p>
    </div>
</footer>

<!-- Mobile Bottom Navigation (Visible only on mobile) -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50 flex justify-around items-center h-16 shadow-[0_-2px_10px_rgba(0,0,0,0.05)] pb-[env(safe-area-inset-bottom)]">
    <a href="index.php" class="flex flex-col items-center justify-center w-full h-full text-gray-500 hover:text-primary transition-colors">
        <i class="fa-solid fa-chart-line text-xl mb-1"></i>
        <span class="text-[10px] font-medium">แดชบอร์ด</span>
    </a>
    <a href="equipments.php" class="flex flex-col items-center justify-center w-full h-full text-gray-500 hover:text-primary transition-colors">
        <i class="fa-solid fa-boxes-stacked text-xl mb-1"></i>
        <span class="text-[10px] font-medium">อุปกรณ์</span>
    </a>
    <a href="borrow.php" class="flex flex-col items-center justify-center w-full h-full text-gray-500 hover:text-primary transition-colors relative">
        <div class="absolute -top-4 bg-primary text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg border-4 border-[#f8fafc]">
            <i class="fa-solid fa-handshake text-xl mt-1"></i>
        </div>
        <span class="text-[10px] font-medium mt-6 text-primary">ยืม-คืน</span>
    </a>
    <a href="history.php" class="flex flex-col items-center justify-center w-full h-full text-gray-500 hover:text-primary transition-colors">
        <i class="fa-solid fa-clock-rotate-left text-xl mb-1"></i>
        <span class="text-[10px] font-medium">ประวัติ</span>
    </a>
</nav>

<!-- jQuery (Required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- DataTables & Plugins (Responsive, Buttons for Export Excel/CSV) -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<!-- SweetAlert2 for nice alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- HTML5 QR Code Scanner -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<!-- Custom JS -->
<script src="assets/js/main.js"></script>

<script>
// ตั้งค่าพื้นฐาน DataTables ให้แสดงผลเป็นภาษาไทย และมี Animation เบาๆ
$.extend(true, $.fn.dataTable.defaults, {
    language: {
        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json'
    },
    responsive: true,
    dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"l<"flex items-center gap-2"fB>>rt<"flex flex-col md:flex-row justify-between items-center mt-4"ip>',
    buttons: [
        {
            extend: 'excelHtml5',
            text: '<i class="fa-solid fa-file-excel mr-1"></i> Excel',
            className: 'bg-green-600 hover:bg-green-700 text-white font-bold py-1 px-3 rounded shadow-sm transition-all focus:outline-none text-sm ml-2'
        },
        {
            extend: 'print',
            text: '<i class="fa-solid fa-print mr-1"></i> พิมพ์',
            className: 'bg-gray-600 hover:bg-gray-700 text-white font-bold py-1 px-3 rounded shadow-sm transition-all focus:outline-none text-sm ml-2'
        }
    ]
});
</script>

</body>
</html>
