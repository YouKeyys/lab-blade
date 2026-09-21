<div id="main-device-view" class="max-w-7xl mx-auto relative animate-in fade-in duration-300 block">
    
    <!-- ==================== MAIN DEVICE LIST ==================== -->
    <div id="dev-main-view">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Device Management</h1>
                <p class="text-gray-500 mt-1 text-base">Manage Device Inventory</p>
            </div>
            <button id="dev_btn_add" onclick="dev_openModal()" class="bg-[#6B6565] text-white px-6 py-2.5 rounded-lg hover:bg-[#5a5454] flex items-center gap-2 shadow-md transition-all active:scale-95 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#6B6565]">
                <i data-lucide="plus-square" class="w-5 h-5" aria-hidden="true"></i> Add Device
            </button>
        </div>

        <!-- Toolbar: Search + Bulk Actions + Info -->
        <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3 mb-4">
            <div class="relative flex-1 max-w-md">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" aria-hidden="true"></i>
                <input type="search" id="dev_search" oninput="dev_handleSearch(this.value)" placeholder="Search by ID, name, location, or modbus..." class="w-full pl-10 pr-10 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all" aria-label="Search devices">
                <button id="dev_clear_search" onclick="dev_clearSearch()" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" aria-label="Clear search">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="flex items-center gap-2">
                <div id="dev_bulk_actions" class="hidden items-center gap-2">
                    <span id="dev_selected_count" class="text-sm text-gray-600 font-medium whitespace-nowrap"></span>
                    <button id="dev_btn_bulk_delete" onclick="dev_bulkDelete()" class="bg-red-50 text-red-600 border border-red-200 px-3 py-2 rounded-lg text-xs font-semibold hover:bg-red-100 transition-all flex items-center gap-1.5 focus:outline-none focus:ring-2 focus:ring-red-300">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5" aria-hidden="true"></i>
                        Delete Selected
                    </button>
                </div>
                <div class="text-sm text-gray-500 whitespace-nowrap" id="dev_showing_info">0 devices</div>
            </div>
        </div>

        <!-- Desktop Table -->
        <div class="bg-white rounded-xl shadow-lg border border-blue-200 overflow-hidden outline outline-1 outline-blue-100 hidden md:block">
            <table class="w-full text-left border-collapse" role="grid" aria-label="Devices table">
                <thead>
                    <tr class="bg-[#DBEAFE] text-gray-800 font-bold uppercase text-xs tracking-widest" id="dev_table_header">
                        <!-- Header will be rendered by JavaScript -->
                    </tr>
                </thead>
                <tbody id="dev_table_body">
                    <tr><td colspan="10" class="text-center p-6 text-gray-500 italic">Loading devices...</td></tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div id="dev_mobile_cards" class="md:hidden bg-white rounded-xl shadow-lg border border-blue-200 overflow-hidden outline outline-1 outline-blue-100 divide-y divide-blue-100"></div>

        <!-- Pagination -->
        <div id="dev_pagination" class="hidden flex items-center justify-between px-6 py-3 bg-gray-50 border border-blue-100 rounded-b-xl mt-[-1px]">
            <div class="text-sm text-gray-500" id="dev_page_info"></div>
            <div class="flex items-center gap-1">
                <button onclick="dev_changePage(-1)" id="dev_prev_btn" class="p-2 rounded-lg hover:bg-blue-100 disabled:opacity-40 disabled:cursor-not-allowed transition-colors" aria-label="Previous page"><i data-lucide="chevron-left" class="w-4 h-4"></i></button>
                <div id="dev_page_numbers" class="flex items-center gap-1"></div>
                <button onclick="dev_changePage(1)" id="dev_next_btn" class="p-2 rounded-lg hover:bg-blue-100 disabled:opacity-40 disabled:cursor-not-allowed transition-colors" aria-label="Next page"><i data-lucide="chevron-right" class="w-4 h-4"></i></button>
            </div>
        </div>
    </div>

    <!-- ==================== DEVICE HISTORY VIEW ==================== -->
    <div id="dev-history-view" class="max-w-6xl mx-auto relative animate-in slide-in-from-right-4 duration-300 hidden">
        <button onclick="dev_closeHistory()" class="mb-6 flex items-center gap-2 text-gray-500 hover:text-blue-600 font-medium group transition-colors focus:outline-none">
            <div class="p-2 bg-white rounded-full shadow-sm group-hover:bg-blue-50 border border-gray-200 transition-colors"><i data-lucide="arrow-left" class="w-5 h-5"></i></div>
            <span>Back to Device List</span>
        </button>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
            <div class="mb-6 border-b border-gray-100 pb-4">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Device History</h1>
                <p class="text-gray-500 mt-1 text-lg font-medium">
                    <span id="dev_hist_device_name">Device Name</span>
                    <span id="dev_hist_device_id" class="text-sm border border-gray-300 px-2 py-1 rounded ml-2 text-gray-600 bg-gray-50">ID</span>
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-12 text-sm">
                <div class="space-y-3">
                    <div class="flex justify-between border-b border-dashed border-gray-200 pb-2">
                        <span class="text-gray-500 font-medium">First Connected</span>
                        <span id="dev_hist_first_seen" class="text-[#6366F1] font-bold">...</span>
                    </div>
                    <div class="flex justify-between border-b border-dashed border-gray-200 pb-2">
                        <span class="text-gray-500 font-medium">Last Seen</span>
                        <span id="dev_hist_last_seen" class="text-[#16A34A] font-bold">...</span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between border-b border-dashed border-gray-200 pb-2">
                        <span class="text-gray-500 font-medium">Current State</span>
                        <span id="dev_hist_status" class="font-bold uppercase tracking-wide">...</span>
                    </div>
                    <div class="flex justify-between border-b border-dashed border-gray-200 pb-2">
                        <span class="text-gray-500 font-medium">Active Days</span>
                        <span id="dev_hist_active_days" class="text-[#16A34A] font-bold">...</span>
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
                <tbody id="dev_history_table_body">
                    <tr><td colspan="4" class="p-6 text-center text-gray-500">Loading logs...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ==================== ADD / EDIT MODAL ==================== -->
    <div id="dev_modal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true" aria-labelledby="dev_modal_title">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="dev_closeModal()"></div>
        <div class="relative bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-gray-300 font-sans transform transition-all" role="document">
            <button onclick="dev_closeModal()" class="absolute top-4 right-4 p-2 rounded-full hover:bg-gray-100 transition-colors text-gray-400 hover:text-gray-600 focus:outline-none" aria-label="Close modal">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <div class="p-8">
                <h2 id="dev_modal_title" class="text-2xl font-bold text-center mb-6 tracking-tight text-gray-900">Add New Device</h2>
                <form id="dev_form" class="space-y-4" onsubmit="dev_submitForm(event)" novalidate>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="dev_input_id" class="block text-sm font-bold text-gray-700 ml-1">Device ID <span class="text-red-500">*</span></label>
                            <input id="dev_input_id" type="text" required class="w-full p-3 bg-[#E5E5E5] border-none rounded-xl outline-none focus:ring-2 focus:ring-blue-500 text-sm" aria-required="true">
                            <p id="dev_id_error" class="hidden text-xs text-red-500 ml-1"></p>
                        </div>
                        <div class="space-y-1.5">
                            <label for="dev_input_name" class="block text-sm font-bold text-gray-700 ml-1">Device Name <span class="text-red-500">*</span></label>
                            <input id="dev_input_name" type="text" required class="w-full p-3 bg-[#E5E5E5] border-none rounded-xl outline-none focus:ring-2 focus:ring-blue-500 text-sm" aria-required="true">
                            <p id="dev_name_error" class="hidden text-xs text-red-500 ml-1"></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="dev_input_lab" class="block text-sm font-bold text-gray-700 ml-1">Location <span class="text-red-500">*</span></label>
                            <select id="dev_input_lab" required class="w-full p-3 bg-[#E5E5E5] border-none rounded-xl outline-none focus:ring-2 focus:ring-blue-500 text-sm cursor-pointer" aria-required="true"></select>
                            <p id="dev_lab_warning" class="text-red-500 text-xs ml-1 font-bold hidden">All labs are currently occupied.</p>
                            <p id="dev_lab_error" class="hidden text-xs text-red-500 ml-1"></p>
                        </div>
                        <div class="space-y-1.5">
                            <label for="dev_input_modbus" class="block text-sm font-bold text-gray-700 ml-1">Modbus ID <span class="text-red-500">*</span></label>
                            <input id="dev_input_modbus" type="number" min="1" max="254" required class="w-full p-3 bg-[#E5E5E5] border-none rounded-xl outline-none focus:ring-2 focus:ring-blue-500 text-sm" aria-required="true">
                            <p id="dev_modbus_error" class="hidden text-xs text-red-500 ml-1"></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="dev_input_last_cal" class="block text-sm font-bold text-gray-700 ml-1">Last Calibration <span class="text-[10px] font-normal text-gray-400">(Optional)</span></label>
                            <input id="dev_input_last_cal" type="date" class="w-full p-3 bg-[#E5E5E5] border-none rounded-xl outline-none focus:ring-2 focus:ring-blue-500 text-sm cursor-pointer">
                        </div>
                        <div class="space-y-1.5">
                            <label for="dev_input_status" class="block text-sm font-bold text-gray-700 ml-1">Status</label>
                            <select id="dev_input_status" class="w-full p-3 bg-[#E5E5E5] border-none rounded-xl outline-none focus:ring-2 focus:ring-blue-500 text-sm cursor-pointer">
                                <option value="online">Online</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="offline">Offline</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-between items-center pt-6">
                        <button type="button" onclick="dev_closeModal()" class="bg-[#C24444] text-white px-8 py-2.5 rounded-xl font-bold text-sm hover:bg-red-800 transition-all shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Cancel</button>
                        <button id="dev_btn_submit" type="submit" class="bg-[#3B82F6] text-white px-8 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-800 transition-all shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-60 disabled:cursor-not-allowed">Save Device</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="dev_toast_container" class="fixed top-4 right-4 z-[10000] flex flex-col gap-2 pointer-events-none" aria-live="polite"></div>

<style>
    .dev_sort_icon::after { content: '↕'; font-size: 10px; opacity: 0.4; }
    .dev_sort_icon.asc::after { content: '↑'; opacity: 1; color: #1e40af; }
    .dev_sort_icon.desc::after { content: '↓'; opacity: 1; color: #1e40af; }
    .dev-skeleton { background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: dev-shimmer 1.5s infinite; border-radius: 4px; }
    @keyframes dev-shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
    .dev-toast { animation: dev-slide-in 0.3s ease-out forwards; pointer-events: auto; }
    .dev-toast.removing { animation: dev-slide-out 0.3s ease-in forwards; }
    @keyframes dev-slide-in { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @keyframes dev-slide-out { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
</style>

<script>
    let dev_data = [];
    let dev_sort = { key: null, direction: 'asc' };
    let dev_editMode = false;
    let dev_currentEditDevice = null;
    let dev_availableLabs = [];
    let dev_userRole = localStorage.getItem('userRole') || 'supervisor';
    let dev_userId = localStorage.getItem('userId');
    
    let dev_searchQuery = '';
    let dev_currentPage = 1;
    let dev_perPage = 10;
    let dev_selectedDevices = new Set();
    let dev_isLoading = false;
    let dev_previousFocus = null;

    function dev_escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    function dev_showToast(message, type = 'success') {
        const container = document.getElementById('dev_toast_container');
        const toast = document.createElement('div');
        const colors = { success: 'bg-green-50 border-green-300 text-green-800', error: 'bg-red-50 border-red-300 text-red-800', info: 'bg-blue-50 border-blue-300 text-blue-800' };
        const icons = { success: 'check-circle', error: 'alert-circle', info: 'info' };
        toast.className = `dev-toast flex items-center gap-3 px-4 py-3 rounded-lg border shadow-lg ${colors[type]} text-sm font-medium min-w-[280px]`;
        toast.innerHTML = `<i data-lucide="${icons[type]}" class="w-5 h-5 flex-shrink-0"></i><span class="flex-1">${dev_escapeHtml(message)}</span><button onclick="this.parentElement.classList.add('removing'); setTimeout(() => this.parentElement.remove(), 300)" class="flex-shrink-0 hover:opacity-70" aria-label="Dismiss"><i data-lucide="x" class="w-4 h-4"></i></button>`;
        container.appendChild(toast);
        if (typeof lucide !== 'undefined') lucide.createIcons();
        setTimeout(() => { if (toast.parentElement) { toast.classList.add('removing'); setTimeout(() => toast.remove(), 300); } }, 4000);
    }

    function dev_renderTableHeader() {
        const headerRow = document.getElementById('dev_table_header');
        const isAdmin = dev_userRole === 'admin';
        
        let html = '';
        
        if (isAdmin) {
            html += `<th id="dev_select_header" class="p-4 border-b border-blue-200 text-center w-12">
                <input type="checkbox" id="dev_select_all" onchange="dev_toggleSelectAll(this.checked)" 
                    class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer" 
                    aria-label="Select all devices">
            </th>`;
        }
        
        html += `
            <th class="p-4 border-b border-blue-200 text-center w-12">No</th>
            <th class="p-4 border-b border-blue-200 cursor-pointer hover:bg-blue-300/50 transition-colors select-none" onclick="dev_requestSort('device_id')">
                <span class="inline-flex items-center gap-1.5">Device ID / MAC <span class="dev_sort_icon" data-key="device_id"></span></span>
            </th>
            <th class="p-4 border-b border-blue-200 cursor-pointer hover:bg-blue-300/50 transition-colors select-none" onclick="dev_requestSort('location')">
                <span class="inline-flex items-center gap-1.5">Location <span class="dev_sort_icon" data-key="location"></span></span>
            </th>
            <th class="p-4 border-b border-blue-200 text-center cursor-pointer hover:bg-blue-300/50 transition-colors select-none" onclick="dev_requestSort('modbus_slave_id')">
                <span class="inline-flex items-center gap-1.5">Modbus ID <span class="dev_sort_icon" data-key="modbus_slave_id"></span></span>
            </th>
            <th class="p-4 border-b border-blue-200 text-center cursor-pointer hover:bg-blue-300/50 transition-colors select-none" onclick="dev_requestSort('status')">
                <span class="inline-flex items-center gap-1.5">Status <span class="dev_sort_icon" data-key="status"></span></span>
            </th>
            <th class="p-4 border-b border-blue-200 text-center cursor-pointer hover:bg-blue-300/50 transition-colors select-none" onclick="dev_requestSort('calStatus')">
                <span class="inline-flex items-center gap-1.5">Calibration <span class="dev_sort_icon" data-key="calStatus"></span></span>
            </th>
            <th class="p-4 border-b border-blue-200 text-center cursor-pointer hover:bg-blue-300/50 transition-colors select-none" onclick="dev_requestSort('lastCal')">Last Cal</th>
            <th class="p-4 border-b border-blue-200 text-center cursor-pointer hover:bg-blue-300/50 transition-colors select-none" onclick="dev_requestSort('nextCal')">Next Due</th>
            <th class="p-4 border-b border-blue-200 text-center">Action</th>
        `;
        
        headerRow.innerHTML = html;
    }

    async function dev_fetchDevices() {
        dev_isLoading = true;
        dev_renderSkeleton();
        try {
            const res = await fetch('/api/devices');
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            let data = await res.json();
            
            if (dev_userRole === 'supervisor') {
                const addButton = document.getElementById('dev_btn_add');
                if (addButton) addButton.style.display = 'none';
                
                const userRes = await fetch('/api/users');
                const users = await userRes.json();
                const me = users.find(u => u.user_id == dev_userId);
                const assigned = me ? (me.assignedLabs || []) : [];
                data = data.filter(d => assigned.includes(d.location));
            }
            
            dev_data = data;
            dev_currentPage = 1;
            dev_selectedDevices.clear();
            dev_renderTable();
        } catch (error) {
            console.error("Device fetch error:", error);
            dev_renderErrorState();
        } finally {
            dev_isLoading = false;
        }
    }

    function dev_renderSkeleton() {
        const tbody = document.getElementById('dev_table_body');
        const mobile = document.getElementById('dev_mobile_cards');
        const isAdmin = dev_userRole === 'admin';
        
        dev_renderTableHeader();
        
        let rows = '';
        for (let i = 0; i < 5; i++) {
            rows += `<tr class="bg-white">
                ${isAdmin ? `<td class="p-4 border-b border-blue-50 text-center"><div class="w-4 h-4 mx-auto dev-skeleton"></div></td>` : ''}
                <td class="p-4 border-b border-blue-50 text-center"><div class="w-6 h-4 mx-auto dev-skeleton"></div></td>
                <td class="p-4 border-b border-blue-50"><div class="space-y-1.5"><div class="w-28 h-3 dev-skeleton"></div><div class="w-40 h-2.5 dev-skeleton"></div></div></td>
                <td class="p-4 border-b border-blue-50"><div class="w-20 h-3 dev-skeleton"></div></td>
                <td class="p-4 border-b border-blue-50 text-center"><div class="w-8 h-4 mx-auto dev-skeleton"></div></td>
                <td class="p-4 border-b border-blue-50 text-center"><div class="w-16 h-5 rounded-full mx-auto dev-skeleton"></div></td>
                <td class="p-4 border-b border-blue-50 text-center"><div class="w-16 h-5 rounded-full mx-auto dev-skeleton"></div></td>
                <td class="p-4 border-b border-blue-50 text-center"><div class="w-20 h-3 mx-auto dev-skeleton"></div></td>
                <td class="p-4 border-b border-blue-50 text-center"><div class="w-20 h-3 mx-auto dev-skeleton"></div></td>
                <td class="p-4 border-b border-blue-50 text-center"><div class="flex justify-center gap-1"><div class="w-7 h-7 rounded-lg dev-skeleton"></div><div class="w-7 h-7 rounded-lg dev-skeleton"></div><div class="w-7 h-7 rounded-lg dev-skeleton"></div></div></td>
            </tr>`;
        }
        tbody.innerHTML = rows;
        mobile.innerHTML = Array(3).fill(`<div class="p-4 space-y-3"><div class="flex items-center gap-3"><div class="w-10 h-10 rounded dev-skeleton"></div><div class="flex-1 space-y-1.5"><div class="w-32 h-3 dev-skeleton"></div><div class="w-48 h-2.5 dev-skeleton"></div></div></div><div class="flex gap-2"><div class="w-16 h-5 rounded-full dev-skeleton"></div><div class="w-24 h-5 rounded dev-skeleton"></div></div></div>`).join('');
    }

    function dev_renderErrorState() {
        const isAdmin = dev_userRole === 'admin';
        const colSpan = isAdmin ? 10 : 9;
        
        dev_renderTableHeader();
        
        const html = `<div class="flex flex-col items-center justify-center py-12 px-4 text-center"><div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mb-4"><i data-lucide="alert-triangle" class="w-8 h-8 text-red-400"></i></div><p class="text-gray-700 font-semibold mb-1">Failed to load devices</p><p class="text-gray-500 text-sm mb-4">Please check your connection and try again.</p><button onclick="dev_fetchDevices()" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors flex items-center gap-2"><i data-lucide="refresh-cw" class="w-4 h-4"></i> Retry</button></div>`;
        document.getElementById('dev_table_body').innerHTML = `<tr><td colspan="${colSpan}">${html}</td></tr>`;
        document.getElementById('dev_mobile_cards').innerHTML = html;
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function dev_getFilteredData() {
        if (!dev_searchQuery) return [...dev_data];
        const q = dev_searchQuery.toLowerCase();
        return dev_data.filter(d => 
            (d.device_id || '').toLowerCase().includes(q) ||
            (d.device_name || '').toLowerCase().includes(q) ||
            (d.location || '').toLowerCase().includes(q) ||
            String(d.modbus_slave_id || '').includes(q)
        );
    }

    function dev_getPaginatedData(data) {
        const start = (dev_currentPage - 1) * dev_perPage;
        return data.slice(start, start + dev_perPage);
    }

    function dev_getTotalPages(data) { return Math.max(1, Math.ceil(data.length / dev_perPage)); }

    function dev_handleSearch(value) {
        dev_searchQuery = value.trim();
        dev_currentPage = 1;
        dev_selectedDevices.clear();
        const selectAll = document.getElementById('dev_select_all');
        if (selectAll) selectAll.checked = false;
        document.getElementById('dev_clear_search').classList.toggle('hidden', !value);
        dev_renderTable();
    }

    function dev_clearSearch() {
        document.getElementById('dev_search').value = '';
        dev_handleSearch('');
    }

    function dev_getStatusColor(status) {
        switch (status?.toLowerCase()) {
            case 'online': return 'bg-[#4ADE80] text-green-900';
            case 'maintenance': return 'bg-[#84CC16] text-white';
            case 'offline': return 'bg-gray-400 text-white';
            default: return 'bg-gray-200 text-gray-800';
        }
    }

    function dev_getCalStatusColor(status) {
        switch (status?.toLowerCase()) {
            case 'valid': return 'bg-[#15803D] text-white';
            case 'recalibration_needed': return 'bg-[#EAB308] text-white';
            case 'expired': return 'bg-[#DC2626] text-white';
            default: return 'bg-gray-200 text-gray-800';
        }
    }

    function dev_formatDate(dateString) {
        if (!dateString) return "N/A";
        return new Date(dateString).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
    }

    function dev_formatDateTime(isoString) {
        if (!isoString) return "N/A";
        const d = new Date(isoString);
        return `${d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })} ${d.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' })}`;
    }

    function dev_renderTable() {
        const tbody = document.getElementById('dev_table_body');
        const mobile = document.getElementById('dev_mobile_cards');
        
        dev_renderTableHeader();
        
        let filtered = dev_getFilteredData();
        
        if (dev_sort.key) {
            filtered.sort((a, b) => {
                let aVal = (a[dev_sort.key] || '').toString().toLowerCase();
                let bVal = (b[dev_sort.key] || '').toString().toLowerCase();
                if (aVal < bVal) return dev_sort.direction === 'asc' ? -1 : 1;
                if (aVal > bVal) return dev_sort.direction === 'asc' ? 1 : -1;
                return 0;
            });
        }

        document.getElementById('dev_showing_info').textContent = `${filtered.length} device${filtered.length !== 1 ? 's' : ''}${dev_searchQuery ? ' found' : ''}`;

        if (filtered.length === 0) {
            const isAdmin = dev_userRole === 'admin';
            const colSpan = isAdmin ? 10 : 9;
            
            const emptyHTML = `<div class="flex flex-col items-center justify-center py-12 px-4 text-center"><div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-4"><i data-lucide="monitor" class="w-8 h-8 text-blue-300"></i></div><p class="text-gray-700 font-semibold mb-1">${dev_searchQuery ? 'No devices match your search' : 'No devices yet'}</p><p class="text-gray-500 text-sm mb-4">${dev_searchQuery ? 'Try adjusting your search terms.' : 'Get started by adding your first device.'}</p></div>`;
            tbody.innerHTML = `<tr><td colspan="${colSpan}">${emptyHTML}</td></tr>`;
            mobile.innerHTML = emptyHTML;
            document.getElementById('dev_pagination').classList.add('hidden');
            if (typeof lucide !== 'undefined') lucide.createIcons();
            dev_updateSortIcons();
            dev_updateBulkUI();
            return;
        }

        const paginatedData = dev_getPaginatedData(filtered);
        const totalPages = dev_getTotalPages(filtered);
        const startIdx = (dev_currentPage - 1) * dev_perPage;
        const isAdmin = dev_userRole === 'admin';
        const canEdit = dev_userRole === 'admin' || dev_userRole === 'supervisor';
        const canDelete = dev_userRole === 'admin';

        tbody.innerHTML = paginatedData.map((d, i) => {
            const globalIdx = startIdx + i;
            const isSelected = dev_selectedDevices.has(d.device_id);
            const safeId = dev_escapeHtml(d.device_id);
            const safeName = dev_escapeHtml(d.device_name || '-');
            const safeLoc = dev_escapeHtml(d.location || 'Unassigned');
            const safeModbus = dev_escapeHtml(d.modbus_slave_id || '-');
            const safeStatus = dev_escapeHtml(d.status || '-');
            const safeCal = dev_escapeHtml(d.calStatus || 'Valid');

            return `<tr class="${isSelected ? 'bg-blue-50' : (i % 2 === 0 ? 'bg-white' : 'bg-[#EFF6FF]')} hover:bg-blue-100/40 transition-colors group">
                ${isAdmin ? `
                <td class="p-4 border-b border-blue-50 text-center">
                    <input type="checkbox" ${isSelected ? 'checked' : ''} onchange="dev_toggleSelect('${d.device_id}', this.checked)" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer" aria-label="Select ${safeId}">
                </td>
                ` : ''}
                <td class="p-4 border-b border-blue-50 text-center text-xs font-bold text-gray-500">${globalIdx + 1}</td>
                <td class="p-4 border-b border-blue-50 font-bold text-gray-800 text-sm">${safeId}<div class="text-xs text-gray-500 font-normal">${safeName}</div></td>
                <td class="p-4 border-b border-blue-50 text-sm font-medium text-gray-600">${safeLoc}</td>
                <td class="p-4 border-b border-blue-50 text-sm font-black text-center text-gray-800">${safeModbus}</td>
                <td class="p-4 border-b border-blue-50 text-center"><span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase shadow-sm ${dev_getStatusColor(d.status)}">${safeStatus}</span></td>
                <td class="p-4 border-b border-blue-50 text-center"><span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase shadow-sm ${dev_getCalStatusColor(d.calStatus)}">${safeCal}</span></td>
                <td class="p-4 border-b border-blue-50 text-center text-gray-500 italic font-medium text-xs">${dev_formatDate(d.lastCal)}</td>
                <td class="p-4 border-b border-blue-50 text-center text-gray-500 italic font-medium text-xs">${dev_formatDate(d.nextCal)}</td>
                <td class="p-4 border-b border-blue-50">
                    <div class="flex justify-center gap-1.5 opacity-70 group-hover:opacity-100 transition-opacity">
                        ${canEdit ? `
                        <button onclick="dev_edit('${d.device_id}')" class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Edit Device">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                        </button>
                        ` : ''}
                        ${canDelete ? `
                        <button onclick="dev_delete('${d.device_id}')" class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Delete Device">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                        ` : ''}
                        <button onclick="dev_viewHistory('${d.device_id}')" class="p-1.5 bg-[#8B5CF6] text-white rounded-lg hover:bg-violet-700 shadow-sm transition-all hover:scale-105 focus:outline-none focus:ring-2 focus:ring-violet-300" title="View Device History" aria-label="History for ${safeId}"><i data-lucide="history" class="w-3.5 h-3.5"></i></button>
                    </div>
                </td>
            </tr>`;
        }).join('');

        mobile.innerHTML = paginatedData.map((d, i) => {
            const isSelected = dev_selectedDevices.has(d.device_id);
            const safeId = dev_escapeHtml(d.device_id);
            const safeName = dev_escapeHtml(d.device_name || '-');
            const safeLoc = dev_escapeHtml(d.location || 'Unassigned');
            return `<div class="p-4 ${isSelected ? 'bg-blue-50' : ''}">
                <div class="flex items-start gap-3">
                    ${isAdmin ? `
                    <input type="checkbox" ${isSelected ? 'checked' : ''} onchange="dev_toggleSelect('${d.device_id}', this.checked)" class="w-4 h-4 mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer" aria-label="Select ${safeId}">
                    ` : ''}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <div class="min-w-0"><div class="font-bold text-gray-800 text-sm truncate">${safeId}</div><div class="text-xs text-gray-500 truncate">${safeName}</div></div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase flex-shrink-0 ${dev_getStatusColor(d.status)}">${dev_escapeHtml(d.status || '-')}</span>
                        </div>
                        <div class="mt-2 text-xs text-gray-600">Loc: ${safeLoc} | Modbus: ${dev_escapeHtml(d.modbus_slave_id || '-')}</div>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded ${dev_getCalStatusColor(d.calStatus)}">${dev_escapeHtml(d.calStatus || 'Valid')}</span>
                            <div class="flex gap-1.5">
                                ${canEdit ? `
                                <button onclick="dev_edit('${d.device_id}')" class="flex-1 px-3 py-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors text-sm font-medium">
                                    <i data-lucide="pencil" class="w-4 h-4 inline-block mr-1"></i>
                                    Edit
                                </button>
                                ` : ''}
                                ${canDelete ? `
                                <button onclick="dev_delete('${d.device_id}')" class="flex-1 px-3 py-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors text-sm font-medium">
                                    <i data-lucide="trash-2" class="w-4 h-4 inline-block mr-1"></i>
                                    Delete
                                </button>
                                ` : ''}
                                <button onclick="dev_viewHistory('${d.device_id}')" class="p-1.5 bg-violet-100 text-violet-600 rounded-lg hover:bg-violet-200" aria-label="History"><i data-lucide="history" class="w-3.5 h-3.5"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
        }).join('');

        if (typeof lucide !== 'undefined') lucide.createIcons();
        dev_updateSortIcons();
        dev_renderPagination(filtered.length, totalPages);
        dev_updateBulkUI();
    }

    function dev_updateSortIcons() {
        document.querySelectorAll('.dev_sort_icon').forEach(el => {
            const key = el.dataset.key;
            el.className = 'dev_sort_icon';
            if (dev_sort.key === key) el.classList.add(dev_sort.direction);
        });
    }

    function dev_requestSort(key) {
        dev_sort.direction = (dev_sort.key === key && dev_sort.direction === 'asc') ? 'desc' : 'asc';
        dev_sort.key = key;
        dev_renderTable();
    }

    function dev_renderPagination(total, totalPages) {
        const paginationEl = document.getElementById('dev_pagination');
        if (totalPages <= 1) { paginationEl.classList.add('hidden'); return; }
        paginationEl.classList.remove('hidden');
        paginationEl.classList.add('flex');
        const start = (dev_currentPage - 1) * dev_perPage + 1;
        const end = Math.min(dev_currentPage * dev_perPage, total);
        document.getElementById('dev_page_info').textContent = `Showing ${start}-${end} of ${total}`;
        document.getElementById('dev_prev_btn').disabled = dev_currentPage <= 1;
        document.getElementById('dev_next_btn').disabled = dev_currentPage >= totalPages;
        
        const container = document.getElementById('dev_page_numbers');
        let pages = [];
        const delta = 1;
        const left = Math.max(2, dev_currentPage - delta);
        const right = Math.min(totalPages - 1, dev_currentPage + delta);
        pages.push(1);
        if (left > 2) pages.push('...');
        for (let i = left; i <= right; i++) pages.push(i);
        if (right < totalPages - 1) pages.push('...');
        if (totalPages > 1) pages.push(totalPages);
        
        container.innerHTML = pages.map(p => {
            if (p === '...') return `<span class="px-2 text-gray-400 text-sm">…</span>`;
            const isActive = p === dev_currentPage;
            return `<button onclick="dev_goToPage(${p})" class="w-8 h-8 rounded-lg text-sm font-medium transition-colors ${isActive ? 'bg-blue-500 text-white shadow-sm' : 'hover:bg-blue-100 text-gray-600'}" ${isActive ? 'aria-current="page"' : ''}>${p}</button>`;
        }).join('');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function dev_changePage(delta) {
        const filtered = dev_getFilteredData();
        const totalPages = dev_getTotalPages(filtered);
        const newPage = dev_currentPage + delta;
        if (newPage >= 1 && newPage <= totalPages) {
            dev_currentPage = newPage;
            dev_renderTable();
            document.querySelector('#main-device-view')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function dev_goToPage(page) { dev_currentPage = page; dev_renderTable(); }

    function dev_toggleSelect(id, checked) {
        if (checked) dev_selectedDevices.add(id);
        else dev_selectedDevices.delete(id);
        dev_updateBulkUI();
        dev_renderTable();
    }

    function dev_toggleSelectAll(checked) {
        const filtered = dev_getFilteredData();
        const paginated = dev_getPaginatedData(filtered);
        paginated.forEach(d => {
            if (checked) dev_selectedDevices.add(d.device_id);
            else dev_selectedDevices.delete(d.device_id);
        });
        dev_updateBulkUI();
        dev_renderTable();
    }

    function dev_updateBulkUI() {
        const bulkEl = document.getElementById('dev_bulk_actions');
        const countEl = document.getElementById('dev_selected_count');
        if (dev_selectedDevices.size > 0) {
            bulkEl.classList.remove('hidden');
            bulkEl.classList.add('flex');
            countEl.textContent = `${dev_selectedDevices.size} selected`;
        } else {
            bulkEl.classList.add('hidden');
            bulkEl.classList.remove('flex');
        }
    }

    async function dev_bulkDelete() {
        const count = dev_selectedDevices.size;
        if (count === 0 || dev_userRole !== 'admin') return;
        const result = await Swal.fire({ title: `Delete ${count} device${count > 1 ? 's' : ''}?`, text: 'This action cannot be undone.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#E11D48', confirmButtonText: 'Yes, delete all' });
        if (!result.isConfirmed) return;
        try {
            const ids = Array.from(dev_selectedDevices);
            await Promise.all(ids.map(id => fetch(`/api/devices/${id}`, { method: 'DELETE' })));
            dev_selectedDevices.clear();
            dev_fetchDevices();
            dev_showToast(`${count} device${count > 1 ? 's' : ''} deleted successfully`, 'success');
        } catch (error) {
            dev_showToast('Failed to delete some devices', 'error');
        }
    }

    async function dev_fetchLabsForModal(currentLabId = null) {
        try {
            const res = await fetch('/api/labs/detail');
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            dev_availableLabs = await res.json();
            const occupied = dev_data.map(d => d.lab_id).filter(id => id != null);
            const filtered = dev_availableLabs.filter(lab => !occupied.includes(lab.lab_id) || lab.lab_id == currentLabId);
            const select = document.getElementById('dev_input_lab');
            select.innerHTML = '<option value="">Select Lab...</option>' + filtered.map(lab => `<option value="${lab.lab_id}">${dev_escapeHtml(lab.lab_name)}</option>`).join('');
            const warning = document.getElementById('dev_lab_warning');
            if (warning) warning.classList.toggle('hidden', filtered.length > 0);
            if (currentLabId) select.value = currentLabId;
        } catch (error) {
            console.error("Failed to fetch labs:", error);
            dev_showToast('Failed to load available labs', 'error');
        }
    }

    async function dev_openModal() {
        dev_editMode = false;
        dev_currentEditDevice = null;
        document.getElementById('dev_form').reset();
        document.getElementById('dev_input_id').readOnly = false;
        document.getElementById('dev_input_id').classList.remove('bg-gray-200', 'text-gray-500');
        document.getElementById('dev_modal_title').innerText = "Add New Device";
        document.querySelectorAll('[id$="_error"]').forEach(el => el.classList.add('hidden'));
        await dev_fetchLabsForModal();
        dev_previousFocus = document.activeElement;
        document.getElementById('dev_modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('dev_input_id').focus(), 100);
    }

    async function dev_edit(id) {
        dev_editMode = true;
        dev_currentEditDevice = dev_data.find(x => x.device_id === id);
        if (!dev_currentEditDevice) return;
        const d = dev_currentEditDevice;
        document.getElementById('dev_input_id').value = d.device_id;
        document.getElementById('dev_input_id').readOnly = true;
        document.getElementById('dev_input_id').classList.add('bg-gray-200', 'text-gray-500');
        document.getElementById('dev_input_name').value = d.device_name || '';
        document.getElementById('dev_input_modbus').value = d.modbus_slave_id || '';
        document.getElementById('dev_input_status').value = d.status || 'online';
        document.getElementById('dev_modal_title').innerText = "Edit Device";
        document.getElementById('dev_input_last_cal').value = d.lastCal ? new Date(d.lastCal).toISOString().split('T')[0] : '';
        document.querySelectorAll('[id$="_error"]').forEach(el => el.classList.add('hidden'));
        await dev_fetchLabsForModal(d.lab_id);
        dev_previousFocus = document.activeElement;
        document.getElementById('dev_modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('dev_input_name').focus(), 100);
    }

    function dev_closeModal() {
        document.getElementById('dev_modal').classList.add('hidden');
        document.body.style.overflow = '';
        if (dev_previousFocus) { dev_previousFocus.focus(); dev_previousFocus = null; }
    }

    document.addEventListener('keydown', (e) => {
        const modal = document.getElementById('dev_modal');
        if (!modal.classList.contains('hidden')) {
            if (e.key === 'Escape') { dev_closeModal(); return; }
            if (e.key === 'Tab') {
                const focusable = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                const first = focusable[0], last = focusable[focusable.length - 1];
                if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
                else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
            }
        }
    });

    function dev_validateForm() {
        let isValid = true;
        const id = document.getElementById('dev_input_id').value.trim();
        const name = document.getElementById('dev_input_name').value.trim();
        const lab = document.getElementById('dev_input_lab').value;
        const modbus = document.getElementById('dev_input_modbus').value;

        const idError = document.getElementById('dev_id_error');
        if (!id) { idError.textContent = 'Device ID is required'; idError.classList.remove('hidden'); isValid = false; } 
        else { idError.classList.add('hidden'); }

        const nameError = document.getElementById('dev_name_error');
        if (!name) { nameError.textContent = 'Device Name is required'; nameError.classList.remove('hidden'); isValid = false; } 
        else { nameError.classList.add('hidden'); }

        const labError = document.getElementById('dev_lab_error');
        if (!lab) { labError.textContent = 'Location is required'; labError.classList.remove('hidden'); isValid = false; } 
        else { labError.classList.add('hidden'); }

        const modbusError = document.getElementById('dev_modbus_error');
        if (!modbus || modbus < 1 || modbus > 254) { modbusError.textContent = 'Modbus ID must be between 1 and 254'; modbusError.classList.remove('hidden'); isValid = false; } 
        else { modbusError.classList.add('hidden'); }

        return isValid;
    }

    async function dev_submitForm(e) {
        e.preventDefault();
        if (!dev_validateForm()) return;
        
        const btn = document.getElementById('dev_btn_submit');
        const originalText = btn.innerText;
        btn.innerHTML = '<span class="inline-flex items-center gap-2"><svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity="0.3"/><path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg> Processing...</span>';
        btn.disabled = true;

        try {
            const modbusId = parseInt(document.getElementById('dev_input_modbus').value);
            const deviceId = document.getElementById('dev_input_id').value.trim();
            
            if (dev_data.some(d => Number(d.modbus_slave_id) === modbusId && d.device_id !== deviceId)) {
                dev_showToast(`Modbus ID ${modbusId} is already in use.`, 'error');
                btn.innerText = originalText;
                btn.disabled = false;
                return;
            }

            const payload = {
                deviceId: deviceId,
                deviceName: document.getElementById('dev_input_name').value.trim(),
                labId: parseInt(document.getElementById('dev_input_lab').value) || null,
                modbus_slave_id: modbusId,
                lastCal: document.getElementById('dev_input_last_cal').value || null,
                status: document.getElementById('dev_input_status').value,
                userId: dev_userId || 1,
                username: localStorage.getItem('username') || 'Admin',
                userRole: dev_userRole
            }

            const method = dev_editMode ? 'PUT' : 'POST';
            const url = dev_editMode ? `/api/devices/${deviceId}` : '/api/devices';
            const response = await fetch(url, { method, headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });

            if (!response.ok) {
                const err = await response.json().catch(() => ({ message: 'Server error.' }));
                throw new Error(err.message || 'Failed to save device.');
            }

            dev_closeModal();
            dev_fetchDevices();
            dev_showToast(`Device ${dev_editMode ? 'updated' : 'created'} successfully!`, 'success');
        } catch (error) {
            console.error('Save device error:', error);
            dev_showToast(error.message || 'Connection failed.', 'error');
        } finally {
            btn.innerText = originalText;
            btn.disabled = false;
        }
    }

    function dev_delete(id) {
        if (dev_userRole !== 'admin') {
            Swal.fire({
                icon: 'error',
                title: 'Access Denied',
                text: 'Only Admin can delete devices.'
            });
            return;
        }
        Swal.fire({ title: 'Are you sure?', html: `Delete <strong>${dev_escapeHtml(id)}</strong>? This cannot be undone.`, icon: 'warning', showCancelButton: true, confirmButtonColor: '#E11D48', confirmButtonText: 'Yes, delete!' }).then(async res => {
            if (res.isConfirmed) {
                try {
                    const response = await fetch(`/api/devices/${id}`, { method: 'DELETE' });
                    if (response.ok) {
                        dev_selectedDevices.delete(id);
                        dev_fetchDevices();
                        dev_showToast(`${id} has been deleted`, 'success');
                    } else {
                        dev_showToast('Cannot delete device.', 'error');
                    }
                } catch (error) {
                    dev_showToast('Connection failed.', 'error');
                }
            }
        });
    }

    async function dev_viewHistory(deviceId) {
        const dev = dev_data.find(d => d.device_id === deviceId);
        if (!dev) return;
        document.getElementById('dev-main-view').classList.add('hidden');
        document.getElementById('dev-history-view').classList.remove('hidden');
        document.getElementById('dev_hist_device_name').innerText = dev.device_name || 'Unknown Device';
        document.getElementById('dev_hist_device_id').innerText = dev.device_id;
        document.getElementById('dev_hist_first_seen').innerText = dev.first_seen ? dev_formatDateTime(dev.first_seen) : 'Not Connected';
        document.getElementById('dev_hist_last_seen').innerText = dev.last_seen ? dev_formatDateTime(dev.last_seen) : 'No Data';
        
        const status = (dev.status || 'offline').toLowerCase();
        const statusEl = document.getElementById('dev_hist_status');
        statusEl.innerText = status;
        statusEl.className = `font-bold uppercase tracking-wide ${status === 'online' ? 'text-[#16A34A]' : status === 'maintenance' ? 'text-amber-500' : 'text-[#DC2626]'}`;
        
        let activeDays = "Just Added";
        if (dev.first_seen) {
            const diffDays = Math.ceil(Math.abs(new Date() - new Date(dev.first_seen)) / (1000 * 60 * 60 * 24));
            activeDays = `${diffDays} days`;
        }
        document.getElementById('dev_hist_active_days').innerText = activeDays;

        const tbody = document.getElementById('dev_history_table_body');
        tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-gray-500">Loading logs...</td></tr>`;

        try {
            const res = await fetch(`/api/devices/${encodeURIComponent(deviceId)}/logs`);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const logs = await res.json();
            
            if (!Array.isArray(logs) || logs.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-gray-500">No history found.</td></tr>`;
                return;
            }
            
            tbody.innerHTML = logs.map((log, i) => `
                <tr class="border-b border-blue-50 hover:bg-blue-50 transition-colors ${i % 2 === 0 ? 'bg-white' : 'bg-[#EFF6FF]'}">
                    <td class="p-4 text-center text-gray-500 font-medium">${i + 1}</td>
                    <td class="p-4 text-gray-800 font-bold text-sm">${dev_formatDateTime(log.date)}</td>
                    <td class="p-4"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold uppercase">${dev_escapeHtml((log.event || '').replace(/_/g, ' '))}</span></td>
                    <td class="p-4 text-gray-600 text-sm">${dev_escapeHtml(log.detail || '-')}</td>
                </tr>
            `).join('');
        } catch (error) {
            console.error('History fetch error:', error);
            tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-red-500">Failed to load history.</td></tr>`;
        }
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function dev_closeHistory() {
        document.getElementById('dev-history-view').classList.add('hidden');
        document.getElementById('dev-main-view').classList.remove('hidden');
    }

    if (document.getElementById('main-device-view')) dev_fetchDevices();
</script>