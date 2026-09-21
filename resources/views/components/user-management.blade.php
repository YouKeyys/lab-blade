<div class="flex-1 min-h-full relative block animate-in fade-in duration-300">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">User Management</h1>
            <p class="text-gray-500 mt-1 text-base">Manage User Access and Roles</p>
        </div>
        <button onclick="usr_openModal()" class="bg-[#6B6565] text-white px-6 py-2.5 rounded-lg hover:bg-[#5a5454] flex items-center gap-2 shadow-md transition-all active:scale-95 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#6B6565]" aria-label="Add new user">
            <i data-lucide="user-plus" class="w-5 h-5" aria-hidden="true"></i> Add User
        </button>
    </div>

    <!-- Toolbar: Search + Bulk Actions + Pagination Info -->
    <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3 mb-4">
        <div class="relative flex-1 max-w-md">
            <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" aria-hidden="true"></i>
            <input 
                type="search" 
                id="usr_search" 
                oninput="usr_handleSearch(this.value)" 
                placeholder="Search by name, email, or role..." 
                class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all"
                aria-label="Search users"
            >
            <button id="usr_clear_search" onclick="usr_clearSearch()" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" aria-label="Clear search">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="flex items-center gap-2">
            <div id="usr_bulk_actions" class="hidden items-center gap-2">
                <span id="usr_selected_count" class="text-sm text-gray-600 font-medium whitespace-nowrap"></span>
                <button onclick="usr_bulkDelete()" class="bg-red-50 text-red-600 border border-red-200 px-3 py-2 rounded-lg text-xs font-semibold hover:bg-red-100 transition-all flex items-center gap-1.5 focus:outline-none focus:ring-2 focus:ring-red-300" aria-label="Delete selected users">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5" aria-hidden="true"></i> Delete Selected
                </button>
            </div>
            <div class="text-sm text-gray-500 whitespace-nowrap">
                <span id="usr_showing_info">0 users</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-blue-200 overflow-hidden outline outline-1 outline-blue-100">
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse" role="grid" aria-label="Users table">
                <thead>
                    <tr class="bg-[#DBEAFE] text-gray-800 font-bold uppercase text-xs tracking-widest">
                        <th class="p-4 border-b border-blue-200 text-center w-12">
                            <input type="checkbox" id="usr_select_all" onchange="usr_toggleSelectAll(this.checked)" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer" aria-label="Select all users">
                        </th>
                        <th class="p-4 border-b border-blue-200 text-center w-12">No</th>
                        <th class="p-4 border-b border-blue-200 cursor-pointer hover:bg-blue-300/50 transition-colors select-none" onclick="usr_requestSort('username')">
                            <span class="inline-flex items-center gap-1.5">
                                Username
                                <span class="usr_sort_icon" data-key="username"></span>
                            </span>
                        </th>
                        <th class="p-4 border-b border-blue-200 cursor-pointer hover:bg-blue-300/50 transition-colors select-none" onclick="usr_requestSort('role')">
                            <span class="inline-flex items-center gap-1.5">
                                Role
                                <span class="usr_sort_icon" data-key="role"></span>
                            </span>
                        </th>
                        <th class="p-4 border-b border-blue-200">Assigned Labs</th>
                        <th class="p-4 border-b border-blue-200 text-center cursor-pointer hover:bg-blue-300/50 transition-colors select-none" onclick="usr_requestSort('last_login')">
                            <span class="inline-flex items-center gap-1.5">
                                Last Login
                                <span class="usr_sort_icon" data-key="last_login"></span>
                            </span>
                        </th>
                        <th class="p-4 border-b border-blue-200 text-center cursor-pointer hover:bg-blue-300/50 transition-colors select-none" onclick="usr_requestSort('created_at')">
                            <span class="inline-flex items-center gap-1.5">
                                Created At
                                <span class="usr_sort_icon" data-key="created_at"></span>
                            </span>
                        </th>
                        <th class="p-4 border-b border-blue-200 text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="usr_table_body">
                    <tr><td colspan="8" class="text-center p-6 text-gray-500 italic">Loading users...</td></tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div id="usr_mobile_cards" class="md:hidden divide-y divide-blue-100"></div>

        <!-- Pagination -->
        <div id="usr_pagination" class="hidden flex items-center justify-between px-6 py-3 bg-gray-50 border-t border-blue-100">
            <div class="text-sm text-gray-500" id="usr_page_info"></div>
            <div class="flex items-center gap-1">
                <button onclick="usr_changePage(-1)" id="usr_prev_btn" class="p-2 rounded-lg hover:bg-blue-100 disabled:opacity-40 disabled:cursor-not-allowed transition-colors" aria-label="Previous page">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </button>
                <div id="usr_page_numbers" class="flex items-center gap-1"></div>
                <button onclick="usr_changePage(1)" id="usr_next_btn" class="p-2 rounded-lg hover:bg-blue-100 disabled:opacity-40 disabled:cursor-not-allowed transition-colors" aria-label="Next page">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- USER MODAL (Add/Edit) -->
<div id="usr_modal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true" aria-labelledby="usr_modal_title">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="usr_closeModal()"></div>
    <div class="relative bg-white w-full max-w-xl max-h-[90vh] overflow-y-auto rounded-2xl shadow-2xl border border-gray-300 font-sans scrollbar-hide transform transition-all" role="document">
        <div class="p-8 sm:p-10 text-gray-900">
            <button onclick="usr_closeModal()" class="absolute top-4 right-4 p-2 rounded-full hover:bg-gray-100 transition-colors text-gray-400 hover:text-gray-600" aria-label="Close modal">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <h2 id="usr_modal_title" class="text-2xl sm:text-3xl font-bold text-center mb-8 tracking-tight">Add New User</h2>
            
            <form id="usr_form" class="space-y-5" autocomplete="off" onsubmit="usr_submitForm(event)" novalidate>
                <input type="hidden" id="usr_input_id">
                
                <div class="space-y-2">
                    <label for="usr_input_name" class="block text-sm font-bold text-gray-700 ml-1">Full Name (Username) <span class="text-red-500">*</span></label>
                    <input type="text" id="usr_input_name" required class="w-full p-4 bg-[#E5E5E5] border-none rounded-xl outline-none focus:ring-4 focus:ring-blue-500/20 transition-all text-sm" placeholder="Full Name / Username" aria-required="true">
                    <p id="usr_name_error" class="hidden text-xs text-red-500 ml-1"></p>
                </div>

                <div class="space-y-2">
                    <label for="usr_input_email" class="block text-sm font-bold text-gray-700 ml-1">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" id="usr_input_email" required class="w-full p-4 bg-[#E5E5E5] border-none rounded-xl outline-none focus:ring-4 focus:ring-blue-500/20 transition-all text-sm" placeholder="Email Address" aria-required="true">
                    <p id="usr_email_error" class="hidden text-xs text-red-500 ml-1"></p>
                </div>

                <div class="space-y-2">
                    <label for="usr_input_role" class="block text-sm font-bold text-gray-700 ml-1">Role <span class="text-red-500">*</span></label>
                    <select id="usr_input_role" required onchange="usr_handleRoleChange()" class="w-full p-4 bg-[#E5E5E5] border-none rounded-xl outline-none focus:ring-4 focus:ring-blue-500/20 cursor-pointer transition-all text-sm" aria-required="true">
                        <option value="" disabled selected>Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="supervisor">Supervisor</option>
                    </select>
                </div>

                <!-- ASSIGNED LABS SECTION (Hidden by default) -->
                <div id="usr_lab_assignment_section" class="space-y-3 p-4 bg-blue-50/50 rounded-xl border border-blue-100 hidden animate-in fade-in slide-in-from-top-2 duration-200">
                    <label class="block text-sm font-bold text-gray-700 ml-1">Assigned Labs</label>
                    
                    <div id="usr_assigned_badges" class="flex flex-wrap gap-2 mb-2" role="list" aria-label="Assigned labs"></div>

                    <label for="usr_lab_select" class="sr-only">Select a lab to assign</label>
                    <select id="usr_lab_select" onchange="usr_addLab(this)" class="w-full p-3 bg-white border border-gray-300 rounded-lg outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 cursor-pointer transition-all text-sm">
                        <option value="" disabled selected>+ Add Lab to assignment...</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="usr_input_pwd" id="usr_pwd_label" class="block text-sm font-bold text-gray-700 ml-1">Set Password <span id="usr_pwd_required" class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" id="usr_input_pwd" class="w-full p-4 pr-12 bg-[#E5E5E5] border-none rounded-xl outline-none focus:ring-4 focus:ring-blue-500/20 transition-all text-sm" placeholder="Password">
                        <button type="button" onclick="usr_togglePwd()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded" aria-label="Toggle password visibility">
                            <i data-lucide="eye" id="usr_pwd_icon" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <!-- Password Strength Indicator -->
                    <div id="usr_pwd_strength" class="hidden">
                        <div class="flex gap-1 mb-1">
                            <div id="usr_str_1" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors"></div>
                            <div id="usr_str_2" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors"></div>
                            <div id="usr_str_3" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors"></div>
                            <div id="usr_str_4" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors"></div>
                        </div>
                        <p id="usr_str_text" class="text-xs text-gray-500 ml-1"></p>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4">
                    <button type="button" onclick="usr_closeModal()" class="bg-[#C24444] text-white px-8 py-3 rounded-xl font-bold text-sm hover:bg-red-800 transition-all shadow-lg active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Cancel</button>
                    <button id="usr_btn_submit" type="submit" class="bg-[#3B82F6] text-white px-8 py-3 rounded-xl font-bold text-sm hover:bg-blue-700 transition-all shadow-lg active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-60 disabled:cursor-not-allowed">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="usr_toast_container" class="fixed top-4 right-4 z-[10000] flex flex-col gap-2 pointer-events-none" aria-live="polite"></div>

<style>
    .usr_sort_icon::after {
        content: '↕';
        font-size: 10px;
        opacity: 0.4;
    }
    .usr_sort_icon.asc::after {
        content: '↑';
        opacity: 1;
        color: #1e40af;
    }
    .usr_sort_icon.desc::after {
        content: '↓';
        opacity: 1;
        color: #1e40af;
    }
    .usr-skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: usr-shimmer 1.5s infinite;
        border-radius: 4px;
    }
    @keyframes usr-shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    .usr-toast {
        animation: usr-slide-in 0.3s ease-out forwards;
        pointer-events: auto;
    }
    .usr-toast.removing {
        animation: usr-slide-out 0.3s ease-in forwards;
    }
    @keyframes usr-slide-in {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes usr-slide-out {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
</style>

<script>
    let usr_data = [];
    let usr_sort = { key: null, direction: 'asc' };
    let usr_editMode = false;
    
    // States for Lab Assignment
    let usr_availableLabs = [];
    let usr_assignedLabs = [];

    // Search & Pagination
    let usr_searchQuery = '';
    let usr_currentPage = 1;
    let usr_perPage = 10;
    let usr_selectedUsers = new Set();
    let usr_isLoading = false;

    // --- Utility: HTML Escaping (XSS Prevention) ---
    function usr_escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    // --- Utility: Generate Avatar Initials ---
    function usr_getInitials(name) {
        if (!name) return '?';
        return name.split(/\s+/).map(w => w[0]).slice(0, 2).join('').toUpperCase();
    }

    function usr_getAvatarColor(name) {
        const colors = ['#6366f1','#8b5cf6','#ec4899','#f43f5e','#f97316','#eab308','#22c55e','#14b8a6','#06b6d4','#3b82f6'];
        let hash = 0;
        for (let i = 0; i < (name || '').length; i++) hash = name.charCodeAt(i) + ((hash << 5) - hash);
        return colors[Math.abs(hash) % colors.length];
    }

    // --- Utility: Toast Notifications ---
    function usr_showToast(message, type = 'success') {
        const container = document.getElementById('usr_toast_container');
        const toast = document.createElement('div');
        const colors = {
            success: 'bg-green-50 border-green-300 text-green-800',
            error: 'bg-red-50 border-red-300 text-red-800',
            info: 'bg-blue-50 border-blue-300 text-blue-800'
        };
        const icons = { success: 'check-circle', error: 'alert-circle', info: 'info' };
        
        toast.className = `usr-toast flex items-center gap-3 px-4 py-3 rounded-lg border shadow-lg ${colors[type]} text-sm font-medium min-w-[280px]`;
        toast.innerHTML = `
            <i data-lucide="${icons[type]}" class="w-5 h-5 flex-shrink-0"></i>
            <span class="flex-1">${usr_escapeHtml(message)}</span>
            <button onclick="this.parentElement.classList.add('removing'); setTimeout(() => this.parentElement.remove(), 300)" class="flex-shrink-0 hover:opacity-70" aria-label="Dismiss">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        `;
        container.appendChild(toast);
        lucide.createIcons();
        setTimeout(() => {
            if (toast.parentElement) {
                toast.classList.add('removing');
                setTimeout(() => toast.remove(), 300);
            }
        }, 4000);
    }

    // --- Utility: Password Strength ---
    function usr_checkPasswordStrength(pwd) {
        let score = 0;
        if (pwd.length >= 8) score++;
        if (/[a-z]/.test(pwd) && /[A-Z]/.test(pwd)) score++;
        if (/\d/.test(pwd)) score++;
        if (/[^a-zA-Z0-9]/.test(pwd)) score++;
        return score;
    }

    function usr_updatePasswordStrength() {
        const pwd = document.getElementById('usr_input_pwd').value;
        const strengthEl = document.getElementById('usr_pwd_strength');
        
        if (!pwd) {
            strengthEl.classList.add('hidden');
            return;
        }
        strengthEl.classList.remove('hidden');
        
        const score = usr_checkPasswordStrength(pwd);
        const colors = ['bg-red-400', 'bg-orange-400', 'bg-yellow-400', 'bg-green-400'];
        const labels = ['Weak', 'Fair', 'Good', 'Strong'];
        
        for (let i = 1; i <= 4; i++) {
            const bar = document.getElementById(`usr_str_${i}`);
            bar.className = `h-1 flex-1 rounded-full transition-colors ${i <= score ? colors[score - 1] : 'bg-gray-200'}`;
        }
        document.getElementById('usr_str_text').textContent = labels[score - 1] || '';
        document.getElementById('usr_str_text').className = `text-xs ml-1 ${score <= 1 ? 'text-red-500' : score === 2 ? 'text-orange-500' : score === 3 ? 'text-yellow-600' : 'text-green-600'}`;
    }

    // --- Fetch Database ---
    async function usr_fetchUsers() {
        usr_isLoading = true;
        usr_renderSkeleton();
        try {
            const res = await fetch(`/api/users`);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            usr_data = await res.json();
            usr_currentPage = 1;
            usr_selectedUsers.clear();
            usr_renderTable();
        } catch (error) {
            console.error("User fetch error", error);
            usr_renderErrorState();
        } finally {
            usr_isLoading = false;
        }
    }

    async function usr_fetchAvailableLabs() {
        try {
            const res = await fetch('/api/labs/detail');
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const labs = await res.json();
            usr_availableLabs = labs.map(l => l.lab_name);
        } catch (error) {
            console.error("Failed fetching labs for modal", error);
            usr_showToast('Failed to load available labs', 'error');
        }
    }

    // --- Skeleton Loading ---
    function usr_renderSkeleton() {
        const tbody = document.getElementById('usr_table_body');
        const mobile = document.getElementById('usr_mobile_cards');
        let skeletonRows = '';
        for (let i = 0; i < 5; i++) {
            skeletonRows += `
            <tr class="bg-white">
                <td class="p-4 border-b border-blue-50 text-center"><div class="w-4 h-4 mx-auto usr-skeleton"></div></td>
                <td class="p-4 border-b border-blue-50 text-center"><div class="w-6 h-4 mx-auto usr-skeleton"></div></td>
                <td class="p-4 border-b border-blue-50"><div class="flex items-center gap-3"><div class="w-9 h-9 rounded-full usr-skeleton"></div><div class="space-y-1.5"><div class="w-28 h-3 usr-skeleton"></div><div class="w-40 h-2.5 usr-skeleton"></div></div></div></td>
                <td class="p-4 border-b border-blue-50"><div class="w-16 h-5 rounded-full usr-skeleton"></div></td>
                <td class="p-4 border-b border-blue-50"><div class="w-24 h-4 usr-skeleton"></div></td>
                <td class="p-4 border-b border-blue-50 text-center"><div class="w-24 h-3 mx-auto usr-skeleton"></div></td>
                <td class="p-4 border-b border-blue-50 text-center"><div class="w-20 h-3 mx-auto usr-skeleton"></div></td>
                <td class="p-4 border-b border-blue-50 text-center"><div class="flex justify-center gap-1"><div class="w-7 h-7 rounded-lg usr-skeleton"></div><div class="w-7 h-7 rounded-lg usr-skeleton"></div></div></td>
            </tr>`;
        }
        tbody.innerHTML = skeletonRows;
        mobile.innerHTML = Array(3).fill(`
            <div class="p-4 space-y-3">
                <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-full usr-skeleton"></div><div class="flex-1 space-y-1.5"><div class="w-32 h-3 usr-skeleton"></div><div class="w-48 h-2.5 usr-skeleton"></div></div></div>
                <div class="flex gap-2"><div class="w-16 h-5 rounded-full usr-skeleton"></div><div class="w-24 h-5 rounded usr-skeleton"></div></div>
            </div>
        `).join('');
    }

    // --- Error State ---
    function usr_renderErrorState() {
        const tbody = document.getElementById('usr_table_body');
        const mobile = document.getElementById('usr_mobile_cards');
        const errorHTML = `
            <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mb-4">
                    <i data-lucide="alert-triangle" class="w-8 h-8 text-red-400"></i>
                </div>
                <p class="text-gray-700 font-semibold mb-1">Failed to load users</p>
                <p class="text-gray-500 text-sm mb-4">Please check your connection and try again.</p>
                <button onclick="usr_fetchUsers()" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors flex items-center gap-2">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i> Retry
                </button>
            </div>`;
        tbody.innerHTML = `<tr><td colspan="8">${errorHTML}</td></tr>`;
        mobile.innerHTML = errorHTML;
        lucide.createIcons();
    }

    // --- Empty State ---
    function usr_renderEmptyState() {
        const emptyHTML = `
            <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-4">
                    <i data-lucide="users" class="w-8 h-8 text-blue-300"></i>
                </div>
                <p class="text-gray-700 font-semibold mb-1">${usr_searchQuery ? 'No users match your search' : 'No users yet'}</p>
                <p class="text-gray-500 text-sm mb-4">${usr_searchQuery ? 'Try adjusting your search terms.' : 'Get started by adding your first user.'}</p>
                ${!usr_searchQuery ? `<button onclick="usr_openModal()" class="bg-[#6B6565] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#5a5454] transition-colors flex items-center gap-2"><i data-lucide="user-plus" class="w-4 h-4"></i> Add User</button>` : ''}
            </div>`;
        return emptyHTML;
    }

    // --- Filtering & Pagination ---
    function usr_getFilteredData() {
        if (!usr_searchQuery) return [...usr_data];
        const q = usr_searchQuery.toLowerCase();
        return usr_data.filter(u => 
            (u.username || '').toLowerCase().includes(q) ||
            (u.email || '').toLowerCase().includes(q) ||
            (u.role || '').toLowerCase().includes(q) ||
            (u.assignedLabs || []).some(l => l.toLowerCase().includes(q))
        );
    }

    function usr_getPaginatedData(data) {
        const start = (usr_currentPage - 1) * usr_perPage;
        return data.slice(start, start + usr_perPage);
    }

    function usr_getTotalPages(data) {
        return Math.max(1, Math.ceil(data.length / usr_perPage));
    }

    // --- Search ---
    function usr_handleSearch(value) {
        usr_searchQuery = value.trim();
        usr_currentPage = 1;
        usr_selectedUsers.clear();
        document.getElementById('usr_select_all').checked = false;
        document.getElementById('usr_clear_search').classList.toggle('hidden', !value);
        usr_renderTable();
    }

    function usr_clearSearch() {
        document.getElementById('usr_search').value = '';
        usr_handleSearch('');
    }

    // --- Rendering Table ---
    function usr_renderTable() {
        const tbody = document.getElementById('usr_table_body');
        const mobile = document.getElementById('usr_mobile_cards');
        let filtered = usr_getFilteredData();
        
        // Sort
        if (usr_sort.key) {
            filtered.sort((a, b) => {
                let aVal = (a[usr_sort.key] || '').toString().toLowerCase();
                let bVal = (b[usr_sort.key] || '').toString().toLowerCase();
                if (aVal < bVal) return usr_sort.direction === 'asc' ? -1 : 1;
                if (aVal > bVal) return usr_sort.direction === 'asc' ? 1 : -1;
                return 0;
            });
        }

        // Update showing info
        document.getElementById('usr_showing_info').textContent = `${filtered.length} user${filtered.length !== 1 ? 's' : ''}${usr_searchQuery ? ' found' : ''}`;

        // Empty state
        if (filtered.length === 0) {
            const emptyHTML = usr_renderEmptyState();
            tbody.innerHTML = `<tr><td colspan="8">${emptyHTML}</td></tr>`;
            mobile.innerHTML = emptyHTML;
            document.getElementById('usr_pagination').classList.add('hidden');
            lucide.createIcons();
            usr_updateSortIcons();
            usr_updateBulkUI();
            return;
        }

        // Paginate
        const paginatedData = usr_getPaginatedData(filtered);
        const totalPages = usr_getTotalPages(filtered);
        const startIdx = (usr_currentPage - 1) * usr_perPage;

        // Desktop Table
        tbody.innerHTML = paginatedData.map((u, i) => {
            const globalIdx = startIdx + i;
            const isSelected = usr_selectedUsers.has(u.user_id);
            const initials = usr_getInitials(u.username);
            const avatarColor = usr_getAvatarColor(u.username);
            const safeName = usr_escapeHtml(u.username);
            const safeEmail = usr_escapeHtml(u.email);
            
            const assignedHTML = u.role === 'admin' 
                ? '<span class="text-gray-400 italic text-xs">All Labs Access</span>' 
                : (u.assignedLabs && u.assignedLabs.length > 0 
                    ? u.assignedLabs.map(l => `<span class="text-[10px] bg-blue-100 text-blue-800 px-2 py-1 rounded font-bold inline-block">${usr_escapeHtml(l)}</span>`).join(' ')
                    : '<span class="text-gray-400 italic text-xs">No labs assigned</span>');
            
            const lastLog = u.last_login 
                ? new Date(u.last_login).toLocaleString('en-GB', { day:'numeric', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' }) 
                : '<span class="text-gray-300">Never</span>';
            const createdDate = u.created_at 
                ? new Date(u.created_at).toLocaleDateString('en-GB', { day:'numeric', month:'short', year:'numeric' }) 
                : '-';

            return `
            <tr class="${isSelected ? 'bg-blue-50' : (i % 2 === 0 ? 'bg-white' : 'bg-[#EFF6FF]')} hover:bg-blue-100/40 transition-colors group">
                <td class="p-4 border-b border-blue-50 text-center">
                    ${u.role !== 'admin' ? `<input type="checkbox" ${isSelected ? 'checked' : ''} onchange="usr_toggleSelect('${u.user_id}', this.checked)" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer" aria-label="Select ${safeName}">` : ''}
                </td>
                <td class="p-4 border-b border-blue-50 text-center font-bold text-gray-500 text-xs">${globalIdx + 1}</td>
                <td class="p-4 border-b border-blue-50">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 shadow-sm" style="background-color: ${avatarColor}">${initials}</div>
                        <div>
                            <div class="font-bold text-gray-800 text-sm">${safeName}</div>
                            <div class="text-xs text-gray-500 font-normal">${safeEmail}</div>
                        </div>
                    </div>
                </td>
                <td class="p-4 border-b border-blue-50">
                    <span class="px-3 py-1 rounded-full text-white text-[10px] font-black uppercase shadow-sm inline-flex items-center gap-1 ${u.role === 'admin' ? 'bg-[#7C3AED]' : 'bg-[#B5AD30]'}">
                        <i data-lucide="${u.role === 'admin' ? 'shield' : 'user'}" class="w-3 h-3" aria-hidden="true"></i>
                        ${u.role}
                    </span>
                </td>
                <td class="p-4 border-b border-blue-50">${assignedHTML}</td>
                <td class="p-4 border-b border-blue-50 text-center text-xs text-gray-500 italic font-medium">${lastLog}</td>
                <td class="p-4 border-b border-blue-50 text-center text-xs text-gray-500 font-medium">${createdDate}</td>
                <td class="p-4 border-b border-blue-50 text-center">
                    ${u.role !== 'admin' ? `
                        <div class="flex items-center justify-center gap-1 opacity-70 group-hover:opacity-100 transition-opacity">
                            <button onclick="usr_edit('${u.user_id}')" class="p-1.5 bg-[#3B82F6] text-white rounded-lg hover:bg-blue-700 shadow-sm transition-all hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-300" aria-label="Edit ${safeName}">
                                <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                            </button>
                            <button onclick="usr_delete('${u.user_id}', '${safeName}')" class="p-1.5 bg-[#E11D48] text-white rounded-lg hover:bg-red-700 shadow-sm transition-all hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-300" aria-label="Delete ${safeName}">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    ` : '<span class="text-[10px] text-gray-300 uppercase font-bold">Protected</span>'}
                </td>
            </tr>`;
        }).join('');

        // Mobile Cards
        mobile.innerHTML = paginatedData.map((u, i) => {
            const globalIdx = startIdx + i;
            const isSelected = usr_selectedUsers.has(u.user_id);
            const initials = usr_getInitials(u.username);
            const avatarColor = usr_getAvatarColor(u.username);
            const safeName = usr_escapeHtml(u.username);
            const safeEmail = usr_escapeHtml(u.email);

            const assignedHTML = u.role === 'admin' 
                ? '<span class="text-gray-400 italic text-xs">All Labs Access</span>' 
                : (u.assignedLabs && u.assignedLabs.length > 0 
                    ? u.assignedLabs.map(l => `<span class="text-[10px] bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-bold inline-block">${usr_escapeHtml(l)}</span>`).join(' ')
                    : '<span class="text-gray-400 italic text-xs">No labs assigned</span>');

            const lastLog = u.last_login 
                ? new Date(u.last_login).toLocaleString('en-GB', { day:'numeric', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' }) 
                : 'Never';

            return `
            <div class="p-4 ${isSelected ? 'bg-blue-50' : ''}">
                <div class="flex items-start gap-3">
                    ${u.role !== 'admin' ? `<input type="checkbox" ${isSelected ? 'checked' : ''} onchange="usr_toggleSelect('${u.user_id}', this.checked)" class="w-4 h-4 mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer" aria-label="Select ${safeName}">` : '<div class="w-4"></div>'}
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-sm" style="background-color: ${avatarColor}">${initials}</div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <div class="min-w-0">
                                <div class="font-bold text-gray-800 text-sm truncate">${safeName}</div>
                                <div class="text-xs text-gray-500 truncate">${safeEmail}</div>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-white text-[10px] font-black uppercase flex-shrink-0 ${u.role === 'admin' ? 'bg-[#7C3AED]' : 'bg-[#B5AD30]'}">${u.role}</span>
                        </div>
                        <div class="mt-2 flex flex-wrap gap-1">${assignedHTML}</div>
                        <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                            <span>Last login: ${lastLog}</span>
                            ${u.role !== 'admin' ? `
                                <div class="flex gap-1">
                                    <button onclick="usr_edit('${u.user_id}')" class="p-1.5 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200" aria-label="Edit"><i data-lucide="edit" class="w-3.5 h-3.5"></i></button>
                                    <button onclick="usr_delete('${u.user_id}', '${safeName}')" class="p-1.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-200" aria-label="Delete"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            </div>`;
        }).join('');

        lucide.createIcons();
        usr_updateSortIcons();
        usr_renderPagination(filtered.length, totalPages);
        usr_updateBulkUI();
    }

    // --- Sort Icons ---
    function usr_updateSortIcons() {
        document.querySelectorAll('.usr_sort_icon').forEach(el => {
            const key = el.dataset.key;
            el.className = 'usr_sort_icon';
            if (usr_sort.key === key) {
                el.classList.add(usr_sort.direction);
            }
        });
    }

    function usr_requestSort(key) {
        if (usr_sort.key === key) {
            usr_sort.direction = usr_sort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            usr_sort.direction = 'asc';
        }
        usr_sort.key = key;
        usr_renderTable();
    }

    // --- Pagination ---
    function usr_renderPagination(total, totalPages) {
        const paginationEl = document.getElementById('usr_pagination');
        if (totalPages <= 1) {
            paginationEl.classList.add('hidden');
            return;
        }
        paginationEl.classList.remove('hidden');
        paginationEl.classList.add('flex');

        const start = (usr_currentPage - 1) * usr_perPage + 1;
        const end = Math.min(usr_currentPage * usr_perPage, total);
        document.getElementById('usr_page_info').textContent = `Showing ${start}-${end} of ${total}`;

        document.getElementById('usr_prev_btn').disabled = usr_currentPage <= 1;
        document.getElementById('usr_next_btn').disabled = usr_currentPage >= totalPages;

        // Page numbers
        const container = document.getElementById('usr_page_numbers');
        let pages = [];
        const delta = 1;
        const left = Math.max(2, usr_currentPage - delta);
        const right = Math.min(totalPages - 1, usr_currentPage + delta);

        pages.push(1);
        if (left > 2) pages.push('...');
        for (let i = left; i <= right; i++) pages.push(i);
        if (right < totalPages - 1) pages.push('...');
        if (totalPages > 1) pages.push(totalPages);

        container.innerHTML = pages.map(p => {
            if (p === '...') return `<span class="px-2 text-gray-400 text-sm">…</span>`;
            const isActive = p === usr_currentPage;
            return `<button onclick="usr_goToPage(${p})" class="w-8 h-8 rounded-lg text-sm font-medium transition-colors ${isActive ? 'bg-blue-500 text-white shadow-sm' : 'hover:bg-blue-100 text-gray-600'}" ${isActive ? 'aria-current="page"' : ''}>${p}</button>`;
        }).join('');

        lucide.createIcons();
    }

    function usr_changePage(delta) {
        const filtered = usr_getFilteredData();
        const totalPages = usr_getTotalPages(filtered);
        const newPage = usr_currentPage + delta;
        if (newPage >= 1 && newPage <= totalPages) {
            usr_currentPage = newPage;
            usr_renderTable();
            document.querySelector('.bg-white.rounded-xl.shadow-lg')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function usr_goToPage(page) {
        usr_currentPage = page;
        usr_renderTable();
    }

    // --- Selection ---
    function usr_toggleSelect(id, checked) {
        if (checked) usr_selectedUsers.add(id);
        else usr_selectedUsers.delete(id);
        usr_updateBulkUI();
        usr_renderTable();
    }

    function usr_toggleSelectAll(checked) {
        const filtered = usr_getFilteredData();
        const paginated = usr_getPaginatedData(filtered);
        paginated.forEach(u => {
            if (u.role !== 'admin') {
                if (checked) usr_selectedUsers.add(u.user_id);
                else usr_selectedUsers.delete(u.user_id);
            }
        });
        usr_updateBulkUI();
        usr_renderTable();
    }

    function usr_updateBulkUI() {
        const bulkEl = document.getElementById('usr_bulk_actions');
        const countEl = document.getElementById('usr_selected_count');
        if (usr_selectedUsers.size > 0) {
            bulkEl.classList.remove('hidden');
            bulkEl.classList.add('flex');
            countEl.textContent = `${usr_selectedUsers.size} selected`;
        } else {
            bulkEl.classList.add('hidden');
            bulkEl.classList.remove('flex');
        }
    }

    async function usr_bulkDelete() {
        const count = usr_selectedUsers.size;
        if (count === 0) return;
        
        const result = await Swal.fire({
            title: `Delete ${count} user${count > 1 ? 's' : ''}?`,
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E11D48',
            confirmButtonText: 'Yes, delete all'
        });

        if (!result.isConfirmed) return;

        try {
            const ids = Array.from(usr_selectedUsers);
            await Promise.all(ids.map(id => fetch(`/api/users/${id}`, { method: 'DELETE' })));
            usr_selectedUsers.clear();
            usr_fetchUsers();
            usr_showToast(`${count} user${count > 1 ? 's' : ''} deleted successfully`, 'success');
        } catch (error) {
            usr_showToast('Failed to delete some users', 'error');
        }
    }

    // --- Modal & UI Logic ---
    let usr_previousFocus = null;

    async function usr_openModal() {
        usr_editMode = false;
        usr_assignedLabs = [];
        
        document.getElementById('usr_form').reset();
        document.getElementById('usr_input_id').value = '';
        document.getElementById('usr_input_pwd').required = true;
        document.getElementById('usr_pwd_required').classList.remove('hidden');
        document.getElementById('usr_pwd_label').innerHTML = 'Set Password <span id="usr_pwd_required" class="text-red-500">*</span>';
        document.getElementById('usr_input_pwd').placeholder = 'Password';
        document.getElementById('usr_modal_title').innerText = "Add New User";
        document.getElementById('usr_btn_submit').innerText = "Create";
        document.getElementById('usr_pwd_strength').classList.add('hidden');
        
        document.getElementById('usr_lab_assignment_section').classList.add('hidden');
        
        // Clear validation errors
        document.querySelectorAll('[id$="_error"]').forEach(el => el.classList.add('hidden'));
        
        await usr_fetchAvailableLabs();
        
        usr_previousFocus = document.activeElement;
        document.getElementById('usr_modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Focus first input
        setTimeout(() => document.getElementById('usr_input_name').focus(), 100);
    }

    async function usr_edit(id) {
        usr_editMode = true;
        const u = usr_data.find(x => x.user_id == id);
        if(!u) return;

        await usr_fetchAvailableLabs();
        usr_assignedLabs = u.assignedLabs ? [...u.assignedLabs] : [];
        
        document.getElementById('usr_input_id').value = u.user_id;
        document.getElementById('usr_input_name').value = u.username;
        document.getElementById('usr_input_email').value = u.email;
        document.getElementById('usr_input_role').value = u.role;
        document.getElementById('usr_input_pwd').required = false;
        document.getElementById('usr_input_pwd').placeholder = "Leave blank to keep current";
        document.getElementById('usr_pwd_label').innerHTML = 'Change Password <span id="usr_pwd_required" class="text-red-500 hidden">*</span>';
        document.getElementById('usr_pwd_strength').classList.add('hidden');
        
        document.getElementById('usr_modal_title').innerText = "Edit User";
        document.getElementById('usr_btn_submit').innerText = "Update";
        
        // Clear validation errors
        document.querySelectorAll('[id$="_error"]').forEach(el => el.classList.add('hidden'));
        
        usr_handleRoleChange();
        
        usr_previousFocus = document.activeElement;
        document.getElementById('usr_modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        setTimeout(() => document.getElementById('usr_input_name').focus(), 100);
    }

    function usr_closeModal() { 
        document.getElementById('usr_modal').classList.add('hidden'); 
        document.body.style.overflow = '';
        if (usr_previousFocus) {
            usr_previousFocus.focus();
            usr_previousFocus = null;
        }
    }

    // --- Keyboard Navigation ---
    document.addEventListener('keydown', (e) => {
        const modal = document.getElementById('usr_modal');
        if (modal.classList.contains('hidden')) return;
        
        if (e.key === 'Escape') {
            usr_closeModal();
            return;
        }

        // Focus trap
        if (e.key === 'Tab') {
            const focusable = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            const first = focusable[0];
            const last = focusable[focusable.length - 1];
            
            if (e.shiftKey) {
                if (document.activeElement === first) {
                    e.preventDefault();
                    last.focus();
                }
            } else {
                if (document.activeElement === last) {
                    e.preventDefault();
                    first.focus();
                }
            }
        }
    });

    // --- Lab Assignment Logic ---
    function usr_handleRoleChange() {
        const role = document.getElementById('usr_input_role').value;
        const section = document.getElementById('usr_lab_assignment_section');
        
        if (role === 'supervisor') {
            section.classList.remove('hidden');
            usr_renderLabUI();
        } else {
            section.classList.add('hidden');
            usr_assignedLabs = []; 
        }
    }

    function usr_renderLabUI() {
        const badgeContainer = document.getElementById('usr_assigned_badges');
        if (usr_assignedLabs.length === 0) {
            badgeContainer.innerHTML = '<span class="text-sm text-gray-400 italic">No labs assigned yet...</span>';
        } else {
            badgeContainer.innerHTML = usr_assignedLabs.map(lab => `
                <span class="flex items-center gap-2 px-3 py-1.5 bg-blue-600 text-white text-sm font-semibold rounded-lg shadow-sm animate-in fade-in zoom-in-95 duration-200" role="listitem">
                    <i data-lucide="flask-conical" class="w-3.5 h-3.5 opacity-70" aria-hidden="true"></i>
                    ${usr_escapeHtml(lab)}
                    <button type="button" onclick="usr_removeLab('${usr_escapeHtml(lab)}')" class="hover:bg-blue-800 rounded-full p-0.5 transition-colors focus:outline-none focus:ring-2 focus:ring-white" aria-label="Remove ${usr_escapeHtml(lab)}">
                        <i data-lucide="x" class="w-3 h-3"></i>
                    </button>
                </span>
            `).join('');
        }
        lucide.createIcons();

        const selectDropdown = document.getElementById('usr_lab_select');
        let optionsHTML = '<option value="" disabled selected>+ Add Lab to assignment...</option>';
        const available = usr_availableLabs.filter(lab => !usr_assignedLabs.includes(lab));
        if (available.length === 0) {
            optionsHTML += '<option value="" disabled>All labs assigned</option>';
        } else {
            available.forEach(lab => {
                optionsHTML += `<option value="${usr_escapeHtml(lab)}">${usr_escapeHtml(lab)}</option>`;
            });
        }
        selectDropdown.innerHTML = optionsHTML;
    }

    function usr_addLab(selectElement) {
        const lab = selectElement.value;
        if (lab && !usr_assignedLabs.includes(lab)) {
            usr_assignedLabs.push(lab);
            usr_renderLabUI();
        }
        selectElement.value = "";
    }

    function usr_removeLab(lab) {
        usr_assignedLabs = usr_assignedLabs.filter(l => l !== lab);
        usr_renderLabUI();
    }

    // --- Form Validation ---
    function usr_validateForm() {
        let isValid = true;
        const name = document.getElementById('usr_input_name').value.trim();
        const email = document.getElementById('usr_input_email').value.trim();
        const pwd = document.getElementById('usr_input_pwd').value;

        // Name validation
        const nameError = document.getElementById('usr_name_error');
        if (!name) {
            nameError.textContent = 'Name is required';
            nameError.classList.remove('hidden');
            isValid = false;
        } else if (name.length < 2) {
            nameError.textContent = 'Name must be at least 2 characters';
            nameError.classList.remove('hidden');
            isValid = false;
        } else {
            nameError.classList.add('hidden');
        }

        // Email validation
        const emailError = document.getElementById('usr_email_error');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email) {
            emailError.textContent = 'Email is required';
            emailError.classList.remove('hidden');
            isValid = false;
        } else if (!emailRegex.test(email)) {
            emailError.textContent = 'Please enter a valid email address';
            emailError.classList.remove('hidden');
            isValid = false;
        } else {
            emailError.classList.add('hidden');
        }

        // Password validation (only required for new users)
        if (!usr_editMode && !pwd) {
            isValid = false;
        } else if (pwd && pwd.length < 6) {
            usr_showToast('Password must be at least 6 characters', 'error');
            isValid = false;
        }

        return isValid;
    }

    // --- Submit Logic ---
    async function usr_submitForm(e) {
        e.preventDefault();
        
        if (!usr_validateForm()) return;
        
        const btn = document.getElementById('usr_btn_submit');
        const originalText = btn.innerText;
        btn.innerHTML = '<span class="inline-flex items-center gap-2"><svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity="0.3"/><path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg> Processing...</span>';
        btn.disabled = true;

       const id = document.getElementById('usr_input_id').value;
        const roleInput = document.getElementById('usr_input_role');
        
        // MODIFIKASI DISINI: Jika input role disabled (berarti lagi buka My Profile), 
        // ambil value aslinya, bukan dari dropdown yang ter-disable.
        let finalRole = roleInput.value;
        if (roleInput.disabled && usr_editMode) {
             const u = usr_data.find(x => x.user_id == id);
             finalRole = u ? u.role : 'supervisor';
        }
        
        const payload = {
            username: document.getElementById('usr_input_name').value.trim(),
            email: document.getElementById('usr_input_email').value.trim(),
            role: finalRole, // <--- UBAH BAGIAN INI JADI finalRole (Bukan getElementById lagi)
            assignedLabs: usr_assignedLabs
        };
        const pwd = document.getElementById('usr_input_pwd').value;
        if(pwd) payload.password = pwd;

        const method = usr_editMode ? 'PUT' : 'POST';
        const url = usr_editMode ? `/api/users/${id}` : '/api/users';

        try {
            const response = await fetch(url, { 
                method, 
                headers: {'Content-Type': 'application/json'}, 
                body: JSON.stringify(payload) 
            });
            
            if(response.ok) {
                usr_closeModal();
                usr_fetchUsers();
                usr_showToast(`User ${usr_editMode ? 'updated' : 'created'} successfully!`, 'success');
            } else {
                const err = await response.json().catch(() => ({ message: 'Failed to save user.' }));
                usr_showToast(err.message || 'Failed to save user.', 'error');
            }
        } catch (error) {
            usr_showToast('Network connection failed. Please try again.', 'error');
        }
        
        btn.innerText = originalText;
        btn.disabled = false;
    }

    function usr_delete(id, name) {
        Swal.fire({ 
            title: 'Delete User?', 
            html: `Are you sure you want to remove <strong>${usr_escapeHtml(name)}</strong> permanently?<br><span class="text-sm text-gray-500">This action cannot be undone.</span>`, 
            icon: 'warning', 
            showCancelButton: true, 
            confirmButtonColor: '#E11D48',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel'
        }).then(async (res) => {
            if(res.isConfirmed) {
                try {
                    const response = await fetch(`/api/users/${id}`, { method: 'DELETE' });
                    if (response.ok) {
                        usr_selectedUsers.delete(id);
                        usr_fetchUsers();
                        usr_showToast(`${name} has been deleted`, 'success');
                    } else {
                        usr_showToast('Failed to delete user', 'error');
                    }
                } catch (error) {
                    usr_showToast('Network error. Please try again.', 'error');
                }
            }
        });
    }

    function usr_togglePwd() {
        const input = document.getElementById('usr_input_pwd');
        const icon = document.getElementById('usr_pwd_icon');
        if (input.type === "password") {
            input.type = "text";
            icon.setAttribute('data-lucide', 'eye-off');
        } else {
            input.type = "password";
            icon.setAttribute('data-lucide', 'eye');
        }
        lucide.createIcons();
    }

    // --- Password Strength Listener ---
    document.getElementById('usr_input_pwd')?.addEventListener('input', usr_updatePasswordStrength);

    // --- Init ---
    if(document.getElementById('usr_table_body')) usr_fetchUsers();
</script>
