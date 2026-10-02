<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Lab Monitoring</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="flex h-screen w-screen bg-gray-50 overflow-hidden text-gray-900 font-sans">

    <!-- SIDEBAR -->
    <aside class="w-20 bg-white border-r border-gray-200 flex flex-col items-center py-6 shrink-0 shadow-sm z-20">
        <div class="mb-10 w-full px-2 flex justify-center">
            <img src="{{ asset('assets/images/330px-Philips_logo.svg.png') }}" alt="Logo" class="h-8 w-auto object-contain cursor-pointer active:scale-95 transition-transform" onclick="switchMenu('dashboard')">
        </div>

        <nav class="flex flex-col gap-8 flex-1 w-full items-center">
            <button onclick="switchMenu('dashboard')" class="menu-btn p-2 rounded-lg transition-all text-blue-600 bg-blue-50 shadow-[4px_0_0_0_rgba(59,130,246,1)]" data-target="dashboard" title="Dashboard"><i data-lucide="layout-dashboard"></i></button>
            <button onclick="switchMenu('labs')" class="menu-btn p-2 rounded-lg transition-all text-gray-400 hover:text-blue-500" data-target="labs" title="Lab Management"><i data-lucide="building-2"></i></button>
            <button onclick="switchMenu('users')" class="menu-btn p-2 rounded-lg transition-all text-gray-400 hover:text-blue-500" data-target="users" title="User Management"><i data-lucide="users"></i></button>
            <button onclick="switchMenu('devices')" class="menu-btn p-2 rounded-lg transition-all text-gray-400 hover:text-blue-500" data-target="devices" title="Device Management"><i data-lucide="hard-drive"></i></button>
            <button onclick="switchMenu('alerts')" class="menu-btn p-2 rounded-lg transition-all text-gray-400 hover:text-blue-500" data-target="alerts" title="Alert Management"><i data-lucide="alert-triangle"></i></button>
        </nav>

        <button onclick="handleLogout()" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors active:scale-90" title="Logout"><i data-lucide="log-out"></i></button>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 overflow-y-auto flex flex-col relative">
        <header class="flex justify-between items-center px-8 py-6 border-b border-gray-200 sticky top-0 bg-gray-50/80 backdrop-blur-md z-10">
            <h2 id="page-title" class="text-2xl font-bold uppercase tracking-tight">Dashboard Overview</h2>
            <div class="flex items-center gap-4 text-sm font-medium text-gray-600">
                
                @include('components.notification-bell')

                <!-- PROFILE DROPDOWN -->
                <div class="relative">
                    <button onclick="toggleProfileMenu()" class="flex items-center gap-3 focus:outline-none hover:opacity-80 transition-opacity">
                        <div class="flex flex-col text-right hidden sm:flex">
                            <span class="text-xs text-gray-500 font-normal leading-tight">Welcome,</span>
                            <span id="header-user-name" class="font-bold text-gray-900 leading-tight truncate max-w-[120px]">Admin</span>
                        </div>
                        <div id="header-avatar" class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-sm bg-blue-600 transition-transform active:scale-95">
                            AD
                        </div>
                    </button>

                    <!-- DROPDOWN CONTENT -->
                    <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50 animate-in fade-in slide-in-from-top-2 duration-200">
                        <div class="px-4 py-2 border-b border-gray-50 mb-1">
                            <p class="text-xs text-gray-500">Signed in as</p>
                            <p id="header-user-email" class="text-sm font-bold text-gray-900 truncate">admin@philips.com</p>
                        </div>
                        <button onclick="openMyProfile()" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors flex items-center gap-2">
                            <i data-lucide="user-cog" class="w-4 h-4"></i> My Profile
                        </button>
                        <hr class="my-1 border-gray-100">
                        <button onclick="handleLogout()" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors flex items-center gap-2">
                            <i data-lucide="log-out" class="w-4 h-4"></i> Sign out
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <div class="px-8 pb-10 mt-8 relative w-full h-full">
            
            <!-- VIEW: DASHBOARD -->
            <div id="view-dashboard" class="content-view block animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="mb-8 flex justify-between items-end">
                    <div>
                        <h3 class="text-lg font-bold">Dashboard Overview</h3>
                        <p class="text-sm text-gray-500">Real-time monitoring of your temperature and humidity sensors.</p>
                    </div>
                    <button onclick="window.location.href='/'" class="bg-white border border-gray-200 px-4 py-1.5 rounded-lg text-sm font-medium hover:bg-gray-50 shadow-sm transition">
                        View Public Dashboard
                    </button>
                </div>

                <!-- STAT CARDS -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-10" id="admin-stat-cards">
                    <div class="text-gray-400 text-sm">Loading stats...</div>
                </div>

                <!-- RECENT ALERTS -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Recent Alerts</h3>
                        <button onclick="switchMenu('alerts')" class="text-sm text-blue-600 font-medium hover:underline">View All History</button>
                    </div>
                    <div id="admin-recent-alerts" class="flex flex-col gap-3">
                        <div class="text-gray-400 text-sm">Loading alerts...</div>
                    </div>
                </div>
            </div>

            <!-- VIEW: LABS -->
    <div id="view-labs" class="content-view hidden">
        @include('components.lab-management')
    </div>

    <!-- VIEW: USERS -->
    <div id="view-users" class="content-view hidden">
        @include('components.user-management')
    </div>

    <!-- VIEW: DEVICES -->
    <div id="view-devices" class="content-view hidden">
        @include('components.device-management')
    </div>

    <!-- VIEW: ALERTS -->
    <div id="view-alerts" class="content-view hidden">
        @include('components.alert-management')
    </div>

</div>
<!-- PROFILE MODAL (STANDALONE) -->
    <div id="profile_modal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeProfileModal()"></div>
        <div class="relative bg-white w-full max-w-md rounded-2xl shadow-xl border border-gray-200 p-6 sm:p-8 transform transition-all">
            <button onclick="closeProfileModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
            
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">My Profile</h2>
                <p class="text-sm text-gray-500">Update your account details</p>
            </div>

            <form id="profile_form" onsubmit="submitProfileForm(event)" class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 ml-1 mb-1">Full Name</label>
                    <input type="text" id="prof_name" required class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 text-sm transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 ml-1 mb-1">Email Address</label>
                    <input type="email" id="prof_email" required class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 text-sm transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 ml-1 mb-1">New Password <span class="text-xs text-gray-400 font-normal">(leave blank to keep)</span></label>
                    <input type="password" id="prof_pwd" placeholder="Leave blank to keep current" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 text-sm transition-all">
                </div>
                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeProfileModal()" class="px-5 py-2.5 rounded-xl font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors text-sm">Cancel</button>
                    <button type="submit" id="prof_btn_save" class="px-5 py-2.5 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 transition-colors text-sm shadow-md">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    </main>

<script>
    // 1. AMBIL DATA DARI SERVER
    const serverUser = @json($user ?? null);

    // 2. FUNGSI AMAN UNTUK BACA LOCALSTORAGE
    function getSafeLocalStorage(key, fallback) {
        try { return localStorage.getItem(key) || fallback; } 
        catch (e) { return fallback; }
    }

    // 3. DEKLARASI VARIABEL
    const userId = serverUser ? (serverUser.user_id || serverUser.id) : getSafeLocalStorage('userId', '');
    const username = serverUser ? serverUser.username : getSafeLocalStorage('username', 'Admin');
    const userEmail = serverUser ? serverUser.email : getSafeLocalStorage('userEmail', 'admin@philips.com');
    const userRole = serverUser ? serverUser.role : getSafeLocalStorage('userRole', 'admin');

    // 4. CEK OTENTIKASI
    if (userRole !== 'admin') {
        window.location.href = '/login';
    }

    // 5. UPDATE UI HEADER
    document.getElementById('header-user-name').innerText = username;
    document.getElementById('header-user-email').innerText = userEmail;
    lucide.createIcons();

    // --- LOGIKA PROFILE AVATAR ---
    function getInitials(name) {
        return name.split(/\s+/).map(w => w[0]).slice(0, 2).join('').toUpperCase();
    }
    function getAvatarColor(name) {
        const colors = ['#6366f1','#8b5cf6','#ec4899','#f43f5e','#f97316','#eab308','#22c55e','#14b8a6','#06b6d4','#3b82f6'];
        let hash = 0;
        for (let i = 0; i < (name || '').length; i++) hash = name.charCodeAt(i) + ((hash << 5) - hash);
        return colors[Math.abs(hash) % colors.length];
    }
    const avatarEl = document.getElementById('header-avatar');
    avatarEl.innerText = getInitials(username);
    avatarEl.style.backgroundColor = getAvatarColor(username);

    function toggleProfileMenu() {
        document.getElementById('profile-dropdown').classList.toggle('hidden');
    }
    document.addEventListener('click', (event) => {
        const dropdown = document.getElementById('profile-dropdown');
        const button = dropdown.previousElementSibling;
        if (dropdown && !dropdown.contains(event.target) && !button.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });

    function openMyProfile() {
        toggleProfileMenu(); 
        document.getElementById('prof_name').value = username;
        document.getElementById('prof_email').value = userEmail;
        document.getElementById('prof_pwd').value = ''; 
        document.getElementById('profile_modal').classList.remove('hidden');
    }
    function closeProfileModal() {
        document.getElementById('profile_modal').classList.add('hidden');
    }

    async function submitProfileForm(e) {
        e.preventDefault();
        const btn = document.getElementById('prof_btn_save');
        const originalText = btn.innerText;
        btn.innerHTML = 'Saving...';
        btn.disabled = true;

        try {
            const resUser = await fetch('/api/users');
            const users = await resUser.json();
            const me = users.find(u => (u.user_id || u.id).toString() === userId.toString());

            const payload = {
                username: document.getElementById('prof_name').value.trim(),
                email: document.getElementById('prof_email').value.trim(),
                role: me ? me.role : userRole,
                assignedLabs: me ? (me.assignedLabs || []) : []
            };
            
            const pwd = document.getElementById('prof_pwd').value;
            if (pwd) payload.password = pwd;

            const response = await fetch(`/api/users/${userId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            if (response.ok) {
                Swal.fire({ title: 'Success', text: 'Profile updated successfully!', icon: 'success' });
                document.getElementById('header-user-name').innerText = payload.username;
                document.getElementById('header-user-email').innerText = payload.email;
                document.getElementById('header-avatar').innerText = getInitials(payload.username);
                document.getElementById('header-avatar').style.backgroundColor = getAvatarColor(payload.username);
                try {
                    localStorage.setItem('username', payload.username);
                    localStorage.setItem('userEmail', payload.email);
                } catch (err) {}
                closeProfileModal();
            } else {
                const err = await response.json().catch(() => ({}));
                Swal.fire({ title: 'Error', text: err.message || 'Failed to update profile', icon: 'error' });
            }
        } catch (error) {
            Swal.fire({ title: 'Error', text: 'Network connection failed.', icon: 'error' });
        }
        btn.innerHTML = originalText;
        btn.disabled = false;
    }

    // --- NAVIGASI MENU ---
    function switchMenu(target) {
        const titles = {
            'dashboard': 'Dashboard Overview', 'labs': 'Lab Management',
            'users': 'User Management', 'devices': 'Device Management', 'alerts': 'Alert Management'
        };
        document.getElementById('page-title').innerText = titles[target];

        document.querySelectorAll('.menu-btn').forEach(btn => {
            if (btn.getAttribute('data-target') === target) {
                btn.className = "menu-btn p-2 rounded-lg transition-all text-blue-600 bg-blue-50 shadow-[4px_0_0_0_rgba(59,130,246,1)]";
            } else {
                btn.className = "menu-btn p-2 rounded-lg transition-all text-gray-400 hover:text-blue-500";
            }
        });

        document.querySelectorAll('.content-view').forEach(view => view.classList.add('hidden'));
        document.getElementById(`view-${target}`).classList.remove('hidden');
    }

    // --- FETCH DASHBOARD (VERSI AMAN DARI SYNTAX ERROR) ---
    async function fetchAdminDashboard() {
        try {
            const [sensorRes, alertRes, deviceRes] = await Promise.all([
                fetch('/api/sensors/latest'), 
                fetch('/api/alerts'), 
                fetch('/api/devices')
            ]);
            
            const sensors = await sensorRes.json();
            const alerts = await alertRes.json();
            const devices = await deviceRes.json();

            const safeSensors = Array.isArray(sensors) ? sensors : [];
            const safeAlerts = Array.isArray(alerts) ? alerts : [];
            const safeDevices = Array.isArray(devices) ? devices : [];

            renderAdminDashboard(safeSensors, safeAlerts, safeDevices);
        } catch (err) {
            console.error("Fetch admin error:", err);
            renderAdminDashboard([], [], []);
        }
    }

    function renderAdminDashboard(sensors, alerts, devices) {
        const online = sensors.filter(s => s.current_status === 'online').length;
        const criticals = alerts.filter(a => a.level === 'critical').length;
        
        let highestTemp = "--"; 
        let highestLab = "N/A";
        
        if (sensors.length > 0) {
            const sorted = [...sensors].sort((a, b) => (b.max_temp || 0) - (a.max_temp || 0));
            highestTemp = sorted[0].max_temp || "--";
            highestLab = sorted[0].lab_name || "--";
        }
        
        const validCal = devices.filter(d => (d.calStatus || '').toLowerCase() === 'valid').length;

        document.getElementById('admin-stat-cards').innerHTML = `
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                <span class="text-sm font-bold text-gray-500">Total Devices</span>
                <span class="text-3xl font-black mt-2 text-gray-900">${sensors.length}</span>
                <span class="text-xs font-bold text-green-600 mt-2 bg-green-50 w-max px-2 py-1 rounded">${online} online</span>
            </div>
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                <span class="text-sm font-bold text-gray-500">Active Alerts</span>
                <span class="text-3xl font-black mt-2 text-gray-900">${alerts.length}</span>
                <span class="text-xs font-bold text-red-600 mt-2 bg-red-50 w-max px-2 py-1 rounded">${criticals} High priority</span>
            </div>
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                <span class="text-sm font-bold text-gray-500">Highest Temp</span>
                <span class="text-3xl font-black mt-2 text-gray-900">${highestTemp}°C</span>
                <span class="text-xs font-bold text-gray-500 mt-2">${highestLab}</span>
            </div>
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                <span class="text-sm font-bold text-gray-500">Calibrated Devices</span>
                <span class="text-3xl font-black mt-2 text-gray-900">${devices.length}</span>
                <span class="text-xs font-bold text-green-600 mt-2 bg-green-50 w-max px-2 py-1 rounded">${validCal} valid</span>
            </div>
        `;

        const alertBox = document.getElementById('admin-recent-alerts');
        if (alerts.length === 0) {
            alertBox.innerHTML = `<p class="text-gray-500 italic text-sm">No active alerts right now.</p>`;
        } else {
            alertBox.innerHTML = alerts.slice(0, 3).map(alert => {
                const val = parseFloat(alert.triggered_value || 0);
                const isOffline = alert.parameter === 'status' || val === 0 || alert.parameter === 'connection';
                const isHigh = alert.level === 'critical' || isOffline; 
                
                let timeStr = '';
                if (alert.triggered_at) {
                    const d = new Date(alert.triggered_at);
                    timeStr = d.toLocaleString('en-US', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                }
                
                const bgCol = isHigh ? 'border-red-500 bg-red-50' : 'border-amber-500 bg-amber-50';
                const badgeCol = isHigh ? 'bg-red-500' : 'bg-amber-500';
                const badgeTxt = isHigh ? 'CRITICAL' : 'MEDIUM';
                
                const title = isOffline ? 'Connection Loss' : `${alert.parameter.charAt(0).toUpperCase() + alert.parameter.slice(1)} Alert`;
                const detail = isOffline ? `Device in ${alert.location || 'Unknown'} is offline.` : `${alert.location} - exceeded threshold (${val})`;

                return `
                <div class="flex justify-between p-4 border-l-4 rounded-r-lg shadow-sm ${bgCol}">
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-2">
                            <strong class="text-gray-900">${title}</strong>
                            <span class="text-[10px] px-2 py-0.5 rounded uppercase font-bold text-white ${badgeCol}">${badgeTxt}</span>
                        </div>
                        <p class="text-sm text-gray-600">${detail}</p>
                        <span class="text-[11px] text-gray-400">${timeStr}</span>
                    </div>
                    <div class="flex flex-col gap-2 justify-center">
                        <button onclick="switchMenu('alerts')" class="text-xs border border-gray-300 bg-white px-3 py-1.5 rounded hover:bg-gray-50 font-medium shadow-sm transition">View Details</button>
                    </div>
                </div>`;
            }).join('');
        }
    }

function handleLogout() {
    // Hapus data sesi SAJA. JANGAN gunakan localStorage.clear()
    // agar 'remembered_email' tetap tersimpan untuk login berikutnya.
    localStorage.removeItem('userRole');
    localStorage.removeItem('username');
    localStorage.removeItem('userId');
    localStorage.removeItem('userEmail');
    
    // Redirect ke halaman login
    window.location.href = '/login';
}

    // Jalankan saat load
    fetchAdminDashboard();
    setInterval(fetchAdminDashboard, 30000);
</script>
</body>
</html>