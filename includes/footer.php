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
    <a href="borrow.php" class="flex flex-col items-center justify-center w-full h-full text-gray-500 hover:text-primary transition-colors">
        <i class="fa-solid fa-handshake text-xl mt-1"></i>
        <span class="text-[10px] font-medium">ยืม-คืน</span>
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

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
    dom: '<"hidden"f>rt<"flex flex-col md:flex-row justify-between items-center mt-4"ip>'
});
</script>

<script>
// จัดการเปลี่ยนสี Active Menu (สีน้ำเงิน) แบบทำงานผ่าน JavaScript ป้องกันปัญหา Cache หรือ Path
document.addEventListener('DOMContentLoaded', () => {
    const currentPath = window.location.pathname.toLowerCase();
    let activePage = 'index.php';
    if (currentPath.includes('equipments')) activePage = 'equipments.php';
    else if (currentPath.includes('borrow')) activePage = 'borrow.php';
    else if (currentPath.includes('history')) activePage = 'history.php';

    // เปลี่ยนสีเมนูด้านบน (Desktop)
    document.querySelectorAll('.hidden.md\\:flex a').forEach(link => {
        if (link.getAttribute('href') === activePage) {
            // ลบคลาสสีเทาเดิมออก เพิ่มคลาสสีน้ำเงินและทำตัวหนา
            link.classList.remove('text-gray-700', 'font-medium');
            link.classList.add('text-blue-600', 'bg-blue-50', 'font-bold');
            // บังคับสีป้องกัน Tailwind CDN โหลดไม่ทัน
            link.style.color = '#2563eb'; 
        }
    });

    // เปลี่ยนสีเมนูด้านล่าง (Mobile)
    document.querySelectorAll('.md\\:hidden.fixed.bottom-0 a').forEach(link => {
        if (link.getAttribute('href') === activePage) {
            link.classList.remove('text-gray-500');
            link.classList.add('text-blue-600', 'font-bold');
            link.style.color = '#2563eb';
        }
    });
});
</script>

</body>
</html>
