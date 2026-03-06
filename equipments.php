<?php require_once 'includes/header.php'; ?>

<div class="mb-4 animate__animated animate__fadeInDown">
    <h1 class="text-3xl font-extrabold text-gray-800">จัดการอุปกรณ์ IT <i class="fa-solid fa-laptop-code flex-primary"></i></h1>
    <p class="text-gray-500 mt-1">เพิ่ม ลบ แก้ไข ข้อมูลสเปค และบาร์โค้ดอุปกรณ์</p>
</div>

<!-- Control Panel (ตรึงด้านบนเวลาเลื่อน) -->
<div class="sticky top-[64px] z-40 bg-white/95 backdrop-blur-md rounded-xl shadow-[0_4px_10px_rgba(0,0,0,0.05)] border border-gray-200 p-4 mb-4 transform transition-all">
    <div class="flex flex-col md:flex-row items-end gap-3">
        <div class="w-full md:w-auto flex-grow flex gap-2 items-end">
            <!-- ช่องค้นหา -->
            <div class="flex-grow">
                <label class="block text-xs font-bold text-gray-700 mb-1">ค้นหาบาร์โค้ด / สเปค</label>
                <input type="text" id="customSearch" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary text-sm p-2 border outline-none" placeholder="ค้นหา...">
            </div>
            <!-- ตัวกรองหมวดหมู่ -->
            <div class="w-1/3 md:w-48">
                <label class="block text-xs font-bold text-gray-700 mb-1">หมวดหมู่</label>
                <select id="categoryFilter" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary text-sm p-2 border outline-none">
                    <option value="">ทั้งหมด (All)</option>
                    <option value="PC">PC</option>
                    <option value="Notebook">Notebook</option>
                    <option value="Monitor">Monitor</option>
                    <option value="Printer">Printer</option>
                    <option value="AIO">AIO (All in One)</option>
                    <option value="Other">อื่นๆ</option>
                </select>
            </div>
        </div>
        <div class="flex gap-2">
            <!-- ปุ่ม Excel -->
            <button id="customExcel" class="shrink-0 bg-gray-100 hover:bg-gray-200 border border-gray-300 text-gray-700 rounded-md shadow-sm transition-all focus:outline-none flex items-center justify-center w-[42px] h-[38px]" title="ส่งออก Excel">
                <i class="fa-solid fa-file-excel text-lg text-green-600"></i>
            </button>
            <!-- ปุ่มเพิ่มอุปกรณ์ -->
            <button onclick="openAddModal()" class="shrink-0 bg-primary hover:bg-secondary text-white font-bold px-3 rounded-md shadow-sm transition-all focus:outline-none flex items-center justify-center gap-1 h-[38px]">
                <i class="fa-solid fa-plus"></i> <span class="text-sm">เพิ่มอุปกรณ์</span>
            </button>
        </div>
    </div>
</div>

<!-- ตารางแสดงอุปกรณ์ (ใช้งาน DataTables แบบ Responsive) -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 relative z-10 animate__animated animate__fadeInUp overflow-hidden">
    <table id="equipmentTable" class="w-full text-left whitespace-nowrap" style="width:100%">
        <thead class="bg-gray-50 text-gray-700">
            <tr>
                <th class="py-2 px-3">บาร์โค้ด</th>
                <th class="py-2 px-3">ประเภท</th>
                <th class="py-2 px-3">ยี่ห้อ</th>
                <th class="py-2 px-3">สเปค</th>
                <th class="py-2 px-3">Serial No.</th>
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
    <div class="flex justify-center min-h-screen pt-10 px-4 pb-24 text-center items-start md:items-center sm:p-0">
        <!-- Background Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeAddModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Modal Panel -->
        <div class="inline-block bg-white rounded-xl text-left shadow-xl transform transition-all my-8 sm:align-middle sm:max-w-2xl w-full animate__animated animate__zoomIn">
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
                                <option value="Printer">Printer (เครื่องพิมพ์)</option>
                                <option value="AIO">AIO (All in One)</option>
                                <option value="Other">อื่นๆ</option>
                            </select>
                        </div>
                        <!-- Sub Type Group -->
                        <div id="subTypeGroup" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">หมวดหมู่ย่อย (Sub Category) <span class="text-red-500">*</span></label>
                            <input type="text" name="sub_type" id="sub_type_input" list="subTypeOptions" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border" placeholder="เช่น เมาส์, คีย์บอร์ด">
                            <datalist id="subTypeOptions">
                                <option value="เมาส์">
                                <option value="คีย์บอร์ด">
                                <option value="ที่รองเมาส์">
                                <option value="สายเคเบิล">
                                <option value="แฟลชไดรฟ์">
                            </datalist>
                        </div>
                        <div id="brandGroup">
                            <label class="block text-sm font-medium text-gray-700 mb-1" id="brandLabel">ยี่ห้อ (Brand)</label>
                            <input type="text" name="brand" list="monitorBrands" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border" placeholder="เช่น DELL, HP, Lenovo">
                        </div>
                        <div id="modelGroup">
                            <label class="block text-sm font-medium text-gray-700 mb-1" id="modelLabel">รุ่น (Model)</label>
                            <input type="text" name="model" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border" placeholder="เช่น OptiPlex 7090">
                        </div>
                        
                        <!-- Serial Group -->
                        <div id="serialNumGroup" class="md:col-span-2 hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Serial No.</label>
                            <input type="text" name="serial_number" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border" placeholder="-">
                        </div>

                        <!-- Location Group -->
                        <div id="locationGroup" class="md:col-span-2 hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">ห้องที่ตั้ง (Location)</label>
                            <input type="text" name="location" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border" placeholder="เช่น ห้องHR, ห้องบัญชี">
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
                            <!-- Storage Dynamic Fields (Add) -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">พื้นที่จัดเก็บข้อมูล (Storage)</label>
                                <div id="storageFieldsContainer" class="space-y-2">
                                    <!-- ตัวแรก (ลบไม่ได้) -->
                                    <div class="flex items-center gap-2 storage-row">
                                        <select name="storage_types[]" class="w-1/3 border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border">
                                            <option value="SSD">SSD</option>
                                            <option value="HDD">HDD</option>
                                            <option value="M.2">M.2</option>
                                        </select>
                                        <input type="number" name="storage_gbs[]" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border" placeholder="ความจุ (GB) เช่น 512, 1000">
                                        <button type="button" class="w-10 h-10 flex-shrink-0 bg-gray-100 text-gray-400 rounded-md cursor-not-allowed cursor-default flex items-center justify-center border border-gray-200" disabled>
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" onclick="addStorageField('storageFieldsContainer')" class="mt-2 text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                                    <i class="fa-solid fa-plus-circle"></i> เพิ่มพื้นที่จัดเก็บ
                                </button>
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

<!-- Modal แก้ไขอุปกรณ์ -->
<div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex justify-center min-h-screen pt-10 px-4 pb-24 text-center items-start md:items-center sm:p-0">
        <!-- Background Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeEditModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Modal Panel -->
        <div class="inline-block bg-white rounded-xl text-left shadow-xl transform transition-all my-8 sm:align-middle sm:max-w-2xl w-full animate__animated animate__zoomIn">
            <form id="editEqForm">
                <input type="hidden" name="id" id="edit_id">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4 border-b pb-2"><i class="fa-solid fa-pen-to-square text-blue-500"></i> แก้ไขข้อมูลและสถานะอุปกรณ์</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">รหัสบาร์โค้ด <span class="text-red-500">*</span></label>
                            <input type="text" name="barcode" id="edit_barcode" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm p-2 border bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ประเภทอุปกรณ์ <span class="text-red-500">*</span></label>
                            <select name="type" id="edit_type" onchange="toggleEditEqFields()" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm p-2 border bg-gray-50 pointer-events-none">
                                <option value="PC">PC (คอมพิวเตอร์ตั้งโต๊ะ)</option>
                                <option value="Notebook">Notebook (แล็ปท็อป)</option>
                                <option value="Monitor">Monitor (หน้าจอ)</option>
                                <option value="Printer">Printer (เครื่องพิมพ์)</option>
                                <option value="AIO">AIO (All in One)</option>
                                <option value="Other">อื่นๆ</option>
                            </select>
                        </div>
                        <!-- Sub Type Group -->
                        <div id="editSubTypeGroup" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">หมวดหมู่ย่อย (Sub Category) <span class="text-red-500">*</span></label>
                            <input type="text" name="sub_type" id="edit_sub_type" list="subTypeOptions" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm p-2 border" placeholder="เช่น เมาส์, คีย์บอร์ด">
                        </div>
                        <div id="editBrandGroup">
                            <label class="block text-sm font-medium text-gray-700 mb-1" id="editBrandLabel">ยี่ห้อ (Brand)</label>
                            <input type="text" name="brand" id="edit_brand" list="monitorBrands" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm p-2 border">
                        </div>
                        <div id="editModelGroup">
                            <label class="block text-sm font-medium text-gray-700 mb-1" id="editModelLabel">รุ่น (Model)</label>
                            <input type="text" name="model" id="edit_model" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm p-2 border">
                        </div>
                        
                        <!-- Serial Group -->
                        <div id="editSerialNumGroup" class="md:col-span-2 hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Serial No.</label>
                            <input type="text" name="serial_number" id="edit_serial_number" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm p-2 border">
                        </div>

                        <!-- Location Group -->
                        <div id="editLocationGroup" class="md:col-span-2 hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">ห้องที่ตั้ง (Location)</label>
                            <input type="text" name="location" id="edit_location" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm p-2 border">
                        </div>

                        <!-- PC Specs Group -->
                        <div id="editPcSpecsGroup" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">CPU (Gen)</label>
                                <input type="text" name="cpu_gen" id="edit_cpu_gen" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm p-2 border">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">RAM (GB)</label>
                                <input type="number" name="ram_gb" id="edit_ram_gb" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm p-2 border">
                            </div>
                            <!-- Storage Dynamic Fields (Edit) -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">พื้นที่จัดเก็บข้อมูล (Storage)</label>
                                <div id="editStorageFieldsContainer" class="space-y-2">
                                    <!-- Rows will be injected by JS -->
                                </div>
                                <button type="button" onclick="addStorageField('editStorageFieldsContainer')" class="mt-2 text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                                    <i class="fa-solid fa-plus-circle"></i> เพิ่มพื้นที่จัดเก็บ
                                </button>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">OS (ระบบปฏิบัติการ)</label>
                                <input type="text" name="os" id="edit_os" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm p-2 border">
                            </div>
                        </div>

                        <!-- Status Selection -->
                        <div class="md:col-span-2 mt-2 pt-2 border-t">
                            <label class="block text-sm font-bold text-gray-900 mb-2">สถานะปัจจุบัน <span class="text-red-500">*</span></label>
                            <select name="status" id="edit_status" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm p-2 border font-medium">
                                <option value="available" class="text-green-600">พร้อมใช้งาน</option>
                                <option value="maintenance" class="text-orange-600">ส่งซ่อม / บำรุงรักษา</option>
                                <option value="broken" class="text-red-600">ชำรุด / พัง</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1" id="edit_status_help"><i class="fa-solid fa-circle-info"></i> เลือกสถานะของอุปกรณ์เพื่อให้ตรงกับความเป็นจริง</p>
                        </div>
                        
                        <!-- Remark Selection -->
                        <div class="md:col-span-2 mt-2 hidden animate__animated animate__fadeIn" id="editRemarkGroup">
                            <label class="block text-sm font-bold text-gray-900 mb-2">หมายเหตุ / อาการเสีย</label>
                            <textarea name="remark" id="edit_remark" rows="2" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm p-2 border" placeholder="เช่น จอฟ้า, ฮาร์ดดิสก์เสีย แจ้งซ่อมวันที่..."></textarea>
                        </div>
                    </div>
                </div>
                <!-- ปุ่มกดยืนยัน -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        <i class="fa-solid fa-save mr-2 mt-1"></i> บันทึกการแก้ไข
                    </button>
                    <button type="button" onclick="closeEditModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        ยกเลิก
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal ประวัติการยืมอุปกรณ์ -->
<div id="eqHistoryModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex justify-center min-h-screen pt-10 px-4 pb-24 text-center items-start md:items-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeEqHistoryModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block bg-white rounded-xl text-left shadow-xl transform transition-all my-8 sm:align-middle sm:max-w-3xl w-full animate__animated animate__zoomIn">
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

<datalist id="monitorBrands"></datalist>

<script>
let dataTable;

document.addEventListener('DOMContentLoaded', () => {
    loadEquipments();
    loadMonitorBrands();

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
                loadMonitorBrands(); // อัพเดทยี่ห้อใหม่ (ถ้ามี)
            } else {
                Swal.fire('ข้อผิดพลาด', res.message, 'error');
            }
        })
        .catch(err => console.error(err));
    });

    // ดักจับการ Submit form ลำหรับแก้ไข
    document.getElementById('editEqForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        let fd = new FormData(this);
        fd.append('action', 'update');

        fetch('api/equipment_api.php', {
            method: 'POST',
            body: fd
        })
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                Swal.fire({
                    title: 'บันทึกสำเร็จ!',
                    text: res.message,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
                closeEditModal();
                loadEquipments(); // โหลดข้อมูลใหม่
                loadMonitorBrands(); // อัพเดทยี่ห้อใหม่ (ถ้ามี)
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
                    let statusBadgeHtml = '';
                    let excelStatus = '';
                    if(item.status === 'available') {
                        statusBadgeHtml = '<span class="w-4 h-4 rounded-full bg-green-500 shadow-sm" title="พร้อมใช้งาน"></span>';
                        excelStatus = 'พร้อมใช้งาน';
                    } else if(item.status === 'borrowed') {
                        let borrowerText = item.borrower_name ? ` (โดย: ${item.borrower_name})` : '';
                        statusBadgeHtml = `<span class="w-4 h-4 rounded-full bg-yellow-400 shadow-sm" title="ถูกยืม${borrowerText}"></span>`;
                        excelStatus = 'ถูกยืม' + borrowerText;
                    } else if(item.status === 'maintenance') {
                        statusBadgeHtml = '<span class="w-4 h-4 rounded-full bg-orange-500 shadow-sm" title="ส่งซ่อม"></span>';
                        excelStatus = 'ส่งซ่อม / บำรุงรักษา';
                    } else if(item.status === 'broken') {
                        statusBadgeHtml = '<span class="w-4 h-4 rounded-full bg-red-500 shadow-sm" title="ชำรุด/พัง"></span>';
                        excelStatus = 'ชำรุด / พัง';
                    } else {
                        statusBadgeHtml = `<span class="w-4 h-4 rounded-full bg-gray-500 shadow-sm" title="${item.status}"></span>`;
                        excelStatus = item.status;
                    }

                    if (item.remark && item.remark.trim() !== '') {
                        let safeRemark = item.remark.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
                        statusBadgeHtml += `<i class="fa-solid fa-note-sticky text-yellow-500 ml-2 cursor-pointer" title="หมายเหตุ: ${safeRemark}"></i>`;
                        excelStatus += ` (หมายเหตุ: ${item.remark.trim()})`;
                    }
                    
                    let statusBadge = `<div class="flex items-center justify-center excel-status-container" data-excel-status="${excelStatus}">${statusBadgeHtml}</div>`;

                    let specs = [];
                    if(item.cpu_gen) specs.push(item.cpu_gen);
                    if(item.ram_gb) specs.push(`RAM ${item.ram_gb}GB`);
                    let storageList = [];
                    if(item.storage_json) {
                        try {
                            let storageArr = JSON.parse(item.storage_json);
                            if (Array.isArray(storageArr)) {
                                storageArr.forEach(s => {
                                    if(s.type && s.gb) storageList.push(`${s.type} ${s.gb}GB`);
                                });
                            }
                        } catch(e) { }
                    }
                    if(storageList.length > 0) specs.push(storageList.join(' + '));
                    if(item.type === 'Printer' && item.location) specs.push(`ที่ตั้ง: ${item.location}`);
                    let sp_str = specs.join(' / ') || '-';
                    
                    // จัดการชื่อยี่ห้อและรุ่น ไม่ให้มีขีด (-) โผล่มาถ้ายี่ห้อว่างเปล่า
                    let brandModelText = [item.brand, item.model].filter(Boolean).join(' ') || '-';

                    let displayType = item.type;
                    if (item.type === 'Other' && item.sub_type) {
                        displayType = `อื่นๆ <span class="text-gray-500 text-xs text-nowrap">(${item.sub_type})</span>`;
                    }

                    let tr = document.createElement('tr');
                    tr.className = 'hover-table-row';
                    
                    // ปุ่มเลือกการแก้ไข 
                    let statusAction = `
                        <button onclick="openEditModal(${item.id})" class="text-blue-500 hover:text-blue-700 p-1.5 rounded transition-colors text-lg" title="แก้ไขข้อมูล/สถานะ">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                    `;
                    
                    if (item.status === 'borrowed') {
                        let borrowerDisp = item.borrower_name ? ` (โดย: ${item.borrower_name})` : '';
                        statusAction = `<span class="text-xs text-gray-400 italic mr-2">ยืมอยู่${borrowerDisp}</span>`;
                    }
                    tr.innerHTML = `
                        <td class="py-2 px-3 font-mono text-blue-600 font-medium cursor-pointer hover:underline" onclick="openEqHistoryModal(${item.id}, '${item.barcode}')" title="คลิกดูประวัติการยืม">
                            ${item.barcode} <i class="fa-solid fa-circle-info text-xs text-blue-300 ml-1"></i>
                        </td>
                        <td class="py-2 px-3">${displayType}</td>
                        <td class="py-2 px-3">${brandModelText}</td>
                        <td class="py-2 px-3 text-xs text-gray-500">${sp_str}</td>
                        <td class="py-2 px-3 text-xs font-mono text-gray-600">${item.serial_number || '-'}</td>
                        <td class="py-2 px-3">${statusBadge}</td>
                        <td class="py-2 px-3 text-center flex items-center justify-center gap-2">
                            ${statusAction}
                            <button onclick="deleteEquipment(${item.id})" class="text-red-500 hover:text-red-700 p-1.5 rounded transition-colors text-lg" title="ลบข้อมูล">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

                // เรียกใช้ DataTables แบบซ่อน Component เริ่มต้นไว้
                dataTable = $('#equipmentTable').DataTable({
                    responsive: true,
                    // B: Buttons (ซ่อน), r: processing, t: table, i: info, p: paging
                    dom: '<"hidden"B>rt<"flex flex-col md:flex-row justify-between items-center mt-4 text-sm"ip>',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            className: 'custom-excel-dt', // ซ่อนปุ่มจริงไว้
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5], // ไม่เอาคอลัมน์ "จัดการ" และบังคับโหลดทุกคอลัมน์ไม่ว่าจะถูกซ่อนในมือถือหรือไม่
                                format: {
                                    body: function(data, row, column, node) {
                                        // สำหรับคอลัมน์ บาร์โค้ด ที่มีไอคอน info, และคอลัมน์สถานะ ให้ตัด HTML ออกให้หมด
                                        if (column === 5) { // Status column
                                            let temp = document.createElement('div');
                                            temp.innerHTML = data;
                                            let container = temp.querySelector('.excel-status-container');
                                            if (container && container.hasAttribute('data-excel-status')) {
                                                return container.getAttribute('data-excel-status');
                                            }
                                            return temp.innerText || temp.getAttribute('title') || temp.querySelector('div')?.getAttribute('title') || 'ไม่ทราบสถานะ';
                                        }
                                        return data.replace(/<[^>]*>?/gm, '').replace(/&nbsp;/g, ' ').trim();
                                    }
                                }
                            }
                        }
                    ]
                });

                // เชื่อมช่องค้นหา Control Panel เข้ากับ DataTables
                $('#customSearch').off('keyup').on('keyup', function() {
                    dataTable.search(this.value).draw();
                });

                // เชื่อมตัวกรองหมวดหมู่ Control Panel เข้ากับ DataTables
                $('#categoryFilter').off('change').on('change', function() {
                    let type = $(this).val();
                    if (type === 'Other') {
                        // ค้นหาคำว่า อื่นๆ เพราะในตารางแสดงเป็น อื่นๆ (เมาส์)
                        dataTable.column(1).search('อื่นๆ', true, false).draw();
                    } else if (type === '') {
                        dataTable.column(1).search('').draw();
                    } else {
                        // ค้นหาคำที่ตรงกันเป๊ะๆ (เช่น PC, Notebook)
                        dataTable.column(1).search('^' + type + '$', true, false).draw();
                    }
                });

                // เชื่อมปุ่ม Excel Control Panel เข้ากับ DataTables
                $('#customExcel').off('click').on('click', function() {
                    $('.custom-excel-dt').click();
                });

                // บังคับให้ตารางคำนวณขนาดและจัดคอลัมน์ใหม่เมื่อย่อ/ขยายหน้าจอ
                $(window).on('resize', function() {
                    if (dataTable) {
                        dataTable.columns.adjust().responsive.recalc();
                    }
                });
            }
        });
}

function loadMonitorBrands() {
    fetch('api/equipment_api.php?action=get_brands&type=Monitor')
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                let datalist = document.getElementById('monitorBrands');
                datalist.innerHTML = '';
                res.data.forEach(brand => {
                    let option = document.createElement('option');
                    option.value = brand;
                    datalist.appendChild(option);
                });
            }
        })
        .catch(err => console.error('Error loading brands:', err));
}

function toggleEqFields() {
    let type = document.getElementById('eqTypeSelect').value;
    let serialGrp = document.getElementById('serialNumGroup');
    let locationGrp = document.getElementById('locationGroup');
    let pcGrp = document.getElementById('pcSpecsGroup');
    let modelGrp = document.getElementById('modelGroup');
    let modelLabel = document.getElementById('modelLabel');
    let brandGrp = document.getElementById('brandGroup');
    let brandLabel = document.getElementById('brandLabel');
    let subTypeGrp = document.getElementById('subTypeGroup');
    let subTypeInput = document.getElementById('sub_type_input');

    // Default: ซ่อนหมวดหมู่ย่อย และยกเลิกบังคับกรอก
    subTypeGrp.classList.add('hidden');
    subTypeInput.removeAttribute('required');

    if (type === 'Monitor') {
        serialGrp.classList.remove('hidden');
        locationGrp.classList.add('hidden');
        pcGrp.classList.add('hidden');
        brandGrp.classList.remove('hidden');
        modelGrp.classList.remove('hidden');
        modelLabel.innerText = 'รุ่น / ขนาดจอ';
        brandLabel.innerText = 'ยี่ห้อ (Brand)';
    } else if (type === 'PC') {
        serialGrp.classList.add('hidden');
        locationGrp.classList.add('hidden');
        pcGrp.classList.remove('hidden');
        brandGrp.classList.add('hidden');
        modelGrp.classList.remove('hidden');
        modelLabel.innerText = 'ชื่อประจำอุปกรณ์ (Computer Name)';
    } else if (type === 'Notebook') {
        serialGrp.classList.remove('hidden');
        locationGrp.classList.add('hidden');
        pcGrp.classList.remove('hidden');
        brandGrp.classList.remove('hidden');
        modelGrp.classList.remove('hidden');
        modelLabel.innerText = 'รุ่น (Model)';
        brandLabel.innerText = 'ยี่ห้อ (Brand)';
    } else if (type === 'Printer') {
        serialGrp.classList.remove('hidden');
        locationGrp.classList.remove('hidden');
        pcGrp.classList.add('hidden');
        brandGrp.classList.remove('hidden');
        modelGrp.classList.add('hidden');
        brandLabel.innerText = 'ยี่ห้อ (Brand)';
    } else if (type === 'Other') {
        serialGrp.classList.remove('hidden');
        locationGrp.classList.add('hidden');
        pcGrp.classList.add('hidden');
        brandGrp.classList.remove('hidden');
        modelGrp.classList.add('hidden');
        subTypeGrp.classList.remove('hidden');
        subTypeInput.setAttribute('required', 'required');
        brandLabel.innerText = 'ยี่ห้อ (Brand)';
    } else {
        // AIO
        serialGrp.classList.add('hidden');
        locationGrp.classList.add('hidden');
        pcGrp.classList.remove('hidden');
        brandGrp.classList.remove('hidden');
        modelGrp.classList.remove('hidden');
        modelLabel.innerText = 'รุ่น (Model)';
        brandLabel.innerText = 'ยี่ห้อ (Brand)';
    }
}

function toggleEditEqFields() {
    let type = document.getElementById('edit_type').value;
    let serialGrp = document.getElementById('editSerialNumGroup');
    let locationGrp = document.getElementById('editLocationGroup');
    let pcGrp = document.getElementById('editPcSpecsGroup');
    let modelGrp = document.getElementById('editModelGroup');
    let modelLabel = document.getElementById('editModelLabel');
    let brandGrp = document.getElementById('editBrandGroup');
    let brandLabel = document.getElementById('editBrandLabel');
    let subTypeGrp = document.getElementById('editSubTypeGroup');
    let subTypeInput = document.getElementById('edit_sub_type');

    // Default: ซ่อนหมวดหมู่ย่อย และยกเลิกบังคับกรอก
    subTypeGrp.classList.add('hidden');
    subTypeInput.removeAttribute('required');

    if (type === 'Monitor') {
        serialGrp.classList.remove('hidden');
        locationGrp.classList.add('hidden');
        pcGrp.classList.add('hidden');
        brandGrp.classList.remove('hidden');
        modelGrp.classList.remove('hidden');
        modelLabel.innerText = 'รุ่น / ขนาดจอ';
        brandLabel.innerText = 'ยี่ห้อ (Brand)';
    } else if (type === 'PC') {
        serialGrp.classList.add('hidden');
        locationGrp.classList.add('hidden');
        pcGrp.classList.remove('hidden');
        brandGrp.classList.add('hidden');
        modelGrp.classList.remove('hidden');
        modelLabel.innerText = 'ชื่อประจำอุปกรณ์ (Computer Name)';
    } else if (type === 'Notebook') {
        serialGrp.classList.remove('hidden');
        locationGrp.classList.add('hidden');
        pcGrp.classList.remove('hidden');
        brandGrp.classList.remove('hidden');
        modelGrp.classList.remove('hidden');
        modelLabel.innerText = 'รุ่น (Model)';
        brandLabel.innerText = 'ยี่ห้อ (Brand)';
    } else if (type === 'Printer') {
        serialGrp.classList.remove('hidden');
        locationGrp.classList.remove('hidden');
        pcGrp.classList.add('hidden');
        brandGrp.classList.remove('hidden');
        modelGrp.classList.add('hidden');
        brandLabel.innerText = 'ยี่ห้อ (Brand)';
    } else if (type === 'Other') {
        serialGrp.classList.remove('hidden');
        locationGrp.classList.add('hidden');
        pcGrp.classList.add('hidden');
        brandGrp.classList.remove('hidden');
        modelGrp.classList.add('hidden');
        subTypeGrp.classList.remove('hidden');
        subTypeInput.setAttribute('required', 'required');
        brandLabel.innerText = 'ยี่ห้อ (Brand)';
    } else {
        // AIO
        serialGrp.classList.add('hidden');
        locationGrp.classList.add('hidden');
        pcGrp.classList.remove('hidden');
        brandGrp.classList.remove('hidden');
        modelGrp.classList.remove('hidden');
        modelLabel.innerText = 'รุ่น (Model)';
        brandLabel.innerText = 'ยี่ห้อ (Brand)';
    }
}

function addStorageField(containerId, data = null) {
    let container = document.getElementById(containerId);
    if (!container) return;

    let typeVal = data ? data.type : 'SSD';
    let gbVal = data ? data.gb : '';

    let isFirst = container.children.length === 0;

    let row = document.createElement('div');
    row.className = 'flex items-center gap-2 storage-row animate__animated animate__fadeIn relative';
    
    // แบบลบไม่ได้ (อันแรก) vs ลบได้
    let btnHtml = isFirst 
        ? `<button type="button" class="w-10 h-10 flex-shrink-0 bg-gray-100 text-gray-400 rounded-md cursor-not-allowed flex items-center justify-center border border-gray-200" disabled>
                <i class="fa-solid fa-trash"></i>
           </button>`
        : `<button type="button" onclick="removeStorageField(this)" class="w-10 h-10 flex-shrink-0 bg-white hover:bg-red-50 text-red-500 rounded-md cursor-pointer flex items-center justify-center border border-gray-300 transition-colors title="ลบ" shadow-sm">
                <i class="fa-solid fa-trash"></i>
           </button>`;

    row.innerHTML = `
        <select name="storage_types[]" class="w-1/3 border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border">
            <option value="SSD" ${typeVal === 'SSD' ? 'selected' : ''}>SSD</option>
            <option value="HDD" ${typeVal === 'HDD' ? 'selected' : ''}>HDD</option>
            <option value="M.2" ${typeVal === 'M.2' ? 'selected' : ''}>M.2</option>
        </select>
        <input type="number" name="storage_gbs[]" value="${gbVal}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm p-2 border" placeholder="ความจุ (GB) เช่น 512, 1000">
        ${btnHtml}
    `;

    container.appendChild(row);
}

function removeStorageField(btn) {
    let row = btn.closest('.storage-row');
    if (row) {
        row.remove();
    }
}

function openAddModal() {
    document.getElementById('addEqForm').reset();
    
    // Reset Storage Fields
    let storageContainer = document.getElementById('storageFieldsContainer');
    storageContainer.innerHTML = '';
    addStorageField('storageFieldsContainer'); // เพิ่มบรรทัดแรก

    document.getElementById('addModal').classList.remove('hidden');
    toggleEqFields();
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
}

function openEditModal(id) {
    if (!id) return;

    fetch(`api/equipment_api.php?action=get_single&id=${id}`)
    .then(res => res.json())
    .then(res => {
        if (res.status === 'success') {
            let item = res.data;

            document.getElementById('edit_id').value = item.id;
            document.getElementById('edit_barcode').value = item.barcode;
            document.getElementById('edit_type').value = item.type;
            document.getElementById('edit_brand').value = item.brand || '';
            document.getElementById('edit_model').value = item.model || '';
            document.getElementById('edit_serial_number').value = item.serial_number || '';
            document.getElementById('edit_location').value = item.location || '';
            document.getElementById('edit_cpu_gen').value = item.cpu_gen || '';
            document.getElementById('edit_ram_gb').value = item.ram_gb || '';
            document.getElementById('edit_os').value = item.os || '';
            
            // Sub Type field (if any)
            document.getElementById('edit_sub_type').value = item.sub_type || '';
            
            // Build Edit Storage Rows
            let editStorageContainer = document.getElementById('editStorageFieldsContainer');
            editStorageContainer.innerHTML = '';
            let hasStorage = false;
            if (item.storage_json) {
                try {
                    let storageArr = JSON.parse(item.storage_json);
                    if (Array.isArray(storageArr)) {
                        storageArr.forEach(s => {
                            addStorageField('editStorageFieldsContainer', s);
                            hasStorage = true;
                        });
                    }
                } catch(e) {}
            }
            if (!hasStorage) {
                // Default 1 empty row
                addStorageField('editStorageFieldsContainer');
            }
            
            // Set Status
            let statusEl = document.getElementById('edit_status');
            statusEl.value = item.status || 'available';

            // Set Remark
            document.getElementById('edit_remark').value = item.remark || '';

            // Handle Remark visibility
            function toggleRemarkField() {
                let currentStatus = document.getElementById('edit_status').value;
                let remarkGroup = document.getElementById('editRemarkGroup');
                if (currentStatus === 'maintenance' || currentStatus === 'broken' || document.getElementById('edit_remark').value.trim() !== '') {
                    remarkGroup.classList.remove('hidden');
                } else {
                    remarkGroup.classList.add('hidden');
                }
            }
            toggleRemarkField();
            document.getElementById('edit_status').onchange = toggleRemarkField;

            // ถ้าเครื่องถูกยืมอยู่ ห้ามเปลี่ยนสถานะผ่านหน้านี้ ป้องกันบัค
            if (item.status === 'borrowed') {
                statusEl.innerHTML = '<option value="borrowed" selected class="text-yellow-600">กำลังถูกยืม</option>';
                statusEl.classList.add('bg-gray-100', 'pointer-events-none');
                document.getElementById('edit_status_help').innerText = '*อุปกรณ์ชิ้นนี้กำลังถูกยืม ไม่สามารถเปลี่ยนสถานะได้ในขณะนี้ แต่อัปเดตสเปคได้';
                document.getElementById('edit_status_help').classList.add('text-orange-500');
            } else {
                statusEl.innerHTML = `
                    <option value="available" class="text-green-600">พร้อมใช้งาน</option>
                    <option value="maintenance" class="text-orange-600">ส่งซ่อม / บำรุงรักษา</option>
                    <option value="broken" class="text-red-600">ชำรุด / พัง</option>
                `;
                statusEl.value = item.status;
                statusEl.classList.remove('bg-gray-100', 'pointer-events-none');
                document.getElementById('edit_status_help').innerHTML = '<i class="fa-solid fa-circle-info"></i> เลือกสถานะของอุปกรณ์เพื่อให้ตรงกับความเป็นจริง';
                document.getElementById('edit_status_help').classList.remove('text-orange-500');
            }

            // จัดการ UI
            toggleEditEqFields();
            document.getElementById('editModal').classList.remove('hidden');

        } else {
            Swal.fire('ข้อผิดพลาด', res.message, 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('ข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
    });
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
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
