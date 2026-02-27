<?php require_once 'includes/header.php'; ?>

<div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4 animate__animated animate__fadeInDown">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-800">รายชื่อผู้ยืม <i class="fa-solid fa-users text-primary"></i></h1>
        <p class="text-gray-500 mt-1">จัดการรายชื่อพนักงาน และทำรายการยืม-คืนอุปกรณ์</p>
    </div>
    <button onclick="openAddEmpModal()" class="bg-primary hover:bg-secondary text-white font-bold py-2 px-4 rounded-lg shadow-md transition-all flex items-center gap-2">
        <i class="fa-solid fa-user-plus"></i> เพิ่มรายชื่อ
    </button>
</div>

<!-- ตารางแสดงรายชื่อพนักงาน -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 animate__animated animate__fadeInUp">
    <table id="employeeTable" class="w-full text-left" style="width:100%">
        <thead class="bg-gray-50 text-gray-700">
            <tr>
                <th class="py-2 px-3">รหัสพนักงาน</th>
                <th class="py-2 px-3">ชื่อ-นามสกุล</th>
                <th class="py-2 px-3">แผนก/ฝ่าย</th>
                <th class="py-2 px-3">ตำแหน่ง</th>
                <th class="py-2 px-3 text-center">จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded via AJAX/JS -->
        </tbody>
    </table>
</div>

<!-- Modal เพิ่มพนักงาน -->
<div id="addEmpModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeAddEmpModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate__animated animate__zoomIn">
            <form id="addEmpForm">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4 border-b pb-2"><i class="fa-solid fa-user text-primary"></i> ข้อมูลผู้ยืมใหม่</h3>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">รหัสพนักงาน <span class="text-red-500">*</span></label>
                            <input type="text" name="emp_code" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อ-นามสกุล <span class="text-red-500">*</span></label>
                            <input type="text" name="emp_name" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">แผนก/ฝ่าย</label>
                            <input type="text" name="department" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ตำแหน่ง</label>
                            <input type="text" name="position" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border">
                        </div>
                    </div>
                </div>
                <!-- ปุ่มกดยืนยัน -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-secondary focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        <i class="fa-solid fa-save mr-2 mt-1"></i> บันทึกข้อมูล
                    </button>
                    <button type="button" onclick="closeAddEmpModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        ยกเลิก
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal การยืมคืนสำหรับคนนี้ -->
<div id="actionModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeActionModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full animate__animated animate__fadeInUp">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="text-xl leading-6 font-bold text-gray-900 border-l-4 border-primary pl-2"><i class="fa-solid fa-user-circle text-gray-400"></i> ทำรายการ: <span id="modal_emp_name" class="text-primary"></span></h3>
                    <button onclick="closeActionModal()" class="text-gray-400 hover:text-red-500"><i class="fa-solid fa-times text-xl"></i></button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- ประวัติที่ยืมอยู่ -->
                    <div class="border rounded-lg p-4 bg-gray-50">
                        <h4 class="font-bold text-gray-700 mb-3"><i class="fa-solid fa-box-open text-amber-500"></i> อุปกรณ์ที่กำลังยืมอยู่ตอนนี้</h4>
                        <div id="borrowed_list" class="space-y-3 max-h-64 overflow-y-auto pr-2">
                            <div class="text-center py-4 text-gray-400">ไม่มีรายการยืม</div>
                        </div>
                    </div>

                    <!-- ฟอร์มยืมใหม่ -->
                    <div class="border rounded-lg p-4 bg-blue-50">
                        <h4 class="font-bold text-blue-800 mb-3"><i class="fa-solid fa-plus-circle text-primary"></i> ทำรายการยืมใหม่</h4>
                        <form id="borrowForm">
                            <input type="hidden" id="borrow_emp_id" name="employee_id">
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">เลือกอุปกรณ์ที่ต้องการยืม <span class="text-red-500">*</span></label>
                                <select id="equipment_select" name="equipment_id" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border mb-3">
                                    <option value="">-- โหลดข้อมูลอุปกรณ์... --</option>
                                </select>
                                
                                <label class="block text-sm font-medium text-gray-700 mb-1">สถานที่ตั้งอุปกรณ์ (ห้อง) <span class="text-red-500">*</span></label>
                                <select id="location_select" name="location" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border">
                                    <option value="">-- เลือกห้องที่ตั้งอุปกรณ์ --</option>
                                    <option value="ห้องHR">ห้องHR</option>
                                    <option value="ห้องTransport">ห้องTransport</option>
                                    <option value="ห้องบัญชี">ห้องบัญชี</option>
                                    <option value="ห้องSale">ห้องSale</option>
                                    <option value="ห้องAir fright">ห้องAir fright</option>
                                    <option value="ห้องXunyu">ห้องXunyu</option>
                                    <option value="ห้องCS">ห้องCS</option>
                                    <option value="ห้องผู้บริหาร">ห้องผู้บริหาร</option>
                                    <option value="แหลมฉบัง">แหลมฉบัง</option>
                                </select>
                                
                                <label class="block text-sm font-medium text-gray-700 mt-3 mb-1">วันที่/เวลายืม (ปรับแก้ได้) <span class="text-xs text-gray-400 font-normal ml-1">ถ้าไม่ใส่ระบบจะใช้วันนี้</span></label>
                                <input type="datetime-local" id="borrow_date_input" name="borrow_date" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border">
                            </div>

                            <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-bold py-2 px-4 rounded-lg shadow-md transition-all">
                                ยืนยันการยืมอุปกรณ์นี้
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t">
                <button type="button" onclick="closeActionModal()" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                    ปิดหน้าต่าง
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let empTable;
let allEquipments = [];

document.addEventListener('DOMContentLoaded', () => {
    loadEmployees();
    loadAvailableEquipments(); // โหลดรอไว้สำหรับ select box

    // แบบฟอร์มเพิ่มพนักงาน
    document.getElementById('addEmpForm').addEventListener('submit', function(e) {
        e.preventDefault();
        let fd = new FormData(this);
        fd.append('action', 'add');

        fetch('api/employee_api.php', { method: 'POST', body: fd })
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                Swal.fire({ title: 'สำเร็จ!', text: res.message, icon: 'success', timer: 1500, showConfirmButton: false });
                closeAddEmpModal();
                loadEmployees();
            } else {
                Swal.fire('ข้อผิดพลาด', res.message, 'error');
            }
        });
    });

    // ส่งฟอร์มยืม
    document.getElementById('borrowForm').addEventListener('submit', function(e) {
        e.preventDefault();
        let eqId = document.getElementById('equipment_select').value;
        if(!eqId) return Swal.fire('แจ้งเตือน', 'กรุณาเลือกอุปกรณ์', 'warning');

        let fd = new FormData(this);
        fd.append('action', 'borrow');

        fetch('api/borrow_api.php', { method: 'POST', body: fd })
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                Swal.fire({ title: 'สำเร็จ', text: res.message, icon: 'success', timer: 1500, showConfirmButton: false });
                
                // คืนค่าฟอร์ม และโหลดข้อมูลใหม่
                document.getElementById('equipment_select').value = '';
                document.getElementById('location_select').value = '';
                loadAvailableEquipments(); // โหลดอุปกรณ์ที่ว่างใหม่
                
                // โหลดประวัติใหม่
                let empId = document.getElementById('borrow_emp_id').value;
                loadEmployeeItems(empId);
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        });
    });
});

function loadAvailableEquipments() {
    fetch('api/equipment_api.php?action=get_all')
    .then(res => res.json())
    .then(res => {
        if(res.status === 'success') {
            allEquipments = res.data.filter(item => item.status === 'available');
            let select = document.getElementById('equipment_select');
            select.innerHTML = '<option value="">-- เลือกอุปกรณ์ (ค้นหาได้) --</option>';
            
            allEquipments.forEach(item => {
                let text = `[${item.barcode}] ${item.type} ${item.brand || ''} ${item.model || ''}`;
                select.innerHTML += `<option value="${item.id}">${text}</option>`;
            });
        }
    });
}

function loadEmployees() {
    fetch('api/employee_api.php?action=get_all')
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                if(empTable) empTable.destroy();
                let tbody = document.querySelector('#employeeTable tbody');
                tbody.innerHTML = '';
                
                res.data.forEach(emp => {
                    let tr = document.createElement('tr');
                    tr.className = 'hover-table-row';
                    tr.innerHTML = `
                        <td class="py-2 px-3 font-medium text-blue-600">${emp.emp_code}</td>
                        <td class="py-2 px-3">${emp.emp_name}</td>
                        <td class="py-2 px-3">${emp.department || '-'}</td>
                        <td class="py-2 px-3">${emp.position || '-'}</td>
                        <td class="py-2 px-3 text-center">
                            <button onclick="openActionModal(${emp.id}, '${emp.emp_name}')" class="bg-primary hover:bg-secondary text-white px-3 py-1 rounded text-sm transition-colors shadow-sm">
                                <i class="fa-solid fa-handshake"></i> ยืม/คืน
                            </button>
                            <button onclick="deleteEmp(${emp.id})" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded ml-2 transition-colors" title="ลบข้อมูล">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
                empTable = $('#employeeTable').DataTable();
            }
        })
        .catch(err => {
            console.error("Error loading employees:", err);
            let tbody = document.querySelector('#employeeTable tbody');
            tbody.innerHTML = `<tr><td colspan="5" class="text-center text-red-500 py-4">เกิดข้อผิดพลาดในการโหลดข้อมูลพนักงาน</td></tr>`;
        });
}

function openAddEmpModal() {
    document.getElementById('addEmpForm').reset();
    document.getElementById('addEmpModal').classList.remove('hidden');
}
function closeAddEmpModal() { document.getElementById('addEmpModal').classList.add('hidden'); }

function openActionModal(empId, empName) {
    document.getElementById('modal_emp_name').innerText = empName;
    document.getElementById('borrow_emp_id').value = empId;
    document.getElementById('equipment_select').value = '';
    
    // ตั้งค่า DateTime เริ่มต้นเป็นเวลาปัจจุบัน
    let now = new Date();
    // ปรับ timezone แบบง่ายให้เป็น local (ดึง iso string แล้วตัดตัว Z ออก)
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    document.getElementById('borrow_date_input').value = now.toISOString().slice(0, 16);
    
    // โหลดประวัติ
    loadEmployeeItems(empId);
    
    document.getElementById('actionModal').classList.remove('hidden');
}
function closeActionModal() { document.getElementById('actionModal').classList.add('hidden'); }

function loadEmployeeItems(empId) {
    let listContainer = document.getElementById('borrowed_list');
    listContainer.innerHTML = '<div class="text-center py-4 text-gray-500"><i class="fa-solid fa-spinner fa-spin text-primary"></i> กำลังโหลด...</div>';

    fetch(`api/employee_api.php?action=get_borrowed_items&emp_id=${empId}`)
    .then(res => res.json())
    .then(res => {
        if(res.status === 'success') {
            listContainer.innerHTML = '';
            if(res.data.length === 0) {
                listContainer.innerHTML = '<div class="text-center py-4 text-gray-400">ไม่มีรายการยืมที่ค้างอยู่</div>';
            } else {
                res.data.forEach(item => {
                    let dateStr = item.borrow_date ? item.borrow_date.substring(0,16) : '';
                    let html = `
                        <div class="bg-white p-3 rounded border border-gray-200 shadow-sm flex justify-between items-center group">
                            <div>
                                <p class="font-bold text-gray-800 text-sm"><span class="text-xs text-blue-500 font-mono bg-blue-50 px-1 rounded mr-1">${item.barcode}</span> ${item.type} ${item.brand||''}</p>
                                <p class="text-xs text-gray-500 mt-1"><i class="fa-solid fa-location-dot"></i> สถานที่: <span class="text-gray-700 font-medium">${item.location || '-'}</span></p>
                                <p class="text-xs text-gray-400 mt-1"><i class="fa-regular fa-clock"></i> ยืมเมื่อ: ${dateStr}</p>
                            </div>
                            <button onclick="returnByBarcode('${item.barcode}', ${empId})" class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded text-xs transition-colors opacity-80 group-hover:opacity-100 flex items-center gap-1 shadow-sm">
                                <i class="fa-solid fa-rotate-left"></i> คืน
                            </button>
                        </div>
                    `;
                    listContainer.innerHTML += html;
                });
            }
        }
    })
    .catch(err => {
        console.error("Error loading items:", err);
        listContainer.innerHTML = '<div class="text-center py-4 text-red-500">เกิดข้อผิดพลาดในการโหลดข้อมูล (ดูที่ Console)</div>';
    });
}

function returnByBarcode(barcode, empId) {
    Swal.fire({
        title: 'ยืนยันการคืน?',
        html: `ต้องการคืนอุปกรณ์รหัส <b class="text-blue-600">${barcode}</b> ใช่หรือไม่?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'ใช่, คืนอุปกรณ์',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            let fd = new FormData();
            fd.append('action', 'return');
            fd.append('barcode', barcode);

            fetch('api/borrow_api.php', { method: 'POST', body: fd })
            .then(res => res.json())
            .then(res => {
                if(res.status === 'success') {
                    Swal.fire({ title: 'สำเร็จ', text: res.message, icon: 'success', timer: 1500, showConfirmButton: false });
                    loadEmployeeItems(empId); // โหลดรายการฝั่งซ้ายใหม่
                    loadAvailableEquipments(); // อัพเดท select list
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
        }
    });
}

function deleteEmp(id) {
    Swal.fire({
        title: 'ยืนยันการลบ?',
        text: "คุณต้องการลบรายชื่อนี้หรือไม่ (อาจส่งผลกับประวัติการยืม)",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ลบเลย!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            let fd = new FormData();
            fd.append('action', 'delete');
            fd.append('id', id);

            fetch('api/employee_api.php', { method: 'POST', body: fd })
            .then(res => res.json())
            .then(res => {
                if(res.status === 'success') {
                    Swal.fire('ลบแล้ว!', res.message, 'success');
                    loadEmployees();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
        }
    })
}
</script>

<?php require_once 'includes/footer.php'; ?>
