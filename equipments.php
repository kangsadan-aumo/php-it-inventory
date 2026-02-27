<?php require_once 'includes/header.php'; ?>

<div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4 animate__animated animate__fadeInDown">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-800">จัดการอุปกรณ์ IT <i class="fa-solid fa-laptop-code text-primary"></i></h1>
        <p class="text-gray-500 mt-1">เพิ่ม ลบ แก้ไข ข้อมูลสเปค และบาร์โค้ดอุปกรณ์</p>
    </div>
    <button onclick="openAddModal()" class="bg-primary hover:bg-secondary text-white font-bold py-2 px-4 rounded-lg shadow-md transition-all flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> เพิ่มอุปกรณ์ใหม่
    </button>
</div>

<!-- ตารางแสดงอุปกรณ์ (ใช้งาน DataTables แบบ Responsive) -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 animate__animated animate__fadeInUp">
    <table id="equipmentTable" class="w-full text-left" style="width:100%">
        <thead class="bg-gray-50 text-gray-700">
            <tr>
                <th class="py-2 px-3">บาร์โค้ด</th>
                <th class="py-2 px-3">ประเภท</th>
                <th class="py-2 px-3">ยี่ห้อ/รุ่น</th>
                <th class="py-2 px-3">สเปคหลัก (CPU/RAM/Storage)</th>
                <th class="py-2 px-3">หมายเลขซีเรียลหน้าจอ</th>
                <th class="py-2 px-3">สถานะ</th>
                <th class="py-2 px-3 text-center">จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded via AJAX/JS -->
        </tbody>
    </table>
</div>

<!-- Modal เพิ่มอุปกรณ์ -->
<div id="addModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeAddModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Modal Panel -->
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full animate__animated animate__zoomIn">
            <form id="addEqForm">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4 border-b pb-2"><i class="fa-solid fa-desktop text-primary"></i> ข้อมูลอุปกรณ์ใหม่</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">รหัสบาร์โค้ด <span class="text-red-500">*</span></label>
                            <input type="text" name="barcode" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border">
                            <p class="text-xs text-gray-400 mt-1">ใช้เครื่องสแกนยิงใส่ช่องนี้ได้เลย</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ประเภทอุปกรณ์ <span class="text-red-500">*</span></label>
                            <select name="type" id="eqTypeSelect" onchange="toggleEqFields()" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border">
                                <option value="PC">PC (คอมพิวเตอร์ตั้งโต๊ะ)</option>
                                <option value="Notebook">Notebook (แล็ปท็อป)</option>
                                <option value="Monitor">Monitor (หน้าจอ)</option>
                                <option value="Tablet">Tablet</option>
                                <option value="Other">อื่นๆ</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" id="brandLabel">ยี่ห้อ (Brand)</label>
                            <input type="text" name="brand" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border" placeholder="เช่น DELL, HP, Lenovo">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" id="modelLabel">รุ่น (Model)</label>
                            <input type="text" name="model" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border" placeholder="เช่น OptiPlex 7090">
                        </div>
                        
                        <!-- Serial Group -->
                        <div id="serialNumGroup" class="md:col-span-2 hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">หมายเลขซีเรียล (Serial Number)</label>
                            <input type="text" name="serial_number" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border" placeholder="เพื่อระบุหน้าจอแยกกัน">
                        </div>

                        <!-- PC Specs Group -->
                        <div id="pcSpecsGroup" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">CPU (Gen)</label>
                                <input type="text" name="cpu_gen" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border" placeholder="เช่น i5 Gen 12, Ryzen 5">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">RAM (GB)</label>
                                <input type="number" name="ram_gb" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border" placeholder="เช่น 8, 16">
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Storage Type</label>
                                    <select name="storage_type" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border">
                                        <option value="SSD">SSD</option>
                                        <option value="M.2">M.2</option>
                                        <option value="HDD">HDD</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">ความจุ (GB)</label>
                                    <input type="number" name="storage_gb" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border" placeholder="256, 512">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">OS (ระบบปฏิบัติการ)</label>
                                <input type="text" name="os" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border" placeholder="เช่น Windows 11 Pro">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ปุ่มกดยืนยัน -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-secondary focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        <i class="fa-solid fa-save mr-2 mt-1"></i> บันทึกข้อมูล
                    </button>
                    <button type="button" onclick="closeAddModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        ยกเลิก
                    </button>
                </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal ประวัติการยืมอุปกรณ์ -->
<div id="eqHistoryModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeEqHistoryModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full animate__animated animate__zoomIn">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="text-xl leading-6 font-bold text-gray-900 border-l-4 border-primary pl-2"><i class="fa-solid fa-clock-rotate-left text-gray-400"></i> ประวัติการยืม: <span id="modal_eq_barcode" class="text-primary"></span></h3>
                    <button onclick="closeEqHistoryModal()" class="text-gray-400 hover:text-red-500"><i class="fa-solid fa-times text-xl"></i></button>
                </div>
                
                <div class="bg-gray-50 rounded p-4 max-h-96 overflow-y-auto">
                    <table class="w-full text-left text-sm" id="eqHistoryTable">
                        <thead class="bg-gray-200 text-gray-700">
                            <tr>
                                <th class="py-2 px-3">วัน/เวลายืม</th>
                                <th class="py-2 px-3">วัน/เวลาคืน</th>
                                <th class="py-2 px-3">ผู้ยืม</th>
                                <th class="py-2 px-3">สถานที่</th>
                                <th class="py-2 px-3">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody id="eqHistoryBody">
                            <tr><td colspan="5" class="text-center py-4 text-gray-500">กำลังโหลด...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t">
                <button type="button" onclick="closeEqHistoryModal()" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:w-auto sm:text-sm transition-colors">
                    ปิดหน้าต่าง
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let dataTable;

document.addEventListener('DOMContentLoaded', () => {
    loadEquipments();

    // ดักจับการ Submit form
    document.getElementById('addEqForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        let fd = new FormData(this);
        fd.append('action', 'add');

        fetch('api/equipment_api.php', {
            method: 'POST',
            body: fd
        })
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                Swal.fire({
                    title: 'สำเร็จ!',
                    text: res.message,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
                closeAddModal();
                loadEquipments(); // โหลดข้อมูลใหม่
            } else {
                Swal.fire('ข้อผิดพลาด', res.message, 'error');
            }
        })
        .catch(err => console.error(err));
    });
});

function loadEquipments() {
    fetch('api/equipment_api.php?action=get_all')
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                if(dataTable) {
                    dataTable.destroy();
                }

                let tbody = document.querySelector('#equipmentTable tbody');
                tbody.innerHTML = '';
                
                res.data.forEach(item => {
                    // จัดการสีของสถานะ (Badge เป็นวงกลมสี)
                    let statusBadge = '';
                    if(item.status === 'available') {
                        statusBadge = '<div class="flex items-center justify-center" title="พร้อมใช้งาน"><span class="w-4 h-4 rounded-full bg-green-500 shadow-sm"></span></div>';
                    } else if(item.status === 'borrowed') {
                        statusBadge = '<div class="flex items-center justify-center" title="ถูกยืม"><span class="w-4 h-4 rounded-full bg-yellow-400 shadow-sm"></span></div>';
                    } else if(item.status === 'maintenance') {
                        statusBadge = '<div class="flex items-center justify-center" title="ส่งซ่อม"><span class="w-4 h-4 rounded-full bg-orange-500 shadow-sm"></span></div>';
                    } else if(item.status === 'broken') {
                        statusBadge = '<div class="flex items-center justify-center" title="ชำรุด/พัง"><span class="w-4 h-4 rounded-full bg-red-500 shadow-sm"></span></div>';
                    } else {
                        statusBadge = `<div class="flex items-center justify-center" title="${item.status}"><span class="w-4 h-4 rounded-full bg-gray-500 shadow-sm"></span></div>`;
                    }

                    // แปลงสเปคเครื่องเป็นรายละเอียดรวมกัน
                    let specs = [];
                    if(item.cpu_gen) specs.push(item.cpu_gen);
                    if(item.ram_gb) specs.push(`RAM ${item.ram_gb}GB`);
                    if(item.storage_type && item.storage_gb) specs.push(`${item.storage_type} ${item.storage_gb}GB`);
                    let sp_str = specs.join(' / ') || '-';

                    let tr = document.createElement('tr');
                    tr.className = 'hover-table-row';
                    
                    // ปุ่มเลือกสถานะ (เฉพาะตอนว่าง หรือ เสีย/ซ่อม)
                    let statusAction = '';
                    if(item.status !== 'borrowed') {
                        statusAction = `
                            <select onchange="updateEqStatus(${item.id}, this.value)" class="text-xs border rounded p-1 text-gray-600 bg-gray-50 hover:bg-white transition-colors">
                                <option value="" disabled selected>เปลี่ยนสถานะ</option>
                                <option value="available" ${item.status==='available'?'disabled':''}>พร้อมใช้งาน</option>
                                <option value="maintenance" ${item.status==='maintenance'?'disabled':''}>ส่งซ่อม</option>
                                <option value="broken" ${item.status==='broken'?'disabled':''}>ชำรุด/พัง</option>
                            </select>
                        `;
                    } else {
                        statusAction = `<span class="text-xs text-gray-400 italic">ยืมอยู่</span>`;
                    }
                    tr.innerHTML = `
                        <td class="py-2 px-3 font-mono text-blue-600 font-medium cursor-pointer hover:underline" onclick="openEqHistoryModal(${item.id}, '${item.barcode}')" title="คลิกดูประวัติการยืม">
                            ${item.barcode} <i class="fa-solid fa-circle-info text-xs text-blue-300 ml-1"></i>
                        </td>
                        <td class="py-2 px-3">${item.type}</td>
                        <td class="py-2 px-3">${item.brand || '-'} ${item.model || ''}</td>
                        <td class="py-2 px-3 text-xs text-gray-500">${sp_str}</td>
                        <td class="py-2 px-3 text-xs font-mono text-gray-600">${item.serial_number || '-'}</td>
                        <td class="py-2 px-3">${statusBadge}</td>
                        <td class="py-2 px-3 text-center flex items-center justify-center gap-2">
                            ${statusAction}
                            <button onclick="deleteEquipment(${item.id})" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1.5 rounded transition-colors" title="ลบข้อมูล">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

                // เรียกใช้ DataTables พร้อม Excel Export
                dataTable = $('#equipmentTable').DataTable({
                    // ใช้ default ที่ตั้งไว้ใน footer (ภาษาไทย + ปุ่ม Print/Excel)
                });
            }
        });
}

function toggleEqFields() {
    let type = document.getElementById('eqTypeSelect').value;
    let serialGrp = document.getElementById('serialNumGroup');
    let pcGrp = document.getElementById('pcSpecsGroup');
    let modelLabel = document.getElementById('modelLabel');
    let brandLabel = document.getElementById('brandLabel');

    if(type === 'Monitor') {
        serialGrp.classList.remove('hidden');
        pcGrp.classList.add('hidden');
        modelLabel.innerText = 'รุ่น / ขนาดจอ';
        brandLabel.innerText = 'ยี่ห้อ (Brand)';
    } else if (type === 'PC') {
        serialGrp.classList.add('hidden');
        pcGrp.classList.remove('hidden');
        modelLabel.innerText = 'หมายเลขประจำอุปกรณ์ (เช่น DESKTOP-XXXX)';
        brandLabel.innerText = 'ชื่อเครื่อง (Computer Name)';
    } else {
        // Notebook, Tablet, Other
        serialGrp.classList.add('hidden');
        pcGrp.classList.remove('hidden');
        modelLabel.innerText = 'รุ่น (Model)';
        brandLabel.innerText = 'ยี่ห้อ (Brand)';
    }
}

function openAddModal() {
    document.getElementById('addEqForm').reset();
    document.getElementById('addModal').classList.remove('hidden');
    toggleEqFields();
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
}

function deleteEquipment(id) {
    Swal.fire({
        title: 'ยืนยันการลบ?',
        text: "คุณต้องการลบอุปกรณ์นี้หรือไม่! ไม่สามารถกู้คืนได้",
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

            fetch('api/equipment_api.php', {
                method: 'POST',
                body: fd
            }).then(res => res.json())
            .then(res => {
                if(res.status === 'success') {
                    Swal.fire('ลบแล้ว!', res.message, 'success');
                    loadEquipments();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
        }
    })
}
function updateEqStatus(id, newStatus) {
    if(!newStatus) return;
    
    let statusName = '';
    if(newStatus === 'available') statusName = 'พร้อมใช้งาน';
    if(newStatus === 'maintenance') statusName = 'ส่งซ่อม';
    if(newStatus === 'broken') statusName = 'ชำรุด/พัง';

    Swal.fire({
        title: 'ยืนยันเปลี่ยนสถานะ?',
        text: `ต้องการเปลี่ยนเป็น "${statusName}" ใช่หรือไม่`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่, เปลี่ยนเลย!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            let fd = new FormData();
            fd.append('action', 'update_status');
            fd.append('id', id);
            fd.append('status', newStatus);

            fetch('api/equipment_api.php', { method: 'POST', body: fd })
            .then(res => res.json())
            .then(res => {
                if(res.status === 'success') {
                    Swal.fire('สำเร็จ', res.message, 'success');
                    loadEquipments();
                } else {
                    Swal.fire('Error', res.message, 'error');
                    loadEquipments(); // โหลดใหม่เพื่อรีเซ็ต select box กลับค่าเดิมกรณี error
                }
            });
        } else {
            loadEquipments(); // โหลดใหม่ถ้ายกเลิก เพื่อให้ select box กลับไปหน้าตาเดิม
        }
    });
}
function closeEqHistoryModal() {
    document.getElementById('eqHistoryModal').classList.add('hidden');
}

function openEqHistoryModal(eqId, barcode) {
    document.getElementById('modal_eq_barcode').innerText = barcode;
    document.getElementById('eqHistoryModal').classList.remove('hidden');
    let tbody = document.getElementById('eqHistoryBody');
    tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-gray-500"><i class="fa-solid fa-spinner fa-spin text-primary"></i> กำลังโหลด...</td></tr>';

    fetch(`api/borrow_api.php?action=get_equipment_history&id=${eqId}`)
    .then(res => res.json())
    .then(res => {
        if(res.status === 'success') {
            tbody.innerHTML = '';
            if(res.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-gray-400">ยังไม่มีประวัติการยืม</td></tr>';
            } else {
                res.data.forEach(item => {
                    let bDate = item.borrow_date ? item.borrow_date.substring(0, 16) : '-';
                    let rDate = item.return_date ? item.return_date.substring(0, 16) : '-';
                    let statusHtml = item.status === 'active' 
                        ? '<span class="text-yellow-600 font-semibold text-xs bg-yellow-100 px-2 py-1 rounded-full">ยืมอยู่</span>' 
                        : '<span class="text-green-600 font-semibold text-xs bg-green-100 px-2 py-1 rounded-full">คืนแล้ว</span>';
                    
                    tbody.innerHTML += `
                        <tr class="border-t hover:bg-white transition-colors">
                            <td class="py-2 px-3">${bDate}</td>
                            <td class="py-2 px-3 ${!item.return_date ? 'text-gray-400' : ''}">${rDate}</td>
                            <td class="py-2 px-3 font-medium text-blue-600">${item.emp_name} <span class="text-xs text-gray-400">(${item.emp_code})</span></td>
                            <td class="py-2 px-3">${item.location || '-'}</td>
                            <td class="py-2 px-3">${statusHtml}</td>
                        </tr>
                    `;
                });
            }
        } else {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-red-500">Error: ${res.message}</td></tr>`;
        }
    })
    .catch(err => {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-red-500">ไม่สามารถโหลดข้อมูลได้</td></tr>`;
    });
}
</script>

<?php require_once 'includes/footer.php'; ?>
