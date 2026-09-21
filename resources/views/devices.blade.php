<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Sembunyikan scrollbar untuk tampilan lebih bersih */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans text-gray-900 p-8">

    <!-- ==================== MAIN VIEW (TABLE) ==================== -->
    <div id="main-view" class="max-w-7xl mx-auto relative animate-in fade-in duration-300 block">
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Device Management</h1>
                <p class="text-gray-500 mt-1 text-base">Manage Device Inventory (Blade Version)</p>
            </div>
            <button onclick="openAddModal()" class="bg-[#6B6565] text-white px-6 py-2 rounded-lg hover:bg-[#5a5454] flex items-center gap-2 shadow-md transition-all active:scale-95 text-sm font-medium">
                <i data-lucide="plus-square" class="w-5 h-5"></i> Add Device
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-blue-200 overflow-hidden outline outline-1 outline-blue-100">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#DBEAFE] text-gray-800 font-bold uppercase text-xs tracking-widest">
                        <th class="p-4 border-b border-blue-200 text-center w-16">No</th>
                        <th class="p-4 border-b border-blue-200 cursor-pointer hover:bg-blue-300" onclick="requestSort('device_id')">Device ID / MAC</th>
                        <th class="p-4 border-b border-blue-200 cursor-pointer hover:bg-blue-300" onclick="requestSort('location')">Location</th>
                        <th class="p-4 border-b border-blue-200 text-center cursor-pointer hover:bg-blue-300" onclick="requestSort('modbus_slave_id')">Modbus ID</th>
                        <th class="p-4 border-b border-blue-200 text-center cursor-pointer hover:bg-blue-300" onclick="requestSort('status')">Status</th>
                        <th class="p-4 border-b border-blue-200 text-center cursor-pointer hover:bg-blue-300" onclick="requestSort('calStatus')">Calibration</th>
                        <th class="p-4 border-b border-blue-200 text-center">Last Cal</th>
                        <th class="p-4 border-b border-blue-200 text-center">Next Due</th>
                        <th class="p-4 border-b border-blue-200 text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="device-table-body">
                    <tr><td colspan="9" class="text-center p-6 text-gray-500 italic">Loading devices...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ==================== HISTORY VIEW ==================== -->
    <div id="history-view" class="max-w-6xl mx-auto relative animate-in slide-in-from-right-4 duration-300 hidden">
        <button onclick="closeHistory()" class="mb-6 flex items-center gap-2 text-gray-500 hover:text-blue-600 font-medium group transition-colors">
            <div class="p-2 bg-white rounded-full shadow-sm group-hover:bg-blue-50 border border-gray-200">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </div>
            <span>Back to Device List</span>
        </button>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
            <div class="mb-6 border-b border-gray-100 pb-4">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Device History</h1>
                <p class="text-gray-500 mt-1 text-lg font-medium">
                    <span id="hist-device-name">Device Name</span> 
                    <span id="hist-device-id" class="text-sm border border-gray-300 px-2 py-1 rounded ml-2 text-gray-600">ID</span>
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-12 text-sm">
                <div class="space-y-3">
                    <div class="flex justify-between border-b border-dashed border-gray-200 pb-2">
                        <span class="text-gray-500 font-medium">First Connected</span>
                        <span id="hist-first-seen" class="text-[#6366F1] font-bold">...</span>
                    </div>
                    <div class="flex justify-between border-b border-dashed border-gray-200 pb-2">
                        <span class="text-gray-500 font-medium">Last Seen</span>
                        <span id="hist-last-seen" class="text-[#16A34A] font-bold">...</span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between border-b border-dashed border-gray-200 pb-2">
                        <span class="text-gray-500 font-medium">Current State</span>
                        <span id="hist-status" class="font-bold uppercase tracking-wide">...</span>
                    </div>
                    <div class="flex justify-between border-b border-dashed border-gray-200 pb-2">
                        <span class="text-gray-500 font-medium">Active Days</span>
                        <span id="hist-active-days" class="text-[#16A34A] font-bold">...</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-blue-200 overflow-hidden outline outline-1 outline-blue-100">
            <table class="w-full text-left border-collapse font-sans">
                <thead>
                    <tr class="bg-[#DBEAFE] text-gray-800 font-bold uppercase text-xs tracking-widest">
                        <th class="p-4 text-center w-16 border-b border-blue-200">No</th>
                        <th class="p-4 border-b border-blue-200 w-48">Date & Time</th>
                        <th class="p-4 border-b border-blue-200 w-48">Event</th>
                        <th class="p-4 border-b border-blue-200">Detail</th>
                    </tr>
                </thead>
                <tbody id="history-table-body">
                    <!-- Data log masuk ke sini -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- ==================== ADD/EDIT MODAL ==================== -->
    <div id="device-modal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
        
        <div class="relative bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-gray-300 font-sans">
            <div class="p-8">
                <h2 id="modal-title" class="text-2xl font-bold text-center mb-6 tracking-tight text-gray-900">Add New Device</h2>
                
                <form id="device-form" class="space-y-4" onsubmit="submitDeviceForm(event)">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Device ID / MAC</label>
                            <input id="input-device-id" type="text" required class="w-full p-3 bg-[#E5E5E5] border-none rounded-xl outline-none focus:ring-2 focus:ring-blue-500/50 text-sm" placeholder="e.g. XY-MD02-03">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Device Name</label>
                            <input id="input-device-name" type="text" required class="w-full p-3 bg-[#E5E5E5] border-none rounded-xl outline-none focus:ring-2 focus:ring-blue-500/50 text-sm" placeholder="e.g. Sensor Main">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Location (Lab)</label>
                            <select id="input-lab-id" required class="w-full p-3 bg-[#E5E5E5] border-none rounded-xl outline-none focus:ring-2 focus:ring-blue-500/50 text-sm cursor-pointer">
                                <option value="" disabled selected>Select Lab...</option>
                            </select>
                            <p id="lab-warning" class="text-red-500 text-xs ml-1 font-bold hidden">Semua lab sudah terisi device.</p>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Modbus Slave ID</label>
                            <input id="input-modbus-id" type="number" min="1" max="254" required class="w-full p-3 bg-[#E5E5E5] border-none rounded-xl outline-none focus:ring-2 focus:ring-blue-500/50 text-sm" placeholder="1-254">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Last Calibration <span class="text-[10px] font-normal text-gray-400">(Optional)</span></label>
                            <input id="input-last-cal" type="date" class="w-full p-3 bg-[#E5E5E5] border-none rounded-xl outline-none focus:ring-2 focus:ring-blue-500/50 text-sm cursor-pointer">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Status</label>
                            <select id="input-status" class="w-full p-3 bg-[#E5E5E5] border-none rounded-xl outline-none focus:ring-2 focus:ring-blue-500/50 text-sm cursor-pointer">
                                <option value="online">Online (Active)</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="offline">Offline (Inactive)</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-6">
                        <button type="button" onclick="closeModal()" class="bg-[#C24444] text-white px-8 py-2.5 rounded-xl font-bold text-sm hover:bg-red-800 transition-all shadow-md active:scale-95">Cancel</button>
                        <button id="btn-submit" type="submit" class="bg-[#3B82F6] text-white px-8 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-800 transition-all shadow-md active:scale-95">Save Device</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ==================== JAVASCRIPT ==================== -->
    <script>
        lucide.createIcons();
        let devicesData = [];
        let sortConfig = { key: null, direction: 'asc' };
        
        let editMode = false;
        let currentEditDevice = null;
        let availableLabs = [];

        // 1. FORMATTERS
        const getStatusColor = (status) => {
            switch (status?.toLowerCase()) { 
                case 'online': return 'bg-[#4ADE80] text-green-900';
                case 'maintenance': return 'bg-[#84CC16] text-white';
                case 'offline': return 'bg-gray-400 text-white';
                default: return 'bg-gray-200 text-gray-800';
            }
        };

        const getCalStatusColor = (status) => {
            switch (status?.toLowerCase()) {
                case 'valid': return 'bg-[#15803D] text-white';
                case 'recalibration_needed': return 'bg-[#EAB308] text-white';
                case 'expired': return 'bg-[#DC2626] text-white';
                default: return 'bg-gray-200 text-gray-800';
            }
        };

        const formatDate = (dateString) => {
            if (!dateString) return "N/A";
            return new Date(dateString).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
        };

        const formatDateTime = (isoString) => {
            if (!isoString) return "N/A";
            const d = new Date(isoString);
            return `${d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })} ${d.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' })}`;
        };

        // 2. FETCH MAIN DATA
        async function fetchDevices() {
            try {
                const response = await fetch('/api/devices');
                devicesData = await response.json();
                renderTable();
            } catch (error) {
                console.error("Failed to fetch devices", error);
                document.getElementById('device-table-body').innerHTML = `<tr><td colspan="9" class="text-center p-5 text-red-500">Failed to load data.</td></tr>`;
            }
        }

        function requestSort(key) {
            let direction = 'asc';
            if (sortConfig.key === key && sortConfig.direction === 'asc') direction = 'desc';
            sortConfig = { key, direction };
            renderTable();
        }

        function renderTable() {
            const tbody = document.getElementById('device-table-body');
            let sorted = [...devicesData].sort((a, b) => {
                if (!sortConfig.key) return 0;
                let aVal = a[sortConfig.key] || ''; let bVal = b[sortConfig.key] || '';
                if (typeof aVal === 'string') { aVal = aVal.toLowerCase(); bVal = bVal.toLowerCase(); }
                if (aVal < bVal) return sortConfig.direction === 'asc' ? -1 : 1;
                if (aVal > bVal) return sortConfig.direction === 'asc' ? 1 : -1;
                return 0;
            });

            if (sorted.length === 0) {
                tbody.innerHTML = `<tr><td colspan="9" class="text-center p-6 text-gray-500">No devices found.</td></tr>`;
                return;
            }

            tbody.innerHTML = sorted.map((device, index) => `
                <tr class="transition-colors duration-200 hover:bg-blue-100/30 ${index % 2 === 0 ? 'bg-white' : 'bg-[#EFF6FF]'}">
                    <td class="p-4 border-b border-blue-50 text-center font-bold text-gray-500 text-xs">${index + 1}</td>
                    <td class="p-4 border-b border-blue-50 font-bold text-gray-800 text-sm">
                        ${device.device_id}<div class="text-xs font-normal text-gray-500 mt-0.5">${device.device_name || '-'}</div>
                    </td>
                    <td class="p-4 border-b border-blue-50 text-gray-600 font-medium text-sm">${device.location || 'Unassigned'}</td>
                    <td class="p-4 border-b border-blue-50 text-center text-gray-800 font-black text-sm">${device.modbus_slave_id || '-'}</td>
                    <td class="p-4 border-b border-blue-50 text-center"><span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase shadow-sm ${getStatusColor(device.status)}">${device.status}</span></td>
                    <td class="p-4 border-b border-blue-50 text-center"><span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase shadow-sm ${getCalStatusColor(device.calStatus)}">${device.calStatus || 'Valid'}</span></td>
                    <td class="p-4 border-b border-blue-50 text-center text-gray-500 italic font-medium text-xs">${formatDate(device.lastCal)}</td>
                    <td class="p-4 border-b border-blue-50 text-center text-gray-500 italic font-medium text-xs">${formatDate(device.nextCal)}</td>
                    <td class="p-4 border-b border-blue-50">
                        <div class="flex justify-center gap-2">
                            <button onclick="deleteDevice('${device.device_id}')" class="p-1.5 bg-[#E11D48] text-white rounded-lg hover:bg-red-700 shadow-sm active:scale-90"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                            <button onclick="editDevice('${device.device_id}')" class="p-1.5 bg-[#3B82F6] text-white rounded-lg hover:bg-blue-700 shadow-sm active:scale-90"><i data-lucide="edit" class="w-4 h-4"></i></button>
                            <button onclick="viewHistory('${device.device_id}')" class="p-1.5 bg-[#8B5CF6] text-white rounded-lg hover:bg-violet-700 shadow-sm active:scale-90"><i data-lucide="history" class="w-4 h-4"></i></button>
                        </div>
                    </td>
                </tr>
            `).join('');
            lucide.createIcons();
        }

        // 3. DELETE LOGIC
        function deleteDevice(deviceId) {
            Swal.fire({
                title: 'Are you sure?', text: `Delete ${deviceId}? Cannot be undone.`, icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#E11D48', confirmButtonText: 'Yes, delete!'
            }).then(async (res) => {
                if (res.isConfirmed) {
                    const response = await fetch(`/api/devices/${deviceId}`, { method: 'DELETE' });
                    if (response.ok) { Swal.fire('Deleted!', '', 'success'); fetchDevices(); }
                }
            });
        }

        // 4. ADD & EDIT MODAL LOGIC
        async function fetchLabsForModal(currentLabId = null) {
            const res = await fetch(`/api/labs/detail`);
            availableLabs = await res.json();
            
            const occupied = devicesData.map(d => d.lab_id).filter(id => id != null);
            const filtered = availableLabs.filter(lab => !occupied.includes(lab.lab_id) || lab.lab_id == currentLabId);
            
            const select = document.getElementById('input-lab-id');
            select.innerHTML = '<option value="" disabled>Select Lab...</option>' + 
                filtered.map(l => `<option value="${l.lab_id}">${l.lab_name}</option>`).join('');
            
            document.getElementById('lab-warning').classList.toggle('hidden', filtered.length > 0);
            if(currentLabId) select.value = currentLabId;
        }

        async function openAddModal() {
            editMode = false;
            currentEditDevice = null;
            document.getElementById('modal-title').innerText = "Add New Device";
            document.getElementById('device-form').reset();
            
            const inputId = document.getElementById('input-device-id');
            inputId.readOnly = false;
            inputId.classList.remove('bg-gray-200', 'text-gray-500');

            await fetchLabsForModal();
            document.getElementById('device-modal').classList.remove('hidden');
        }

        async function editDevice(deviceId) {
            editMode = true;
            currentEditDevice = devicesData.find(d => d.device_id === deviceId);
            if(!currentEditDevice) return;

            document.getElementById('modal-title').innerText = "Edit Device";
            
            const inputId = document.getElementById('input-device-id');
            inputId.value = currentEditDevice.device_id;
            inputId.readOnly = true;
            inputId.classList.add('bg-gray-200', 'text-gray-500'); // Efek disabled

            document.getElementById('input-device-name').value = currentEditDevice.device_name || '';
            document.getElementById('input-modbus-id').value = currentEditDevice.modbus_slave_id || '';
            document.getElementById('input-status').value = currentEditDevice.status || 'online';
            
            if(currentEditDevice.lastCal) {
                document.getElementById('input-last-cal').value = new Date(currentEditDevice.lastCal).toISOString().split('T')[0];
            } else {
                document.getElementById('input-last-cal').value = '';
            }

            await fetchLabsForModal(currentEditDevice.lab_id);
            document.getElementById('device-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('device-modal').classList.add('hidden');
        }

        async function submitDeviceForm(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-submit');
            btn.innerText = "Processing..."; btn.disabled = true;

            const modbusId = parseInt(document.getElementById('input-modbus-id').value);
            const deviceId = document.getElementById('input-device-id').value;

            // Pengecekan Duplikat Modbus
            const isDup = devicesData.some(d => d.modbus_slave_id === modbusId && d.device_id !== deviceId);
            if (isDup) {
                Swal.fire({ icon: 'error', title: 'Modbus ID Bentrok!', text: `ID ${modbusId} sudah dipakai.` });
                btn.innerText = "Save Device"; btn.disabled = false;
                return;
            }

            const payload = {
                deviceId: deviceId,
                deviceName: document.getElementById('input-device-name').value,
                labId: parseInt(document.getElementById('input-lab-id').value) || null,
                modbus_slave_id: modbusId,
                lastCal: document.getElementById('input-last-cal').value || null,
                status: document.getElementById('input-status').value,
                userId: 1, // Static fallback atau ambil dari session Blade
                username: 'Admin'
            };

            const url = editMode ? `/api/devices/${deviceId}` : `/api/devices`;
            const method = editMode ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                
                if (response.ok) {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Data tersimpan.', timer: 1500, showConfirmButton: false });
                    closeModal();
                    fetchDevices();
                } else {
                    const err = await response.json();
                    Swal.fire('Gagal!', err.message || 'Kesalahan server', 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'Koneksi terputus.', 'error');
            }
            btn.innerText = "Save Device"; btn.disabled = false;
        }

        // 5. HISTORY VIEW LOGIC
        async function viewHistory(deviceId) {
            const dev = devicesData.find(d => d.device_id === deviceId);
            if(!dev) return;

            document.getElementById('main-view').classList.add('hidden');
            document.getElementById('history-view').classList.remove('hidden');
            
            document.getElementById('hist-device-name').innerText = dev.device_name || 'Unknown Device';
            document.getElementById('hist-device-id').innerText = dev.device_id;
            document.getElementById('hist-first-seen').innerText = formatDateTime(dev.first_seen) === "N/A" ? "Not Connected" : formatDateTime(dev.first_seen);
            document.getElementById('hist-last-seen').innerText = formatDateTime(dev.last_seen) === "N/A" ? "No Data" : formatDateTime(dev.last_seen);
            
            const isOnline = dev.status === 'online';
            const statusEl = document.getElementById('hist-status');
            statusEl.innerText = dev.status || 'offline';
            statusEl.className = `font-bold uppercase tracking-wide ${isOnline ? 'text-[#16A34A]' : (dev.status === 'maintenance' ? 'text-amber-500' : 'text-[#DC2626]')}`;

            // Hitung Hari
            let activeDays = "Just Added";
            if (dev.first_seen) {
                const diffDays = Math.ceil(Math.abs(new Date() - new Date(dev.first_seen)) / (1000 * 60 * 60 * 24));
                activeDays = `${diffDays} days`;
            }
            document.getElementById('hist-active-days').innerText = activeDays;

            // Fetch History Table
            const tbody = document.getElementById('history-table-body');
            tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-gray-500">Loading logs...</td></tr>`;

            const res = await fetch(`/api/devices/${deviceId}/logs`);
            if (res.ok) {
                const logs = await res.json();
                if(logs.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-gray-500">No history found.</td></tr>`;
                } else {
                    tbody.innerHTML = logs.map((log, i) => `
                        <tr class="border-b border-blue-50 hover:bg-blue-50 transition-colors ${i%2===0?'bg-white':'bg-[#EFF6FF]'}">
                            <td class="p-4 text-center text-gray-500 font-medium">${i+1}</td>
                            <td class="p-4 text-gray-800 font-bold">${formatDateTime(log.date)}</td>
                            <td class="p-4"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold uppercase">${(log.event||'').replace('_',' ')}</span></td>
                            <td class="p-4 text-gray-600 text-sm">${log.detail}</td>
                        </tr>
                    `).join('');
                }
            }
        }

        function closeHistory() {
            document.getElementById('history-view').classList.add('hidden');
            document.getElementById('main-view').classList.remove('hidden');
        }

        fetchDevices();
    </script>
</body>
</html>