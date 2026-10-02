<div id="alt_view_list" class="flex-1 min-h-full relative animate-in fade-in duration-300 block">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Alert Management</h1>
            <p class="text-gray-500 mt-1 text-base">Monitor & Resolve Alerts</p>
        </div>
        <button onclick="alt_switchView('history')" class="bg-white border border-gray-300 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition-all font-medium flex items-center gap-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30">
            <i data-lucide="history" class="w-5 h-5" aria-hidden="true"></i> History
        </button>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3 mb-4">
        <div class="relative flex-1 max-w-md">
            <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" aria-hidden="true"></i>
            <input type="search" id="alt_search" oninput="alt_handleSearch(this.value)" placeholder="Search by device, parameter, or status..." class="w-full pl-10 pr-10 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all" aria-label="Search alerts">
            <button id="alt_clear_search" onclick="alt_clearSearch()" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" aria-label="Clear search">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div class="text-sm text-gray-500 whitespace-nowrap" id="alt_showing_info">0 alerts</div>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-blue-200 overflow-hidden outline outline-1 outline-blue-100 hidden md:block">
        <table class="w-full text-left border-collapse" role="grid" aria-label="Active alerts table">
            <thead>
                <tr class="bg-[#DBEAFE] text-gray-800 font-bold uppercase text-xs tracking-widest">
                    <th class="p-4 border-b border-blue-200 cursor-pointer hover:bg-blue-300/50 transition-colors select-none" onclick="alt_requestSort('time')">
                        <span class="inline-flex items-center gap-1.5">Time <span class="alt_sort_icon" data-key="time"></span></span>
                    </th>
                    <th class="p-4 border-b border-blue-200 cursor-pointer hover:bg-blue-300/50 transition-colors select-none" onclick="alt_requestSort('deviceId')">
                        <span class="inline-flex items-center gap-1.5">Device <span class="alt_sort_icon" data-key="deviceId"></span></span>
                    </th>
                    <th class="p-4 border-b border-blue-200 cursor-pointer hover:bg-blue-300/50 transition-colors select-none" onclick="alt_requestSort('parameter')">
                        <span class="inline-flex items-center gap-1.5">Parameter <span class="alt_sort_icon" data-key="parameter"></span></span>
                    </th>
                    <th class="p-4 border-b border-blue-200">Value</th>
                    <th class="p-4 border-b border-blue-200 text-center cursor-pointer hover:bg-blue-300/50 transition-colors select-none" onclick="alt_requestSort('level')">
                        <span class="inline-flex items-center gap-1.5">Level <span class="alt_sort_icon" data-key="level"></span></span>
                    </th>
                    <th class="p-4 border-b border-blue-200 cursor-pointer hover:bg-blue-300/50 transition-colors select-none" onclick="alt_requestSort('status')">
                        <span class="inline-flex items-center gap-1.5">Status <span class="alt_sort_icon" data-key="status"></span></span>
                    </th>
                    <th class="p-4 border-b border-blue-200 text-center">Action</th>
                </tr>
            </thead>
            <tbody id="alt_table_body"><tr><td colspan="7" class="text-center p-6 text-gray-500 italic">Loading alerts...</td></tr></tbody>
        </table>
    </div>

    <div id="alt_mobile_cards" class="md:hidden bg-white rounded-xl shadow-lg border border-blue-200 overflow-hidden outline outline-1 outline-blue-100 divide-y divide-blue-100"></div>

    <div id="alt_pagination" class="hidden flex items-center justify-between px-6 py-3 bg-gray-50 border border-blue-100 rounded-b-xl mt-[-1px]">
        <div class="text-sm text-gray-500" id="alt_page_info"></div>
        <div class="flex items-center gap-1">
            <button onclick="alt_changePage(-1)" id="alt_prev_btn" class="p-2 rounded-lg hover:bg-blue-100 disabled:opacity-40 disabled:cursor-not-allowed transition-colors" aria-label="Previous page"><i data-lucide="chevron-left" class="w-4 h-4"></i></button>
            <div id="alt_page_numbers" class="flex items-center gap-1"></div>
            <button onclick="alt_changePage(1)" id="alt_next_btn" class="p-2 rounded-lg hover:bg-blue-100 disabled:opacity-40 disabled:cursor-not-allowed transition-colors" aria-label="Next page"><i data-lucide="chevron-right" class="w-4 h-4"></i></button>
        </div>
    </div>
</div>

<div id="alt_view_history" class="flex-1 min-h-full relative animate-in slide-in-from-right-4 duration-300 hidden">
    <div class="max-w-7xl mx-auto">
        <button onclick="alt_switchView('list')" class="mb-6 flex items-center gap-2 text-gray-500 hover:text-blue-600 font-medium group transition-colors focus:outline-none">
            <div class="p-2 bg-white rounded-full shadow-sm group-hover:bg-blue-50 border border-gray-200 transition-colors"><i data-lucide="arrow-left" class="w-5 h-5"></i></div>
            <span>Back to Alert Management</span>
        </button>

        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Alert History Log</h1>
            <p class="text-gray-500 mt-1 text-base">Historical data of resolved alerts</p>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3 mb-4">
            <div class="relative flex-1 max-w-md">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" aria-hidden="true"></i>
                <input type="search" id="alt_hist_search" oninput="alt_handleHistSearch(this.value)" placeholder="Search history..." class="w-full pl-10 pr-10 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all" aria-label="Search history">
                <button id="alt_hist_clear_search" onclick="alt_clearHistSearch()" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" aria-label="Clear search">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="text-sm text-gray-500 whitespace-nowrap" id="alt_hist_showing_info">0 records</div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-blue-200 overflow-hidden outline outline-1 outline-blue-100 hidden md:block">
            <table class="w-full text-left border-collapse" role="grid" aria-label="Alert history table">
                <thead>
                    <tr class="bg-[#DBEAFE] text-gray-800 font-bold uppercase text-xs tracking-widest">
                        <th class="p-4 border-b border-blue-200 text-center w-16">No</th>
                        <th class="p-4 border-b border-blue-200">Time Range</th>
                        <th class="p-4 border-b border-blue-200">Device</th>
                        <th class="p-4 border-b border-blue-200">Param</th>
                        <th class="p-4 border-b border-blue-200">Peak Value</th>
                        <th class="p-4 border-b border-blue-200 text-center">Level</th>
                        <th class="p-4 border-b border-blue-200">Ack By</th>
                        <th class="p-4 border-b border-blue-200">Res By</th>
                        <th class="p-4 border-b border-blue-200 text-center">Duration</th>
                    </tr>
                </thead>
                <tbody id="alt_history_body"><tr><td colspan="9" class="text-center p-6 text-gray-500 italic">Loading history...</td></tr></tbody>
            </table>
        </div>

        <div id="alt_hist_mobile_cards" class="md:hidden bg-white rounded-xl shadow-lg border border-blue-200 overflow-hidden outline outline-1 outline-blue-100 divide-y divide-blue-100"></div>

        <div id="alt_hist_pagination" class="hidden flex items-center justify-between px-6 py-3 bg-gray-50 border border-blue-100 rounded-b-xl mt-[-1px]">
            <div class="text-sm text-gray-500" id="alt_hist_page_info"></div>
            <div class="flex items-center gap-1">
                <button onclick="alt_histChangePage(-1)" id="alt_hist_prev_btn" class="p-2 rounded-lg hover:bg-blue-100 disabled:opacity-40 disabled:cursor-not-allowed transition-colors" aria-label="Previous page"><i data-lucide="chevron-left" class="w-4 h-4"></i></button>
                <div id="alt_hist_page_numbers" class="flex items-center gap-1"></div>
                <button onclick="alt_histChangePage(1)" id="alt_hist_next_btn" class="p-2 rounded-lg hover:bg-blue-100 disabled:opacity-40 disabled:cursor-not-allowed transition-colors" aria-label="Next page"><i data-lucide="chevron-right" class="w-4 h-4"></i></button>
            </div>
        </div>
    </div>
</div>

<div id="alt_confirm_modal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true" aria-labelledby="alt_modal_title">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="alt_closeModal()"></div>
    <div class="relative bg-white w-full max-w-md max-h-[90vh] overflow-y-auto rounded-2xl shadow-2xl border border-gray-300 transform transition-all" role="document">
        <button onclick="alt_closeModal()" class="absolute top-4 right-4 p-2 rounded-full hover:bg-gray-100 transition-colors text-gray-400 hover:text-gray-600 focus:outline-none" aria-label="Close modal">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        <div class="p-8 text-center">
            <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="alert-circle" class="w-8 h-8 text-blue-500"></i>
            </div>
            <h3 id="alt_modal_title" class="text-2xl font-bold text-gray-900 mb-2 capitalize">Action Alert?</h3>
            <p class="text-gray-500 text-sm mb-6">Are you sure you want to proceed with this action?</p>
            
            <input type="hidden" id="alt_action_id">
            <input type="hidden" id="alt_action_type">
            
            <div class="mb-6 text-left">
                <label for="alt_remarks" class="block text-sm font-bold text-gray-700 mb-1">Remarks / Notes <span class="text-xs text-gray-400 font-normal">(Optional)</span></label>
                <textarea id="alt_remarks" rows="3" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 text-sm transition-all resize-none" placeholder="E.g., 'Sensor recalibrated', 'False alarm due to maintenance', etc."></textarea>
            </div>

            <div class="flex gap-3 justify-center">
                <button onclick="alt_closeModal()" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-all focus:outline-none focus:ring-2 focus:ring-gray-300">Cancel</button>
                <button onclick="alt_executeAction()" id="alt_btn_execute" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-60 disabled:cursor-not-allowed flex items-center gap-2">
                    Yes, Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<div id="alt_toast_container" class="fixed top-4 right-4 z-[10000] flex flex-col gap-2 pointer-events-none" aria-live="polite"></div>

<style>
    .alt_sort_icon::after { content: '↕'; font-size: 10px; opacity: 0.4; }
    .alt_sort_icon.asc::after { content: '↑'; opacity: 1; color: #1e40af; }
    .alt_sort_icon.desc::after { content: '↓'; opacity: 1; color: #1e40af; }
    .alt-skeleton { background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: alt-shimmer 1.5s infinite; border-radius: 4px; }
    @keyframes alt-shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
    .alt-toast { animation: alt-slide-in 0.3s ease-out forwards; pointer-events: auto; }
    .alt-toast.removing { animation: alt-slide-out 0.3s ease-in forwards; }
    @keyframes alt-slide-in { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @keyframes alt-slide-out { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
</style>

<script>
    let alt_data = [];
    let alt_historyData = [];
    let alt_sort = { key: 'time', direction: 'desc' };
    let alt_userRole = localStorage.getItem('userRole') || 'supervisor';
    let alt_userId = localStorage.getItem('userId');
    let alt_assignedLabs = [];

    let alt_searchQuery = '';
    let alt_histSearchQuery = '';
    let alt_currentPage = 1;
    let alt_histCurrentPage = 1;
    let alt_perPage = 10;
    let alt_isLoading = false;
    let alt_previousFocus = null;

    function alt_escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    function alt_showToast(message, type = 'success') {
        const container = document.getElementById('alt_toast_container');
        const toast = document.createElement('div');
        const colors = { success: 'bg-green-50 border-green-300 text-green-800', error: 'bg-red-50 border-red-300 text-red-800', info: 'bg-blue-50 border-blue-300 text-blue-800' };
        const icons = { success: 'check-circle', error: 'alert-circle', info: 'info' };
        toast.className = `alt-toast flex items-center gap-3 px-4 py-3 rounded-lg border shadow-lg ${colors[type]} text-sm font-medium min-w-[280px]`;
        toast.innerHTML = `<i data-lucide="${icons[type]}" class="w-5 h-5 flex-shrink-0"></i><span class="flex-1">${alt_escapeHtml(message)}</span><button onclick="this.parentElement.classList.add('removing'); setTimeout(() => this.parentElement.remove(), 300)" class="flex-shrink-0 hover:opacity-70" aria-label="Dismiss"><i data-lucide="x" class="w-4 h-4"></i></button>`;
        container.appendChild(toast);
        if (typeof lucide !== 'undefined') lucide.createIcons();
        setTimeout(() => { if (toast.parentElement) { toast.classList.add('removing'); setTimeout(() => toast.remove(), 300); } }, 4000);
    }

    function alt_formatDate(dateString) {
        if (!dateString) return '';
        const d = new Date(dateString);
        const day = String(d.getDate()).padStart(2, '0');
        const month = d.toLocaleString('en-US', { month: 'short' });
        const year = d.getFullYear();
        const hours = String(d.getHours()).padStart(2, '0');
        const minutes = String(d.getMinutes()).padStart(2, '0');
        return `${day} ${month} ${year}, ${hours}:${minutes}`;
    }

    function alt_getIcon(param, isOffline) {
        if (isOffline) return '<i data-lucide="unplug" class="w-4 h-4 text-red-600"></i>';
        if (param?.toLowerCase() === 'temperature') return '<i data-lucide="thermometer" class="w-4 h-4 text-blue-500"></i>';
        if (param?.toLowerCase() === 'humidity') return '<i data-lucide="droplets" class="w-4 h-4 text-green-500"></i>';
        return '<i data-lucide="alert-triangle" class="w-4 h-4 text-gray-500"></i>';
    }

    async function alt_init() {
        if (alt_userRole === 'supervisor') {
            try {
                const userRes = await fetch('/api/users');
                const users = await userRes.json();
                const me = users.find(u => u.user_id == alt_userId);
                if (me && me.assignedLabs) alt_assignedLabs = me.assignedLabs;
            } catch (err) { console.error("Failed to load user labs", err); }
        }
        alt_fetchAlerts();
    }

    function alt_renderSkeleton() {
        const tbody = document.getElementById('alt_table_body');
        const mobile = document.getElementById('alt_mobile_cards');
        let rows = '';
        for (let i = 0; i < 5; i++) {
            rows += `<tr class="bg-white"><td class="p-4 border-b border-blue-100"><div class="w-24 h-4 alt-skeleton"></div></td><td class="p-4 border-b border-blue-100"><div class="w-32 h-4 alt-skeleton mb-1"></div><div class="w-20 h-3 alt-skeleton"></div></td><td class="p-4 border-b border-blue-100"><div class="w-24 h-4 alt-skeleton"></div></td><td class="p-4 border-b border-blue-100"><div class="w-16 h-5 alt-skeleton"></div></td><td class="p-4 border-b border-blue-100 text-center"><div class="w-16 h-6 rounded-full mx-auto alt-skeleton"></div></td><td class="p-4 border-b border-blue-100"><div class="w-20 h-4 alt-skeleton"></div></td><td class="p-4 border-b border-blue-100 text-center"><div class="w-20 h-8 rounded-lg mx-auto alt-skeleton"></div></td></tr>`;
        }
        tbody.innerHTML = rows;
        mobile.innerHTML = Array(3).fill(`<div class="p-4 space-y-3"><div class="flex justify-between"><div class="w-24 h-4 alt-skeleton"></div><div class="w-16 h-6 rounded-full alt-skeleton"></div></div><div class="w-32 h-3 alt-skeleton"></div><div class="flex justify-between"><div class="w-20 h-4 alt-skeleton"></div><div class="w-16 h-5 alt-skeleton"></div></div><div class="w-full h-8 rounded-lg alt-skeleton"></div></div>`).join('');
    }

    function alt_renderErrorState() {
        const html = `<div class="flex flex-col items-center justify-center py-12 px-4 text-center"><div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mb-4"><i data-lucide="alert-triangle" class="w-8 h-8 text-red-400"></i></div><p class="text-gray-700 font-semibold mb-1">Failed to load alerts</p><p class="text-gray-500 text-sm mb-4">Please check your connection and try again.</p><button onclick="alt_fetchAlerts()" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors flex items-center gap-2"><i data-lucide="refresh-cw" class="w-4 h-4"></i> Retry</button></div>`;
        document.getElementById('alt_table_body').innerHTML = `<tr><td colspan="7">${html}</td></tr>`;
        document.getElementById('alt_mobile_cards').innerHTML = html;
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    async function alt_fetchAlerts() {
        alt_isLoading = true;
        alt_renderSkeleton();
        try {
            const res = await fetch(`/api/alerts`);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            let data = await res.json();
            
            let cleanedData = data.map(item => {
                let lvl = item.level?.toLowerCase() || '';
                lvl = lvl.includes('critical') ? 'critical' : (lvl.includes('warning') ? 'warning' : lvl);
                return {
                    ...item,
                    id: item.alert_id || item.id,
                    level: lvl,
                    status: item.status?.toLowerCase() || '',
                    time: alt_formatDate(item.triggered_at),
                    rawTime: item.triggered_at,
                    deviceId: item.device_id || item.deviceId,
                    value: parseFloat(item.triggered_value || 0),
                    threshold: item.threshold_value || item.threshold || 'N/A'
                };
            });

            if (alt_userRole === 'supervisor') {
                cleanedData = cleanedData.filter(a => alt_assignedLabs.includes(a.location));
            }

            alt_data = cleanedData;
            alt_currentPage = 1;
            alt_renderTable();
        } catch (error) { 
            console.error("Alert fetch error", error); 
            alt_renderErrorState();
        } finally {
            alt_isLoading = false;
        }
    }

    function alt_getFilteredData() {
        if (!alt_searchQuery) return [...alt_data];
        const q = alt_searchQuery.toLowerCase();
        return alt_data.filter(a => 
            (a.deviceId || '').toLowerCase().includes(q) ||
            (a.location || '').toLowerCase().includes(q) ||
            (a.parameter || '').toLowerCase().includes(q) ||
            (a.level || '').toLowerCase().includes(q) ||
            (a.status || '').toLowerCase().includes(q)
        );
    }

    function alt_getPaginatedData(data) {
        const start = (alt_currentPage - 1) * alt_perPage;
        return data.slice(start, start + alt_perPage);
    }

    function alt_getTotalPages(data) { return Math.max(1, Math.ceil(data.length / alt_perPage)); }

    function alt_handleSearch(value) {
        alt_searchQuery = value.trim();
        alt_currentPage = 1;
        document.getElementById('alt_clear_search').classList.toggle('hidden', !value);
        alt_renderTable();
    }

    function alt_clearSearch() {
        document.getElementById('alt_search').value = '';
        alt_handleSearch('');
    }

    function alt_renderTable() {
        const tbody = document.getElementById('alt_table_body');
        const mobile = document.getElementById('alt_mobile_cards');
        let filtered = alt_getFilteredData();
        
        filtered.sort((a, b) => {
            let aVal = a[alt_sort.key] || ''; 
            let bVal = b[alt_sort.key] || '';
            if (alt_sort.key === 'time') { 
                aVal = new Date(a.rawTime).getTime(); 
                bVal = new Date(b.rawTime).getTime(); 
            } else if (typeof aVal === 'string') { 
                aVal = aVal.toLowerCase(); 
                bVal = bVal.toLowerCase(); 
            }
            if (aVal < bVal) return alt_sort.direction === 'asc' ? -1 : 1;
            if (aVal > bVal) return alt_sort.direction === 'asc' ? 1 : -1;
            return 0;
        });

        document.getElementById('alt_showing_info').textContent = `${filtered.length} alert${filtered.length !== 1 ? 's' : ''}${alt_searchQuery ? ' found' : ''}`;

        if (filtered.length === 0) {
            const emptyHTML = `<div class="flex flex-col items-center justify-center py-12 px-4 text-center"><div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-4"><i data-lucide="check-circle" class="w-8 h-8 text-blue-300"></i></div><p class="text-gray-700 font-semibold mb-1">${alt_searchQuery ? 'No alerts match your search' : 'No active alerts'}</p><p class="text-gray-500 text-sm mb-4">${alt_searchQuery ? 'Try adjusting your search terms.' : 'All clear! Everything is running smoothly.'}</p></div>`;
            tbody.innerHTML = `<tr><td colspan="7">${emptyHTML}</td></tr>`;
            mobile.innerHTML = emptyHTML;
            document.getElementById('alt_pagination').classList.add('hidden');
            if (typeof lucide !== 'undefined') lucide.createIcons();
            alt_updateSortIcons();
            return;
        }

        const paginatedData = alt_getPaginatedData(filtered);
        const totalPages = alt_getTotalPages(filtered);

        // --- DESKTOP TABLE ---
        tbody.innerHTML = paginatedData.map((a, i) => {
            const hasBeenReminded = a.last_reminder_sent_at ? true : false;
            const reminderIcon = hasBeenReminded 
                ? `<i data-lucide="bell" class="w-3.5 h-3.5 text-amber-500 inline-block ml-1" title="Reminder email has been sent"></i>` 
                : '';

            const isOffline = a.value === 0;
            const effLvl = isOffline ? 'critical' : a.level;
            let lvlStyle = 'bg-gray-100 text-gray-700 border-gray-200';
            let dot = 'bg-gray-500';
            if (effLvl === 'critical') { lvlStyle = 'bg-red-100 text-red-700 border-red-200'; dot = 'bg-red-500'; }
            else if (effLvl === 'warning') { lvlStyle = 'bg-amber-100 text-amber-700 border-amber-200'; dot = 'bg-amber-500'; }

            let paramIcon = alt_getIcon(a.parameter, isOffline);
            let actionBtn = '';
            if (a.status === 'active') {
                actionBtn = `<button onclick="alt_initAction('${a.id}', 'acknowledge')" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold shadow-sm transition-all active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-300">Acknowledge</button>`;
            } else if (a.status === 'acknowledged') {
                actionBtn = `<button onclick="alt_initAction('${a.id}', 'resolve')" class="w-full py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-bold shadow-sm transition-all flex items-center justify-center gap-1 focus:outline-none focus:ring-2 focus:ring-green-300"><i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Resolve</button>`;
            }

            return `<tr class="transition-colors duration-200 hover:bg-blue-100/40 ${i % 2 === 0 ? 'bg-white' : 'bg-[#EFF6FF]'}">
                <td class="p-4 border-b border-blue-100 text-gray-600 font-medium text-sm whitespace-nowrap">${a.time}</td>
                <td class="p-4 border-b border-blue-100"><div class="flex flex-col"><span class="font-bold text-gray-900 text-sm">${alt_escapeHtml(a.deviceId)}</span><span class="text-xs text-gray-500 mt-0.5">${alt_escapeHtml(a.location || '-')}</span></div></td>
                <td class="p-4 border-b border-blue-100"><div class="flex items-center gap-2 font-medium text-gray-700 text-sm">${paramIcon} <span class="capitalize ${isOffline ? 'text-red-600 font-bold' : ''}">${alt_escapeHtml(isOffline ? 'Connection Loss' : a.parameter)}</span></div></td>
                <td class="p-4 border-b border-blue-100"><div class="flex flex-col"><span class="font-bold text-base ${isOffline ? 'text-red-500' : 'text-gray-900'}">${isOffline ? 'OFFLINE' : `${a.value}${a.parameter === 'humidity' ? '%' : '°C'}`}</span>${!isOffline ? `<span class="text-xs text-gray-400 mt-0.5">Limit: ${alt_escapeHtml(a.threshold)}</span>` : ''}</div></td>
                <td class="p-4 border-b border-blue-100 text-center"><span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wide flex items-center justify-center gap-1 w-fit mx-auto border ${lvlStyle}"><span class="w-2 h-2 rounded-full ${dot}"></span>${effLvl}</span></td>
                <td class="p-4 border-b border-blue-100 font-medium text-gray-700 capitalize text-sm">${a.status} ${reminderIcon}</td>
                <td class="p-4 border-b border-blue-100 text-center">${actionBtn}</td>
            </tr>`;
        }).join('');

        // --- MOBILE CARDS ---
        mobile.innerHTML = paginatedData.map((a) => {
            const hasBeenReminded = a.last_reminder_sent_at ? true : false;
            const reminderIcon = hasBeenReminded 
                ? `<i data-lucide="bell" class="w-3.5 h-3.5 text-amber-500 inline-block ml-1" title="Reminder email has been sent"></i>` 
                : '';

            const isOffline = a.value === 0;
            const effLvl = isOffline ? 'critical' : a.level;
            let lvlStyle = 'bg-gray-100 text-gray-700 border-gray-200';
            let dot = 'bg-gray-500';
            if (effLvl === 'critical') { lvlStyle = 'bg-red-100 text-red-700 border-red-200'; dot = 'bg-red-500'; }
            else if (effLvl === 'warning') { lvlStyle = 'bg-amber-100 text-amber-700 border-amber-200'; dot = 'bg-amber-500'; }
            let paramIcon = alt_getIcon(a.parameter, isOffline);
            let actionBtn = '';
            if (a.status === 'active') {
                actionBtn = `<button onclick="alt_initAction('${a.id}', 'acknowledge')" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold shadow-sm transition-all active:scale-95">Acknowledge</button>`;
            } else if (a.status === 'acknowledged') {
                actionBtn = `<button onclick="alt_initAction('${a.id}', 'resolve')" class="w-full py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-bold shadow-sm transition-all flex items-center justify-center gap-1"><i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Resolve</button>`;
            }
            return `<div class="p-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex items-center gap-2">${paramIcon}<span class="font-bold text-gray-900 text-sm">${alt_escapeHtml(a.deviceId)}</span></div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wide flex items-center gap-1 border ${lvlStyle}"><span class="w-1.5 h-1.5 rounded-full ${dot}"></span>${effLvl}</span>
                </div>
                <div class="text-xs text-gray-500 mb-2">${alt_escapeHtml(a.location || '-')} • ${a.time}</div>
                <div class="flex items-center justify-between mb-3">
                    <div><div class="text-xs text-gray-500 uppercase font-bold">Parameter</div><div class="text-sm font-medium text-gray-800 capitalize">${alt_escapeHtml(isOffline ? 'Connection Loss' : a.parameter)}</div></div>
                    <div class="text-right"><div class="text-xs text-gray-500 uppercase font-bold">Value</div><div class="text-base font-bold ${isOffline ? 'text-red-500' : 'text-gray-900'}">${isOffline ? 'OFFLINE' : `${a.value}${a.parameter === 'humidity' ? '%' : '°C'}`}</div></div>
                </div>
                <div class="text-xs text-gray-400 mb-3">Limit: ${alt_escapeHtml(a.threshold)} • Status: <span class="capitalize font-medium text-gray-600">${a.status} ${reminderIcon}</span></div>
                ${actionBtn}
            </div>`;
        }).join('');

        if (typeof lucide !== 'undefined') lucide.createIcons();
        alt_updateSortIcons();
        alt_renderPagination(filtered.length, totalPages);
    }

    function alt_updateSortIcons() {
        document.querySelectorAll('.alt_sort_icon').forEach(el => {
            const key = el.dataset.key;
            el.className = 'alt_sort_icon';
            if (alt_sort.key === key) el.classList.add(alt_sort.direction);
        });
    }

    function alt_requestSort(key) {
        alt_sort.direction = (alt_sort.key === key && alt_sort.direction === 'asc') ? 'desc' : 'asc';
        alt_sort.key = key;
        alt_renderTable();
    }

    function alt_renderPagination(total, totalPages) {
        const paginationEl = document.getElementById('alt_pagination');
        if (totalPages <= 1) { paginationEl.classList.add('hidden'); return; }
        paginationEl.classList.remove('hidden');
        paginationEl.classList.add('flex');
        const start = (alt_currentPage - 1) * alt_perPage + 1;
        const end = Math.min(alt_currentPage * alt_perPage, total);
        document.getElementById('alt_page_info').textContent = `Showing ${start}-${end} of ${total}`;
        document.getElementById('alt_prev_btn').disabled = alt_currentPage <= 1;
        document.getElementById('alt_next_btn').disabled = alt_currentPage >= totalPages;
        
        const container = document.getElementById('alt_page_numbers');
        let pages = [];
        const delta = 1;
        const left = Math.max(2, alt_currentPage - delta);
        const right = Math.min(totalPages - 1, alt_currentPage + delta);
        pages.push(1);
        if (left > 2) pages.push('...');
        for (let i = left; i <= right; i++) pages.push(i);
        if (right < totalPages - 1) pages.push('...');
        if (totalPages > 1) pages.push(totalPages);
        
        container.innerHTML = pages.map(p => {
            if (p === '...') return `<span class="px-2 text-gray-400 text-sm">…</span>`;
            const isActive = p === alt_currentPage;
            return `<button onclick="alt_goToPage(${p})" class="w-8 h-8 rounded-lg text-sm font-medium transition-colors ${isActive ? 'bg-blue-500 text-white shadow-sm' : 'hover:bg-blue-100 text-gray-600'}" ${isActive ? 'aria-current="page"' : ''}>${p}</button>`;
        }).join('');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function alt_changePage(delta) {
        const filtered = alt_getFilteredData();
        const totalPages = alt_getTotalPages(filtered);
        const newPage = alt_currentPage + delta;
        if (newPage >= 1 && newPage <= totalPages) {
            alt_currentPage = newPage;
            alt_renderTable();
            document.querySelector('#alt_view_list')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function alt_goToPage(page) { alt_currentPage = page; alt_renderTable(); }

    function alt_initAction(id, type) {
        document.getElementById('alt_action_id').value = id;
        document.getElementById('alt_action_type').value = type;
        document.getElementById('alt_modal_title').innerText = `${type} Alert?`;
        document.getElementById('alt_btn_execute').innerText = `Yes, ${type}`;
        
        document.getElementById('alt_remarks').value = ''; 
        
        const remarkInput = document.getElementById('alt_remarks');
        if (type === 'acknowledge') {
            remarkInput.placeholder = "E.g., 'Sedang mengecek sensor', 'Tim maintenance telah diberitahu'";
        } else {
            remarkInput.placeholder = "E.g., 'Sensor telah diganti', 'Suhu sudah normal', 'False alarm akibat kalibrasi'";
        }
        
        alt_previousFocus = document.activeElement;
        document.getElementById('alt_confirm_modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('alt_btn_execute').focus(), 100);
    }

    function alt_closeModal() { 
        document.getElementById('alt_confirm_modal').classList.add('hidden'); 
        document.body.style.overflow = '';
        if (alt_previousFocus) { alt_previousFocus.focus(); alt_previousFocus = null; }
    }

    document.addEventListener('keydown', (e) => {
        const modal = document.getElementById('alt_confirm_modal');
        if (!modal.classList.contains('hidden')) {
            if (e.key === 'Escape') { alt_closeModal(); return; }
            if (e.key === 'Tab') {
                const focusable = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                const first = focusable[0], last = focusable[focusable.length - 1];
                if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
                else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
            }
        }
    });

    async function alt_executeAction() {
        const id = document.getElementById('alt_action_id').value;
        const type = document.getElementById('alt_action_type').value;
        const newStatus = type === 'acknowledge' ? 'Acknowledged' : 'Resolved';
        const userId = alt_userId ? parseInt(alt_userId) : null;
        
        // ✅ Ambil nilai remarks
        const remarks = document.getElementById('alt_remarks').value.trim();
        
        const btn = document.getElementById('alt_btn_execute');
        const originalText = btn.innerText;
        
        btn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity="0.3"/><path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg> Processing...';
        btn.disabled = true;

        try {
            const res = await fetch(`/api/alerts/${id}/status`, { 
                method: 'PUT', 
                headers: {'Content-Type': 'application/json'}, 
                // ✅ TAMBAHKAN 'remarks' DI SINI
                body: JSON.stringify({ status: newStatus, userId, remarks }) 
            });
            if (res.ok) {
                alt_closeModal();
                if(document.getElementById('alt_view_history').classList.contains('hidden') === false) {
                    alt_fetchHistory();
                } else {
                    alt_fetchAlerts();
                }
                alt_showToast(`Alert has been ${newStatus.toLowerCase()}!`, 'success');
            } else {
                const err = await res.json().catch(() => ({}));
                alt_showToast(err.message || 'Failed to process action', 'error');
            }
        } catch (error) { 
            console.error(error); 
            alt_showToast('Network connection failed.', 'error');
        } finally {
            btn.innerText = originalText;
            btn.disabled = false;
        }
    }

    async function alt_fetchHistory() {
        const tbody = document.getElementById('alt_history_body');
        tbody.innerHTML = `<tr><td colspan="9" class="text-center p-6 text-gray-500 italic">Loading history...</td></tr>`;
        try {
            const res = await fetch(`/api/alerts/history`);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            let data = await res.json();

            let cleanedData = data.map(item => {
                let lvl = item.level?.toLowerCase() || '';
                lvl = lvl.includes('critical') ? 'critical' : (lvl.includes('warning') ? 'warning' : lvl);
                return { ...item, level: lvl, peak: parseFloat(item.peak || 0) };
            });

            if (alt_userRole === 'supervisor') {
                cleanedData = cleanedData.filter(a => alt_assignedLabs.includes(a.location));
            }

            alt_historyData = cleanedData;
            alt_histCurrentPage = 1;
            alt_renderHistory();
        } catch (error) { 
            console.error(error); 
            tbody.innerHTML = `<tr><td colspan="9" class="text-center p-6 text-red-500">Failed to load history.</td></tr>`;
        }
    }

    function alt_getHistFilteredData() {
        if (!alt_histSearchQuery) return [...alt_historyData];
        const q = alt_histSearchQuery.toLowerCase();
        return alt_historyData.filter(a => 
            (a.deviceId || '').toLowerCase().includes(q) ||
            (a.location || '').toLowerCase().includes(q) ||
            (a.parameter || '').toLowerCase().includes(q) ||
            (a.level || '').toLowerCase().includes(q) ||
            (a.ackBy || '').toLowerCase().includes(q) ||
            (a.resBy || '').toLowerCase().includes(q)
        );
    }

    function alt_getHistPaginatedData(data) {
        const start = (alt_histCurrentPage - 1) * alt_perPage;
        return data.slice(start, start + alt_perPage);
    }

    function alt_getHistTotalPages(data) { return Math.max(1, Math.ceil(data.length / alt_perPage)); }

    function alt_handleHistSearch(value) {
        alt_histSearchQuery = value.trim();
        alt_histCurrentPage = 1;
        document.getElementById('alt_hist_clear_search').classList.toggle('hidden', !value);
        alt_renderHistory();
    }

    function alt_clearHistSearch() {
        document.getElementById('alt_hist_search').value = '';
        alt_handleHistSearch('');
    }

function alt_renderHistory() {
    const tbody = document.getElementById('alt_history_body');
    const mobile = document.getElementById('alt_hist_mobile_cards');
    let filtered = alt_getHistFilteredData();
    
    document.getElementById('alt_hist_showing_info').textContent = `${filtered.length} record${filtered.length !== 1 ? 's' : ''}${alt_histSearchQuery ? ' found' : ''}`;

    if (filtered.length === 0) {
        const emptyHTML = `<div class="flex flex-col items-center justify-center py-12 px-4 text-center"><div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-4"><i data-lucide="file-text" class="w-8 h-8 text-blue-300"></i></div><p class="text-gray-700 font-semibold mb-1">${alt_histSearchQuery ? 'No records match your search' : 'No alert history found'}</p><p class="text-gray-500 text-sm mb-4">${alt_histSearchQuery ? 'Try adjusting your search terms.' : 'Historical data will appear here once alerts are resolved.'}</p></div>`;
        tbody.innerHTML = `<tr><td colspan="9">${emptyHTML}</td></tr>`;
        mobile.innerHTML = emptyHTML;
        document.getElementById('alt_hist_pagination').classList.add('hidden');
        if (typeof lucide !== 'undefined') lucide.createIcons();
        return;
    }

    const paginatedData = alt_getHistPaginatedData(filtered);
    const totalPages = alt_getHistTotalPages(filtered);

    tbody.innerHTML = paginatedData.map((item, index) => {
        const isOffline = item.peak === 0;
        
        // ✅ FIX: Render ack_remarks dan res_remarks terpisah di kolom masing-masing
        const ackRemarkHtml = item.ack_remarks 
            ? `<div class="text-[11px] text-gray-600 mt-1.5 bg-blue-50 p-2 rounded border border-blue-100 flex items-start gap-1.5">
                 <i data-lucide="message-circle" class="w-3 h-3 mt-0.5 flex-shrink-0 text-blue-500"></i>
                 <span class="text-gray-700">${alt_escapeHtml(item.ack_remarks)}</span>
               </div>` 
            : '';
            
        const resRemarkHtml = item.res_remarks 
            ? `<div class="text-[11px] text-gray-600 mt-1.5 bg-green-50 p-2 rounded border border-green-100 flex items-start gap-1.5">
                 <i data-lucide="check-circle-2" class="w-3 h-3 mt-0.5 flex-shrink-0 text-green-600"></i>
                 <span class="text-gray-700">${alt_escapeHtml(item.res_remarks)}</span>
               </div>` 
            : '';

        let lvlStyle = 'bg-gray-100 text-gray-700 border-gray-200';
        let dot = 'bg-gray-500';
        if (isOffline) {
            lvlStyle = 'bg-red-100 text-red-700 border-red-300 shadow-[inset_0_0_8px_rgba(220,38,38,0.2)]';
            dot = 'bg-red-500';
        } else if (item.level === 'critical') {
            lvlStyle = 'bg-red-100 text-red-700 border-red-200'; 
            dot = 'bg-red-500';
        } else if (item.level === 'warning') {
            lvlStyle = 'bg-amber-100 text-amber-700 border-amber-200'; 
            dot = 'bg-amber-500';
        }
        let paramIcon = alt_getIcon(item.parameter, isOffline);

        return `<tr class="transition-colors duration-200 hover:bg-blue-100/40 ${index % 2 === 0 ? 'bg-white' : 'bg-[#EFF6FF]'}">
            <td class="p-4 border-b border-blue-100 text-center font-medium text-gray-500 text-sm align-top">${index + 1}</td>
            <td class="p-4 border-b border-blue-100 whitespace-nowrap align-top"><div class="flex flex-col"><span class="font-bold text-gray-800 text-sm">${alt_escapeHtml(item.start)}</span><span class="text-xs text-gray-500 mt-0.5">End: ${alt_escapeHtml(item.end)}</span></div></td>
            <td class="p-4 border-b border-blue-100 align-top"><div class="flex flex-col"><span class="font-bold text-gray-900 text-sm">${alt_escapeHtml(item.deviceId)}</span><span class="text-xs text-gray-500 mt-0.5">${alt_escapeHtml(item.location || '-')}</span></div></td>
            <td class="p-4 border-b border-blue-100 align-top"><div class="flex items-center gap-2 font-medium text-gray-700 text-sm">${paramIcon} <span class="capitalize ${isOffline ? 'text-red-600 font-bold' : ''}">${alt_escapeHtml(isOffline ? 'Connection Loss' : item.parameter)}</span></div></td>
            <td class="p-4 border-b border-blue-100 align-top"><div class="flex flex-col"><span class="font-bold text-base ${isOffline ? 'text-red-500' : 'text-gray-900'}">${isOffline ? 'OFFLINE' : `${item.peak}${item.parameter === 'humidity' ? '%' : '°C'}`}</span>${!isOffline ? `<span class="text-xs text-gray-400 mt-0.5">Threshold: ${alt_escapeHtml(item.threshold)}</span>` : ''}</div></td>
            <td class="p-4 border-b border-blue-100 text-center align-top"><span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wide flex items-center justify-center gap-1 w-fit mx-auto border ${lvlStyle}"><span class="w-1.5 h-1.5 rounded-full ${dot}"></span>${isOffline ? 'CRITICAL' : item.level}</span></td>
            <td class="p-4 border-b border-blue-100 align-top">
                <div class="font-medium text-gray-700 text-sm">${item.ackBy === 'System' ? '<span class="text-gray-400 italic">N/A</span>' : alt_escapeHtml(item.ackBy)}</div>
                ${ackRemarkHtml}
            </td>
            <td class="p-4 border-b border-blue-100 align-top">
                <div class="font-medium text-gray-700 text-sm">${item.resBy === 'System' ? '<span class="text-gray-400 italic">N/A</span>' : alt_escapeHtml(item.resBy)}</div>
                ${resRemarkHtml}
            </td>
            <td class="p-4 border-b border-blue-100 text-center align-top"><span class="font-bold text-blue-700 bg-blue-50/50 px-2 py-1 rounded text-sm">${alt_escapeHtml(item.duration)}</span></td>
        </tr>`;
    }).join('');

    // Mobile cards juga perlu diupdate
    mobile.innerHTML = paginatedData.map((item, index) => {
        const isOffline = item.peak === 0;
        
        const ackRemarkHtml = item.ack_remarks 
            ? `<div class="text-[11px] mt-2 bg-blue-50 p-2 rounded border border-blue-100 flex items-start gap-1.5">
                 <i data-lucide="message-circle" class="w-3 h-3 mt-0.5 flex-shrink-0 text-blue-500"></i>
                 <span class="text-gray-700">${alt_escapeHtml(item.ack_remarks)}</span>
               </div>` 
            : '';
            
        const resRemarkHtml = item.res_remarks 
            ? `<div class="text-[11px] mt-2 bg-green-50 p-2 rounded border border-green-100 flex items-start gap-1.5">
                 <i data-lucide="check-circle-2" class="w-3 h-3 mt-0.5 flex-shrink-0 text-green-600"></i>
                 <span class="text-gray-700">${alt_escapeHtml(item.res_remarks)}</span>
               </div>` 
            : '';
        
        let lvlStyle = 'bg-gray-100 text-gray-700 border-gray-200';
        let dot = 'bg-gray-500';
        if (isOffline) {
            lvlStyle = 'bg-red-100 text-red-700 border-red-300';
            dot = 'bg-red-500';
        } else if (item.level === 'critical') {
            lvlStyle = 'bg-red-100 text-red-700 border-red-200'; 
            dot = 'bg-red-500';
        } else if (item.level === 'warning') {
            lvlStyle = 'bg-amber-100 text-amber-700 border-amber-200'; 
            dot = 'bg-amber-500';
        }
        let paramIcon = alt_getIcon(item.parameter, isOffline);

        return `<div class="p-4">
            <div class="flex items-start justify-between mb-2">
                <div class="flex items-center gap-2">${paramIcon}<span class="font-bold text-gray-900 text-sm">${alt_escapeHtml(item.deviceId)}</span></div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wide flex items-center gap-1 border ${lvlStyle}"><span class="w-1.5 h-1.5 rounded-full ${dot}"></span>${isOffline ? 'CRITICAL' : item.level}</span>
            </div>
            <div class="text-xs text-gray-500 mb-2">${alt_escapeHtml(item.location || '-')} • ${alt_escapeHtml(item.duration)}</div>
            <div class="flex items-center justify-between mb-3">
                <div><div class="text-xs text-gray-500 uppercase font-bold">Parameter</div><div class="text-sm font-medium text-gray-800 capitalize">${alt_escapeHtml(isOffline ? 'Connection Loss' : item.parameter)}</div></div>
                <div class="text-right"><div class="text-xs text-gray-500 uppercase font-bold">Peak</div><div class="text-base font-bold ${isOffline ? 'text-red-500' : 'text-gray-900'}">${isOffline ? 'OFFLINE' : `${item.peak}${item.parameter === 'humidity' ? '%' : '°C'}`}</div></div>
            </div>
            <div class="text-xs text-gray-500 mb-1">Start: <span class="font-medium text-gray-700">${alt_escapeHtml(item.start)}</span></div>
            <div class="text-xs text-gray-500 mb-3">End: <span class="font-medium text-gray-700">${alt_escapeHtml(item.end)}</span></div>
            <div class="border-t border-gray-100 pt-2 space-y-2">
                <div class="text-xs"><span class="text-gray-500">Ack by:</span> <span class="font-medium text-gray-700">${item.ackBy === 'System' ? 'N/A' : alt_escapeHtml(item.ackBy)}</span>${ackRemarkHtml}</div>
                <div class="text-xs"><span class="text-gray-500">Res by:</span> <span class="font-medium text-gray-700">${item.resBy === 'System' ? 'N/A' : alt_escapeHtml(item.resBy)}</span>${resRemarkHtml}</div>
            </div>
        </div>`;
    }).join('');

    if (typeof lucide !== 'undefined') lucide.createIcons();
    alt_renderHistPagination(filtered.length, totalPages);
}

    function alt_renderHistPagination(total, totalPages) {
        const paginationEl = document.getElementById('alt_hist_pagination');
        if (totalPages <= 1) { paginationEl.classList.add('hidden'); return; }
        paginationEl.classList.remove('hidden');
        paginationEl.classList.add('flex');
        const start = (alt_histCurrentPage - 1) * alt_perPage + 1;
        const end = Math.min(alt_histCurrentPage * alt_perPage, total);
        document.getElementById('alt_hist_page_info').textContent = `Showing ${start}-${end} of ${total}`;
        document.getElementById('alt_hist_prev_btn').disabled = alt_histCurrentPage <= 1;
        document.getElementById('alt_hist_next_btn').disabled = alt_histCurrentPage >= totalPages;
        
        const container = document.getElementById('alt_hist_page_numbers');
        let pages = [];
        const delta = 1;
        const left = Math.max(2, alt_histCurrentPage - delta);
        const right = Math.min(totalPages - 1, alt_histCurrentPage + delta);
        pages.push(1);
        if (left > 2) pages.push('...');
        for (let i = left; i <= right; i++) pages.push(i);
        if (right < totalPages - 1) pages.push('...');
        if (totalPages > 1) pages.push(totalPages);
        
        container.innerHTML = pages.map(p => {
            if (p === '...') return `<span class="px-2 text-gray-400 text-sm">…</span>`;
            const isActive = p === alt_histCurrentPage;
            return `<button onclick="alt_histGoToPage(${p})" class="w-8 h-8 rounded-lg text-sm font-medium transition-colors ${isActive ? 'bg-blue-500 text-white shadow-sm' : 'hover:bg-blue-100 text-gray-600'}" ${isActive ? 'aria-current="page"' : ''}>${p}</button>`;
        }).join('');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function alt_histChangePage(delta) {
        const filtered = alt_getHistFilteredData();
        const totalPages = alt_getHistTotalPages(filtered);
        const newPage = alt_histCurrentPage + delta;
        if (newPage >= 1 && newPage <= totalPages) {
            alt_histCurrentPage = newPage;
            alt_renderHistory();
            document.querySelector('#alt_view_history')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function alt_histGoToPage(page) { alt_histCurrentPage = page; alt_renderHistory(); }

    function alt_switchView(view) {
        if (view === 'history') {
            document.getElementById('alt_view_list').classList.add('hidden');
            document.getElementById('alt_view_history').classList.remove('hidden');
            alt_fetchHistory();
        } else {
            document.getElementById('alt_view_history').classList.add('hidden');
            document.getElementById('alt_view_list').classList.remove('hidden');
            alt_fetchAlerts();
        }
    }

    if(document.getElementById('alt_view_list')) alt_init();
</script>