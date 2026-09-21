<div id="lab_main_view" class="flex-1 min-h-full relative animate-in fade-in duration-300 block">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Lab Management</h1>
            <p class="text-gray-500 mt-1 text-base">Manage Rooms and Monitoring Scenarios</p>
        </div>
        <button onclick="lab_openModal()" class="bg-[#6B6565] text-white px-6 py-2.5 rounded-lg hover:bg-[#5a5454] flex items-center gap-2 shadow-md transition-all active:scale-95 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#6B6565]">
            <i data-lucide="plus-square" class="w-5 h-5" aria-hidden="true"></i> Add New Lab
        </button>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3 mb-6">
        <div class="relative flex-1 max-w-md">
            <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" aria-hidden="true"></i>
            <input type="search" id="lab_search" oninput="lab_handleSearch(this.value)" placeholder="Search lab name..." class="w-full pl-10 pr-10 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all" aria-label="Search labs">
            <button id="lab_clear_search" onclick="lab_clearSearch()" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" aria-label="Clear search">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div class="text-sm text-gray-500 whitespace-nowrap" id="lab_showing_info">0 labs</div>
    </div>

    <div id="lab_grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <p class="text-gray-500 italic col-span-full text-center py-8">Loading labs...</p>
    </div>
</div>

<div id="lab_rules_view" class="flex-1 min-h-full relative animate-in slide-in-from-right-4 duration-300 hidden">
    <div class="max-w-7xl mx-auto">
        <button onclick="rule_closeView()" class="mb-6 flex items-center gap-2 text-gray-500 hover:text-blue-600 font-medium group transition-colors focus:outline-none">
            <div class="p-2 bg-white rounded-full shadow-sm group-hover:bg-blue-50 border border-gray-200 transition-colors"><i data-lucide="arrow-left" class="w-5 h-5"></i></div>
            <span>Back to Labs</span>
        </button>
        
        <div class="mb-8 p-6 bg-blue-600 rounded-2xl text-white shadow-lg flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Rules: <span id="rule_header_lab">Lab Name</span></h1>
                <p class="opacity-90 mt-1 text-sm">Operational configuration for devices in this area.</p>
            </div>
            <div class="flex flex-wrap gap-6 md:gap-8 border-t md:border-t-0 md:border-l border-white/20 pt-4 md:pt-0 md:pl-8 w-full md:w-auto justify-around">
                <div class="text-center">
                    <span class="block text-[10px] uppercase font-black opacity-70">Target Temp</span>
                    <span id="rule_target_temp" class="text-xl font-bold">Not Set</span>
                </div>
                <div class="text-center">
                    <span class="block text-[10px] uppercase font-black opacity-70">Target Hum</span>
                    <span id="rule_target_hum" class="text-xl font-bold">Not Set</span>
                </div>
                <div class="text-center">
                    <span class="block text-[10px] uppercase font-black opacity-70">In Charge</span>
                    <span id="rule_header_pic" class="text-xl font-bold">-</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Active Rules</h2>
                <p class="text-gray-500 mt-1 text-sm">Manage monitoring thresholds for devices.</p>
            </div>
            <button onclick="rule_openModal()" class="bg-[#3B82F6] text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition-all font-medium flex items-center gap-2 shadow-sm active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i data-lucide="plus-circle" class="w-4 h-4" aria-hidden="true"></i> Add New Rule
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-blue-200 overflow-hidden outline outline-1 outline-blue-100 hidden md:block">
            <table class="w-full text-left border-collapse" role="grid" aria-label="Active rules table">
                <thead>
                    <tr class="bg-[#DBEAFE] text-gray-800 font-bold uppercase text-xs tracking-widest">
                        <th class="p-4 border-b border-blue-200">Device</th>
                        <th class="p-4 border-b border-blue-200">Parameter</th>
                        <th class="p-4 border-b border-blue-200">Condition</th>
                        <th class="p-4 border-b border-blue-200 text-center">Severity</th>
                        <th class="p-4 border-b border-blue-200 text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="rule_table_body">
                    <tr><td colspan="5" class="text-center p-6 text-gray-500 italic">Loading rules...</td></tr>
                </tbody>
            </table>
        </div>

        <div id="rule_mobile_cards" class="md:hidden bg-white rounded-xl shadow-lg border border-blue-200 overflow-hidden outline outline-1 outline-blue-100 divide-y divide-blue-100"></div>
    </div>
</div>

<div id="lab_modal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true" aria-labelledby="lab_modal_title">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="lab_closeModal()"></div>
    <div class="relative bg-white w-full max-w-md rounded-2xl shadow-2xl border border-gray-300 transform transition-all" role="document">
        <button onclick="lab_closeModal()" class="absolute top-4 right-4 p-2 rounded-full hover:bg-gray-100 transition-colors text-gray-400 hover:text-gray-600 focus:outline-none" aria-label="Close modal">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        <div class="p-8">
            <h2 id="lab_modal_title" class="text-2xl font-bold mb-6 text-gray-900 text-center">Add New Lab</h2>
            <form id="lab_form" onsubmit="lab_submitForm(event)" class="space-y-4" novalidate>
                <input type="hidden" id="lab_input_id">
                <div>
                    <label for="lab_input_name" class="block text-sm font-bold text-gray-700 mb-1">Lab Name <span class="text-red-500">*</span></label>
                    <input type="text" id="lab_input_name" required class="w-full p-3 bg-gray-100 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 text-sm" aria-required="true">
                    <p id="lab_name_error" class="hidden text-xs text-red-500 mt-1 ml-1"></p>
                </div>
                <div>
                    <label for="lab_input_pic" class="block text-sm font-bold text-gray-700 mb-1">PIC Name <span class="text-red-500">*</span></label>
                    <select id="lab_input_pic" required class="w-full p-3 bg-gray-100 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 text-sm cursor-pointer" aria-required="true">
                        <option value="" disabled selected>Loading users...</option>
                    </select>
                    <p id="lab_pic_error" class="hidden text-xs text-red-500 mt-1 ml-1"></p>
                </div>
                <div>
                    <label for="lab_input_desc" class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                    <textarea id="lab_input_desc" rows="3" class="w-full p-3 bg-gray-100 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none"></textarea>
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="lab_closeModal()" class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-xl font-bold hover:bg-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300">Cancel</button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 shadow-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-60 disabled:cursor-not-allowed">Save Lab</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="rule_modal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true" aria-labelledby="rule_modal_title">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="rule_closeModal()"></div>
    <div class="relative bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-gray-300 transform transition-all" role="document">
        <button onclick="rule_closeModal()" class="absolute top-4 right-4 p-2 rounded-full hover:bg-gray-100 transition-colors text-gray-400 hover:text-gray-600 focus:outline-none" aria-label="Close modal">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        <div class="p-8">
            <h2 id="rule_modal_title" class="text-2xl font-bold mb-2 text-gray-900 text-center">Add New Rule</h2>
            <p id="rule_overwrite_warn" class="text-center text-red-500 font-bold mb-6 text-xs bg-red-50 py-2 rounded-lg border border-red-100 hidden">This will OVERWRITE existing rules for this parameter.</p>
            
            <form id="rule_form" onsubmit="rule_submitForm(event)" class="space-y-5" novalidate>
                <input type="hidden" id="rule_edit_mode">
                
                <div class="space-y-1">
                    <label for="rule_input_param" class="block text-sm font-bold text-gray-700">Parameter <span class="text-red-500">*</span></label>
                    <select id="rule_input_param" onchange="rule_handleParamChange()" class="w-full p-3.5 bg-gray-100 border border-gray-200 rounded-lg outline-none text-sm cursor-pointer focus:ring-2 focus:ring-blue-500" aria-required="true">
                        <option value="temperature">Temperature</option>
                        <option value="humidity">Humidity</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label for="rule_input_device" class="block text-sm font-bold text-gray-700">Assigned Device <span class="text-red-500">*</span></label>
                    <select id="rule_input_device" required class="w-full p-3.5 bg-gray-100 border border-gray-200 rounded-lg outline-none text-sm cursor-pointer focus:ring-2 focus:ring-blue-500" aria-required="true">
                        <option value="" disabled selected>Loading devices...</option>
                    </select>
                    <p id="rule_no_device_warn" class="text-red-500 text-xs mt-1 font-medium hidden">All devices in this lab are fully configured for this parameter.</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label for="rule_input_lower" class="block text-sm font-bold text-gray-700">Lower Limit <span class="text-red-500">*</span></label>
                        <input type="number" step="0.1" id="rule_input_lower" required value="18" class="w-full p-3.5 bg-gray-100 border border-gray-200 rounded-lg outline-none font-bold text-blue-600 text-sm focus:ring-2 focus:ring-blue-500" aria-required="true">
                    </div>
                    <div class="space-y-1">
                        <label for="rule_input_upper" class="block text-sm font-bold text-gray-700">Upper Limit <span class="text-red-500">*</span></label>
                        <input type="number" step="0.1" id="rule_input_upper" required value="28" class="w-full p-3.5 bg-gray-100 border border-gray-200 rounded-lg outline-none font-bold text-red-600 text-sm focus:ring-2 focus:ring-blue-500" aria-required="true">
                    </div>
                </div>

                <div class="bg-orange-50 p-4 rounded-xl border border-orange-200">
                    <p class="text-xs text-gray-700"><span class="text-orange-600 font-bold">Smart Alerts:</span> The system will automatically generate 4 rules (Warning & Critical bounds).</p>
                </div>

                <div class="flex gap-4 pt-2">
                    <button type="button" onclick="rule_closeModal()" class="flex-1 bg-gray-200 text-gray-800 py-3.5 rounded-xl font-bold hover:bg-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300">Cancel</button>
                    <button type="submit" id="rule_btn_submit" class="flex-1 bg-blue-600 text-white py-3.5 rounded-xl font-bold hover:bg-blue-700 shadow-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-60 disabled:cursor-not-allowed">Add Rules</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="lab_toast_container" class="fixed top-4 right-4 z-[10000] flex flex-col gap-2 pointer-events-none" aria-live="polite"></div>

<style>
    .lab-skeleton { background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: lab-shimmer 1.5s infinite; border-radius: 4px; }
    @keyframes lab-shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
    .lab-toast { animation: lab-slide-in 0.3s ease-out forwards; pointer-events: auto; }
    .lab-toast.removing { animation: lab-slide-out 0.3s ease-in forwards; }
    @keyframes lab-slide-in { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @keyframes lab-slide-out { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
</style>

<script>
    let lab_data = [];
    let lab_users = [];
    let lab_editMode = false;
    let lab_searchQuery = '';
    let lab_isLoading = false;
    let lab_previousFocus = null;

    let rule_activeLabId = null;
    let rule_activeLabName = '';
    let rule_data = [];
    let rule_isLoading = false;
    let rule_previousFocus = null;

    function lab_escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    function lab_showToast(message, type = 'success') {
        const container = document.getElementById('lab_toast_container');
        const toast = document.createElement('div');
        const colors = { success: 'bg-green-50 border-green-300 text-green-800', error: 'bg-red-50 border-red-300 text-red-800', info: 'bg-blue-50 border-blue-300 text-blue-800' };
        const icons = { success: 'check-circle', error: 'alert-circle', info: 'info' };
        toast.className = `lab-toast flex items-center gap-3 px-4 py-3 rounded-lg border shadow-lg ${colors[type]} text-sm font-medium min-w-[280px]`;
        toast.innerHTML = `<i data-lucide="${icons[type]}" class="w-5 h-5 flex-shrink-0"></i><span class="flex-1">${lab_escapeHtml(message)}</span><button onclick="this.parentElement.classList.add('removing'); setTimeout(() => this.parentElement.remove(), 300)" class="flex-shrink-0 hover:opacity-70" aria-label="Dismiss"><i data-lucide="x" class="w-4 h-4"></i></button>`;
        container.appendChild(toast);
        if (typeof lucide !== 'undefined') lucide.createIcons();
        setTimeout(() => { if (toast.parentElement) { toast.classList.add('removing'); setTimeout(() => toast.remove(), 300); } }, 4000);
    }

    function lab_renderSkeleton() {
        const grid = document.getElementById('lab_grid');
        grid.innerHTML = Array(6).fill(`
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-gray-200 rounded-xl lab-skeleton"></div>
                    <div class="flex gap-2"><div class="w-8 h-8 bg-gray-200 rounded-lg lab-skeleton"></div><div class="w-8 h-8 bg-gray-200 rounded-lg lab-skeleton"></div></div>
                </div>
                <div class="w-3/4 h-6 bg-gray-200 rounded mb-4 lab-skeleton"></div>
                <div class="space-y-2 mb-4">
                    <div class="w-1/2 h-4 bg-gray-200 rounded lab-skeleton"></div>
                    <div class="w-full h-10 bg-gray-200 rounded lab-skeleton"></div>
                </div>
                <div class="border-t border-gray-50 pt-4 flex justify-between items-center">
                    <div class="w-20 h-8 bg-gray-200 rounded lab-skeleton"></div>
                    <div class="w-28 h-8 bg-gray-200 rounded lab-skeleton"></div>
                </div>
            </div>
        `).join('');
    }

    function lab_renderEmpty() {
        const grid = document.getElementById('lab_grid');
        grid.innerHTML = `
            <div class="col-span-full flex flex-col items-center justify-center py-12 px-4 text-center">
                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-4">
                    <i data-lucide="building-2" class="w-8 h-8 text-blue-300"></i>
                </div>
                <p class="text-gray-700 font-semibold mb-1">${lab_searchQuery ? 'No labs match your search' : 'No labs configured yet'}</p>
                <p class="text-gray-500 text-sm mb-4">${lab_searchQuery ? 'Try adjusting your search terms.' : 'Get started by adding your first lab.'}</p>
                ${!lab_searchQuery ? `<button onclick="lab_openModal()" class="bg-[#6B6565] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#5a5454] transition-colors flex items-center gap-2"><i data-lucide="plus-square" class="w-4 h-4"></i> Add New Lab</button>` : ''}
            </div>
        `;
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    async function lab_fetchUsers() {
        try {
            const res = await fetch('/api/users');
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            lab_users = await res.json();
        } catch (error) { console.error("User fetch error", error); }
    }

    async function lab_fetchLabs() {
        lab_isLoading = true;
        lab_renderSkeleton();
        try {
            const res = await fetch(`/api/labs/detail`);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            lab_data = await res.json();
            lab_renderGrid();
        } catch (error) { 
            console.error("Lab fetch error", error); 
            document.getElementById('lab_grid').innerHTML = `<p class="text-red-500 italic col-span-full text-center py-8">Failed to load labs. Please try again.</p>`;
        } finally {
            lab_isLoading = false;
        }
    }

    function lab_handleSearch(value) {
        lab_searchQuery = value.trim();
        document.getElementById('lab_clear_search').classList.toggle('hidden', !value);
        document.getElementById('lab_showing_info').textContent = `${lab_data.filter(l => l.lab_name.toLowerCase().includes(lab_searchQuery)).length} labs${lab_searchQuery ? ' found' : ''}`;
        lab_renderGrid();
    }

    function lab_clearSearch() {
        document.getElementById('lab_search').value = '';
        lab_handleSearch('');
    }

    function lab_renderGrid() {
        const term = lab_searchQuery.toLowerCase();
        const grid = document.getElementById('lab_grid');
        const filtered = lab_data.filter(l => l.lab_name.toLowerCase().includes(term));

        if (filtered.length === 0) {
            lab_renderEmpty();
            return;
        }

        grid.innerHTML = filtered.map(lab => `
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 hover:shadow-xl transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl"><i data-lucide="building-2" class="w-6 h-6"></i></div>
                    <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button onclick="lab_edit('${lab.lab_id}')" class="p-2 hover:bg-blue-50 rounded-lg text-gray-400 hover:text-blue-600 transition-colors" aria-label="Edit ${lab_escapeHtml(lab.lab_name)}"><i data-lucide="edit" class="w-4 h-4"></i></button>
                        <button onclick="lab_delete('${lab.lab_id}', '${lab_escapeHtml(lab.lab_name)}')" class="p-2 hover:bg-red-50 rounded-lg text-gray-400 hover:text-red-600 transition-colors" aria-label="Delete ${lab_escapeHtml(lab.lab_name)}"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-1 truncate" title="${lab_escapeHtml(lab.lab_name)}">${lab_escapeHtml(lab.lab_name)}</h3>
                <div class="space-y-2 mb-4 mt-3">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                        <span class="font-medium">PIC: <span class="text-gray-900">${lab_escapeHtml(lab.pic_name || 'Unassigned')}</span></span>
                    </div>
                    <p class="text-sm text-gray-500 italic line-clamp-2 h-10">${lab_escapeHtml(lab.description) || 'No description'}</p>
                </div>
                <div class="border-t border-gray-50 pt-4 flex justify-between items-center mt-2">
                    <div class="flex flex-col"><span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Active Rules</span><span class="font-bold text-blue-600 text-sm">${lab.rules_count || 0} Rules</span></div>
                    <button onclick="rule_openView('${lab.lab_id}', '${lab_escapeHtml(lab.lab_name)}', '${lab_escapeHtml(lab.pic_name)}')" class="flex items-center gap-1 text-xs font-bold text-gray-700 hover:text-blue-600 bg-white px-3 py-1.5 rounded-lg border border-gray-200 hover:border-blue-300 shadow-sm transition" aria-label="Manage rules for ${lab_escapeHtml(lab.lab_name)}"><i data-lucide="settings-2" class="w-4 h-4"></i> Manage Rules</button>
                </div>
            </div>
        `).join('');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    async function lab_openModal() {
        lab_editMode = false;
        document.getElementById('lab_form').reset();
        document.getElementById('lab_input_id').value = '';
        document.querySelectorAll('[id$="_error"]').forEach(el => el.classList.add('hidden'));
        
        if (lab_users.length === 0) await lab_fetchUsers();
        const picSelect = document.getElementById('lab_input_pic');
        picSelect.innerHTML = `<option value="" disabled selected>Select PIC...</option>` + 
            lab_users.map(u => `<option value="${lab_escapeHtml(u.username)}">${lab_escapeHtml(u.username)} (${lab_escapeHtml(u.role)})</option>`).join('');

        document.getElementById('lab_modal_title').innerText = "Add New Lab";
        lab_previousFocus = document.activeElement;
        document.getElementById('lab_modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('lab_input_name').focus(), 100);
    }

    async function lab_edit(id) {
        lab_editMode = true;
        const lab = lab_data.find(l => l.lab_id == id);
        if(!lab) return;

        if (lab_users.length === 0) await lab_fetchUsers();
        const picSelect = document.getElementById('lab_input_pic');
        picSelect.innerHTML = `<option value="" disabled>Select PIC...</option>` + 
            lab_users.map(u => `<option value="${lab_escapeHtml(u.username)}" ${u.username === lab.pic_name ? 'selected' : ''}>${lab_escapeHtml(u.username)} (${lab_escapeHtml(u.role)})</option>`).join('');

        document.getElementById('lab_input_id').value = lab.lab_id;
        document.getElementById('lab_input_name').value = lab.lab_name;
        document.getElementById('lab_input_desc').value = lab.description;
        document.querySelectorAll('[id$="_error"]').forEach(el => el.classList.add('hidden'));
        
        document.getElementById('lab_modal_title').innerText = "Edit Lab";
        lab_previousFocus = document.activeElement;
        document.getElementById('lab_modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('lab_input_name').focus(), 100);
    }

    function lab_closeModal() { 
        document.getElementById('lab_modal').classList.add('hidden'); 
        document.body.style.overflow = '';
        if (lab_previousFocus) { lab_previousFocus.focus(); lab_previousFocus = null; }
    }

    async function lab_submitForm(e) {
        e.preventDefault();
        const name = document.getElementById('lab_input_name').value.trim();
        const pic = document.getElementById('lab_input_pic').value;
        
        let isValid = true;
        if (!name) {
            document.getElementById('lab_name_error').textContent = 'Lab name is required';
            document.getElementById('lab_name_error').classList.remove('hidden');
            isValid = false;
        } else { document.getElementById('lab_name_error').classList.add('hidden'); }
        
        if (!pic) {
            document.getElementById('lab_pic_error').textContent = 'PIC is required';
            document.getElementById('lab_pic_error').classList.remove('hidden');
            isValid = false;
        } else { document.getElementById('lab_pic_error').classList.add('hidden'); }

        if (!isValid) return;

        const btn = document.querySelector('#lab_form button[type="submit"]');
        const originalText = btn.innerText;
        btn.innerHTML = '<span class="inline-flex items-center gap-2"><svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity="0.3"/><path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg> Processing...</span>';
        btn.disabled = true;

        const id = document.getElementById('lab_input_id').value;
        const payload = { lab_name: name, pic_name: pic, description: document.getElementById('lab_input_desc').value.trim() };
        const url = lab_editMode ? `/api/labs/${id}` : '/api/labs';
        
        try {
            const res = await fetch(url, { method: lab_editMode ? 'PUT' : 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(payload) });
            if (res.ok) {
                lab_closeModal();
                lab_fetchLabs();
                lab_showToast(`Lab ${lab_editMode ? 'updated' : 'created'} successfully!`, 'success');
            } else {
                const err = await res.json().catch(() => ({}));
                lab_showToast(err.message || 'Failed to save lab.', 'error');
            }
        } catch (error) {
            lab_showToast('Network connection failed.', 'error');
        } finally {
            btn.innerText = originalText;
            btn.disabled = false;
        }
    }

    function lab_delete(id, name) {
        Swal.fire({ 
            title: 'Delete Lab?', 
            html: `Are you sure you want to remove <strong>${lab_escapeHtml(name)}</strong>?<br><span class="text-sm text-gray-500">This action cannot be undone.</span>`, 
            icon: 'warning', 
            showCancelButton: true, 
            confirmButtonColor: '#E11D48',
            confirmButtonText: 'Yes, delete'
        }).then(async (res) => {
            if(res.isConfirmed) {
                try {
                    const response = await fetch(`/api/labs/${id}`, { method: 'DELETE' });
                    if (response.ok) {
                        lab_fetchLabs();
                        lab_showToast(`${name} has been deleted`, 'success');
                    } else {
                        lab_showToast('Failed to delete lab.', 'error');
                    }
                } catch (error) {
                    lab_showToast('Network error. Please try again.', 'error');
                }
            }
        });
    }

    document.addEventListener('keydown', (e) => {
        const labModal = document.getElementById('lab_modal');
        if (!labModal.classList.contains('hidden')) {
            if (e.key === 'Escape') { lab_closeModal(); return; }
            if (e.key === 'Tab') {
                const focusable = labModal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                const first = focusable[0], last = focusable[focusable.length - 1];
                if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
                else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
            }
        }
        const ruleModal = document.getElementById('rule_modal');
        if (!ruleModal.classList.contains('hidden')) {
            if (e.key === 'Escape') { rule_closeModal(); return; }
            if (e.key === 'Tab') {
                const focusable = ruleModal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                const first = focusable[0], last = focusable[focusable.length - 1];
                if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
                else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
            }
        }
    });

    async function rule_openView(labId, labName, picName) {
        rule_activeLabId = labId;
        rule_activeLabName = labName;
        document.getElementById('rule_header_lab').innerText = labName;
        document.getElementById('rule_header_pic').innerText = picName || '-';
        
        document.getElementById('lab_main_view').classList.add('hidden');
        document.getElementById('lab_rules_view').classList.remove('hidden');
        await rule_fetch();
    }

    function rule_closeView() {
        document.getElementById('lab_rules_view').classList.add('hidden');
        document.getElementById('lab_main_view').classList.remove('hidden');
        lab_fetchLabs();
    }

    async function rule_fetch() {
        rule_isLoading = true;
        document.getElementById('rule_table_body').innerHTML = `<tr><td colspan="5" class="text-center p-6 text-gray-500 italic">Loading rules...</td></tr>`;
        try {
            const res = await fetch(`/api/alerts/lab/${rule_activeLabId}/rules`);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            rule_data = await res.json();
            rule_renderTable();
        } catch (error) { 
            console.error(error); 
            document.getElementById('rule_table_body').innerHTML = `<tr><td colspan="5" class="text-center p-6 text-red-500">Failed to load rules.</td></tr>`;
        } finally {
            rule_isLoading = false;
        }
    }

    function rule_getRangeStr(ruleArray) {
        if (ruleArray.length === 0) return 'Not Set';
        let min = null, max = null;
        ruleArray.forEach(r => {
            const val = parseFloat(r.threshold_value);
            if (['<', '<='].includes(r.operator) && (min === null || val > min)) min = val;
            if (['>', '>='].includes(r.operator) && (max === null || val < max)) max = val;
        });
        if (min !== null && max !== null) return `${min} - ${max}`;
        if (min !== null) return `> ${min}`;
        if (max !== null) return `< ${max}`;
        return 'Not Set';
    }

    function rule_renderTable() {
        const tempCrits = rule_data.filter(r => r.parameter === 'temperature' && r.severity === 'critical');
        const humCrits = rule_data.filter(r => r.parameter === 'humidity' && r.severity === 'critical');
        
        const tempStr = rule_getRangeStr(tempCrits);
        const humStr = rule_getRangeStr(humCrits);
        document.getElementById('rule_target_temp').innerText = tempStr !== 'Not Set' ? `${tempStr}°C` : 'Not Set';
        document.getElementById('rule_target_hum').innerText = humStr !== 'Not Set' ? `${humStr}%` : 'Not Set';

        const tbody = document.getElementById('rule_table_body');
        const mobile = document.getElementById('rule_mobile_cards');

        if (rule_data.length === 0) {
            const emptyHTML = `
                <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                    <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-4">
                        <i data-lucide="shield-alert" class="w-8 h-8 text-blue-300"></i>
                    </div>
                    <p class="text-gray-700 font-semibold mb-1">No rules configured</p>
                    <p class="text-gray-500 text-sm mb-4">Get started by adding monitoring thresholds for devices in this lab.</p>
                    <button onclick="rule_openModal()" class="bg-[#3B82F6] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors flex items-center gap-2"><i data-lucide="plus-circle" class="w-4 h-4"></i> Add New Rule</button>
                </div>`;
            tbody.innerHTML = `<tr><td colspan="5">${emptyHTML}</td></tr>`;
            mobile.innerHTML = emptyHTML;
            if (typeof lucide !== 'undefined') lucide.createIcons();
            return;
        }

        tbody.innerHTML = rule_data.map((r, i) => `
            <tr class="${i % 2 === 0 ? 'bg-white' : 'bg-[#EFF6FF]'} hover:bg-blue-100/40 transition-colors group">
                <td class="p-4 border-b border-blue-50 font-bold text-gray-800 text-sm">${lab_escapeHtml(r.device_id)}<div class="text-xs text-gray-500 font-normal">${lab_escapeHtml(r.device_name || '-')}</div></td>
                <td class="p-4 border-b border-blue-50 capitalize text-sm font-medium text-gray-700">${lab_escapeHtml(r.parameter)}</td>
                <td class="p-4 border-b border-blue-50"><span class="font-bold text-gray-900 bg-gray-100 px-3 py-1 rounded-lg border border-gray-200 text-xs">${lab_escapeHtml(r.operator)} ${lab_escapeHtml(r.threshold_value)} ${r.parameter==='temperature'?'°C':'%'}</span></td>
                <td class="p-4 border-b border-blue-50 text-center"><span class="px-2 py-1 rounded-full text-[10px] font-black uppercase tracking-wide ${r.severity === 'critical' ? 'bg-red-500 text-white' : 'bg-amber-500 text-white'}">${lab_escapeHtml(r.severity)}</span></td>
                <td class="p-4 border-b border-blue-50 text-center">
                    <div class="flex items-center justify-center gap-1 opacity-70 group-hover:opacity-100 transition-opacity">
                        <button onclick="rule_edit('${r.device_id}', '${r.parameter}')" class="p-1.5 bg-[#3B82F6] text-white rounded-lg hover:bg-blue-700 shadow-sm transition-all hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-300" aria-label="Edit rule"><i data-lucide="edit" class="w-3.5 h-3.5"></i></button>
                        <button onclick="rule_delete('${r.rule_id}')" class="p-1.5 bg-[#E11D48] text-white rounded-lg hover:bg-red-700 shadow-sm transition-all hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-300" aria-label="Delete rule"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                    </div>
                </td>
            </tr>
        `).join('');

        mobile.innerHTML = rule_data.map((r) => `
            <div class="p-4">
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <div class="font-bold text-gray-900 text-sm">${lab_escapeHtml(r.device_id)}</div>
                        <div class="text-xs text-gray-500">${lab_escapeHtml(r.device_name || '-')}</div>
                    </div>
                    <span class="px-2 py-1 rounded-full text-[10px] font-black uppercase tracking-wide ${r.severity === 'critical' ? 'bg-red-500 text-white' : 'bg-amber-500 text-white'}">${lab_escapeHtml(r.severity)}</span>
                </div>
                <div class="flex items-center justify-between mb-3">
                    <div><div class="text-xs text-gray-500 uppercase font-bold">Parameter</div><div class="text-sm font-medium text-gray-800 capitalize">${lab_escapeHtml(r.parameter)}</div></div>
                    <div class="text-right"><div class="text-xs text-gray-500 uppercase font-bold">Condition</div><div class="text-sm font-bold text-gray-900 bg-gray-100 px-2 py-1 rounded border border-gray-200">${lab_escapeHtml(r.operator)} ${lab_escapeHtml(r.threshold_value)} ${r.parameter==='temperature'?'°C':'%'}</div></div>
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                    <button onclick="rule_edit('${r.device_id}', '${r.parameter}')" class="p-1.5 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200" aria-label="Edit"><i data-lucide="edit" class="w-3.5 h-3.5"></i></button>
                    <button onclick="rule_delete('${r.rule_id}')" class="p-1.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-200" aria-label="Delete"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                </div>
            </div>
        `).join('');

        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    async function rule_openModal() {
        document.getElementById('rule_form').reset();
        document.getElementById('rule_edit_mode').value = '';
        document.getElementById('rule_modal_title').innerText = "Add New Rule";
        document.getElementById('rule_btn_submit').innerText = "Add Rules";
        document.getElementById('rule_overwrite_warn').classList.add('hidden');
        document.getElementById('rule_input_param').disabled = false;
        document.getElementById('rule_input_device').disabled = false;
        document.getElementById('rule_input_device').classList.remove('bg-gray-200', 'text-gray-500');
        
        await rule_populateDevices();
        rule_previousFocus = document.activeElement;
        document.getElementById('rule_modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('rule_input_param').focus(), 100);
    }

    async function rule_populateDevices(editDeviceId = null, editParam = null) {
        try {
            const res = await fetch(`/api/devices`);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const allDev = await res.json();
            const labDevices = allDev.filter(d => d.location === rule_activeLabName);
            const param = editParam || document.getElementById('rule_input_param').value;
            const select = document.getElementById('rule_input_device');
            
            let available = labDevices;
            if (!editDeviceId) {
                available = labDevices.filter(dev => !rule_data.some(r => r.device_id === dev.device_id && r.parameter === param));
            }

            if (available.length === 0) {
                select.innerHTML = '<option value="" disabled selected>No available devices</option>';
                document.getElementById('rule_no_device_warn').classList.remove('hidden');
                document.getElementById('rule_btn_submit').disabled = true;
            } else {
                select.innerHTML = available.map(d => `<option value="${lab_escapeHtml(d.device_id)}">${lab_escapeHtml(d.device_id)}</option>`).join('');
                document.getElementById('rule_no_device_warn').classList.add('hidden');
                document.getElementById('rule_btn_submit').disabled = false;
                if (editDeviceId) select.value = editDeviceId;
            }
        } catch (error) {
            console.error("Failed to fetch devices for rules", error);
            lab_showToast('Failed to load devices', 'error');
        }
    }

    function rule_handleParamChange() {
        const param = document.getElementById('rule_input_param').value;
        document.getElementById('rule_input_lower').value = param === 'temperature' ? '18' : '45';
        document.getElementById('rule_input_upper').value = param === 'temperature' ? '28' : '75';
        if (!document.getElementById('rule_edit_mode').value) rule_populateDevices();
    }

    async function rule_edit(deviceId, param) {
        document.getElementById('rule_edit_mode').value = '1';
        document.getElementById('rule_modal_title').innerText = "Configure Rule Bounds";
        document.getElementById('rule_btn_submit').innerText = "Apply Overwrite";
        document.getElementById('rule_overwrite_warn').classList.remove('hidden');
        
        document.getElementById('rule_input_param').value = param;
        document.getElementById('rule_input_param').disabled = true;
        
        await rule_populateDevices(deviceId, param);
        document.getElementById('rule_input_device').disabled = true;
        document.getElementById('rule_input_device').classList.add('bg-gray-200', 'text-gray-500');
        
        rule_previousFocus = document.activeElement;
        document.getElementById('rule_modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function rule_closeModal() { 
        document.getElementById('rule_modal').classList.add('hidden'); 
        document.body.style.overflow = '';
        if (rule_previousFocus) { rule_previousFocus.focus(); rule_previousFocus = null; }
    }

    async function rule_submitForm(e) {
        e.preventDefault();
        const low = parseFloat(document.getElementById('rule_input_lower').value);
        const up = parseFloat(document.getElementById('rule_input_upper').value);
        if (low >= up) { 
            lab_showToast('Lower limit must be less than upper limit.', 'error'); 
            return; 
        }

        const payload = {
            deviceId: document.getElementById('rule_input_device').value,
            parameter: document.getElementById('rule_input_param').value,
            lowerLimit: low, upperLimit: up
        };

        const btn = document.getElementById('rule_btn_submit');
        const originalText = btn.innerText;
        btn.innerHTML = '<span class="inline-flex items-center gap-2"><svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity="0.3"/><path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg> Processing...</span>';
        btn.disabled = true;

        try {
            const res = await fetch(`/api/alerts/rules`, { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(payload) });
            if (res.ok) {
                rule_closeModal();
                rule_fetch();
                lab_showToast('Rules saved successfully!', 'success');
            } else {
                const err = await res.json().catch(() => ({}));
                lab_showToast(err.message || 'Could not save rules.', 'error');
            }
        } catch (error) { 
            console.error(error); 
            lab_showToast('Network connection failed.', 'error');
        } finally {
            btn.innerText = originalText;
            btn.disabled = false;
        }
    }

    function rule_delete(id) {
        Swal.fire({ 
            title: 'Delete Rule?', 
            text: 'This action cannot be undone.', 
            icon: 'warning', 
            showCancelButton: true, 
            confirmButtonColor: '#E11D48',
            confirmButtonText: 'Yes, delete'
        }).then(async (res) => {
            if(res.isConfirmed) {
                try {
                    const response = await fetch(`/api/alerts/rules/${id}`, { method: 'DELETE' });
                    if (response.ok) {
                        rule_fetch();
                        lab_showToast('Rule deleted successfully', 'success');
                    } else {
                        lab_showToast('Failed to delete rule.', 'error');
                    }
                } catch (error) {
                    lab_showToast('Network error. Please try again.', 'error');
                }
            }
        });
    }

    if(document.getElementById('lab_grid')) lab_fetchLabs();
</script>