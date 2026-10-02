<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Dashboard - Lab Monitoring</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="flex h-screen w-screen bg-gray-50 overflow-hidden text-gray-900 font-sans">

    <!-- SIDEBAR -->
    <aside class="w-20 bg-white border-r border-gray-200 flex flex-col items-center py-6 shrink-0 shadow-sm z-20">
        <div class="mb-10 w-full px-2 flex justify-center">
            <img src="{{ asset('assets/images/330px-Philips_logo.svg.png') }}" alt="Logo" class="h-8 w-auto object-contain cursor-pointer active:scale-95 transition-transform" onclick="switchMenu('dashboard')">
        </div>

        <nav class="flex flex-col gap-8 flex-1 w-full items-center">
            <button onclick="switchMenu('dashboard')" class="menu-btn p-2 rounded-lg transition-all text-green-600 bg-green-50 shadow-[4px_0_0_0_rgba(22,163,74,1)]" data-target="dashboard" title="Dashboard"><i data-lucide="layout-dashboard"></i></button>
            <button onclick="switchMenu('devices')" class="menu-btn p-2 rounded-lg transition-all text-gray-400 hover:text-green-500" data-target="devices" title="Assigned Devices"><i data-lucide="hard-drive"></i></button>
            <button onclick="switchMenu('alerts')" class="menu-btn p-2 rounded-lg transition-all text-gray-400 hover:text-green-500" data-target="alerts" title="Action Center"><i data-lucide="alert-triangle"></i></button>
        </nav>

        <button onclick="handleLogout()" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors active:scale-90" title="Logout"><i data-lucide="log-out"></i></button>
    </aside>

<!-- MAIN CONTENT -->
<main class="flex-1 overflow-y-auto flex flex-col relative">

    <header class="flex justify-between items-center px-8 py-6 border-b border-gray-200 sticky top-0 bg-gray-50/80 backdrop-blur-md z-10">

        <!-- PAGE TITLE -->
        <div>
            <h2
                id="page-title"
                class="text-2xl font-bold uppercase tracking-tight text-gray-800"
            >
                Dashboard Overview
            </h2>

            <p class="text-sm text-gray-500">
                Monitoring access for
                <span id="header-name">Supervisor</span>
            </p>
        </div>


       <!-- TOP BAR RIGHT -->
        <div class="flex items-center gap-4 text-sm font-medium text-gray-600">
            
            @include('components.notification-bell')

            <!-- PROFILE DROPDOWN -->
            <div class="relative">
                <button onclick="toggleProfileMenu()" class="flex items-center gap-3 focus:outline-none hover:opacity-80 transition-opacity">
                    <div class="flex flex-col text-right hidden sm:flex">
                        <span class="text-xs text-gray-500 font-normal leading-tight">Welcome,</span>
                        <span id="header-user-name" class="font-bold text-gray-900 leading-tight truncate max-w-[120px]">Supervisor</span>
                    </div>
                    <div id="header-avatar" class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-sm bg-green-600 transition-transform active:scale-95">
                        SU
                    </div>
                </button>

                <!-- DROPDOWN CONTENT -->
                <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50 animate-in fade-in slide-in-from-top-2 duration-200">
                    <div class="px-4 py-2 border-b border-gray-50 mb-1">
                        <p class="text-xs text-gray-500">Signed in as</p>
                        <p id="header-user-email" class="text-sm font-bold text-gray-900 truncate">supervisor@philips.com</p>
                    </div>
                    <button onclick="openMyProfile()" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors flex items-center gap-2">
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
                <div class="mb-6"><h3 class="text-lg font-bold text-gray-800 border-l-4 border-green-500 pl-3">Status Summary</h3></div>

                <!-- STAT CARDS -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-10" id="sup-stat-cards">
                    <div class="text-gray-400 text-sm">Loading stats...</div>
                </div>

                <div class="mb-6"><h3 class="text-lg font-bold text-gray-800 border-l-4 border-blue-500 pl-3">Lab Quick Overview</h3></div>
                
                <!-- GRID LABS ASSIGNED -->
                <div id="sup-lab-cards" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="text-gray-400 text-sm">Loading your managed labs...</div>
                </div>
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
            try {
                return localStorage.getItem(key) || fallback;
            } catch (e) {
                return fallback;
            }
        }

        // 3. DEKLARASI VARIABEL
        const userId = serverUser ? (serverUser.user_id || serverUser.id).toString() : getSafeLocalStorage('userId', '');
        const username = serverUser ? serverUser.username : getSafeLocalStorage('username', 'Supervisor');
        const userEmail = serverUser ? serverUser.email : getSafeLocalStorage('userEmail', 'supervisor@philips.com');
        const userRole = serverUser ? serverUser.role : getSafeLocalStorage('userRole', 'supervisor');

        // 4. CEK OTENTIKASI
        if (userRole !== 'supervisor') {
            window.location.href = '/login';
        }

        // 5. UPDATE UI HEADER
        document.getElementById('header-name').innerText = username;
        document.getElementById('header-user-name').innerText = username;
        document.getElementById('header-user-email').innerText = userEmail;

        // 6. FUNGSI INISIAL & WARNA AVATAR (WAJIB ADA DI SINI!)
        function getInitials(name) {
            if (!name) return 'SU';
            return name.split(/\s+/).map(w => w[0]).slice(0, 2).join('').toUpperCase();
        }

        function getAvatarColor(name) {
            const colors = ['#6366f1','#8b5cf6','#ec4899','#f43f5e','#f97316','#eab308','#22c55e','#14b8a6','#06b6d4','#3b82f6'];
            let hash = 0;
            for (let i = 0; i < (name || '').length; i++) hash = name.charCodeAt(i) + ((hash << 5) - hash);
            return colors[Math.abs(hash) % colors.length];
        }

        // 7. TERAPKAN AVATAR
        const avatarEl = document.getElementById('header-avatar');
        avatarEl.innerText = getInitials(username);
        avatarEl.style.backgroundColor = getAvatarColor(username);

        // 8. INIT ICONS
        lucide.createIcons();

        // 9. PROFILE DROPDOWN LOGIC
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

        // 10. MODAL PROFILE LOGIC
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
                const me = users.find(u => (u.user_id || u.id).toString() === userId);

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
                    Swal.fire('Success', 'Profile updated successfully!', 'success');
                    
                    document.getElementById('header-name').innerText = payload.username;
                    document.getElementById('header-user-name').innerText = payload.username;
                    document.getElementById('header-user-email').innerText = payload.email;
                    
                    avatarEl.innerText = getInitials(payload.username);
                    avatarEl.style.backgroundColor = getAvatarColor(payload.username);
                    
                    try {
                        localStorage.setItem('username', payload.username);
                        localStorage.setItem('userEmail', payload.email);
                    } catch (err) {}

                    closeProfileModal();
                } else {
                    const err = await response.json().catch(() => ({}));
                    Swal.fire('Error', err.message || 'Failed to update profile', 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'Network connection failed.', 'error');
            }
            
            btn.innerHTML = originalText;
            btn.disabled = false;
        }

        // 11. NAVIGASI SPA
        function switchMenu(target) {
            const titles = {
                'dashboard': 'Dashboard Overview', 'devices': 'Assigned Devices', 'alerts': 'Action Center'
            };
            document.getElementById('page-title').innerText = titles[target];

            document.querySelectorAll('.menu-btn').forEach(btn => {
                if (btn.getAttribute('data-target') === target) {
                    btn.className = "menu-btn p-2 rounded-lg transition-all text-green-600 bg-green-50 shadow-[4px_0_0_0_rgba(22,163,74,1)]";
                } else {
                    btn.className = "menu-btn p-2 rounded-lg transition-all text-gray-400 hover:text-green-500";
                }
            });

            document.querySelectorAll('.content-view').forEach(view => view.classList.add('hidden'));
            document.getElementById(`view-${target}`).classList.remove('hidden');
            
            // Refresh icons jika ada view baru yang muncul
            setTimeout(() => lucide.createIcons(), 50);
        }

        // 12. FETCH & RENDER DATA DASHBOARD
        async function fetchSupDashboard() {
            try {
                const [userRes, sensorRes, alertRes] = await Promise.all([
                    fetch('/api/users'), fetch('/api/sensors/latest'), fetch('/api/alerts')
                ]);
                const users = await userRes.json();
                const sensors = await sensorRes.json();
                const alerts = await alertRes.json();

                const currentUser = users.find(u => (u.user_id || u.id).toString() === userId);
                const assigned = currentUser ? (currentUser.assignedLabs || []) : [];
                
                const myLabs = sensors.filter(lab => assigned.includes(lab.lab_name));
                const myAlerts = alerts.filter(alert => assigned.includes(alert.location));

                renderSupDashboard(myLabs, myAlerts);
            } catch (err) {
                console.error("Fetch supervisor error", err);
                document.getElementById('sup-stat-cards').innerHTML = `<div class="text-red-500 text-sm col-span-4">Gagal memuat data. Silakan refresh.</div>`;
            }
        }

        function renderSupDashboard(myLabs, myAlerts) {
            const online = myLabs.filter(s => s.current_status === 'online').length;
            const warnings = myAlerts.filter(a => (a.level || '').toLowerCase() === 'warning').length;
            const criticals = myAlerts.filter(a => (a.level || '').toLowerCase() === 'critical' || parseFloat(a.triggered_value) === 0).length;

            document.getElementById('sup-stat-cards').innerHTML = `
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                    <span class="text-sm font-bold text-gray-500">Labs Managed</span>
                    <span class="text-3xl font-black mt-2 text-gray-900">${myLabs.length}</span>
                    <span class="text-xs font-bold text-green-600 mt-2 bg-green-50 w-max px-2 py-1 rounded">Assigned</span>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                    <span class="text-sm font-bold text-gray-500">Normal Status</span>
                    <span class="text-3xl font-black mt-2 text-gray-900">${online}</span>
                    <span class="text-xs font-bold text-green-600 mt-2 bg-green-50 w-max px-2 py-1 rounded">Optimal</span>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                    <span class="text-sm font-bold text-gray-500">Warning Alerts</span>
                    <span class="text-3xl font-black mt-2 text-gray-900">${warnings}</span>
                    <span class="text-xs font-bold ${warnings > 0 ? 'text-amber-600 bg-amber-50' : 'text-green-600 bg-green-50'} mt-2 w-max px-2 py-1 rounded">${warnings > 0 ? 'Needs Attention' : 'All Clear'}</span>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                    <span class="text-sm font-bold text-gray-500">Critical Alerts</span>
                    <span class="text-3xl font-black mt-2 text-gray-900">${criticals}</span>
                    <span class="text-xs font-bold ${criticals > 0 ? 'text-red-600 bg-red-50' : 'text-green-600 bg-green-50'} mt-2 w-max px-2 py-1 rounded">${criticals > 0 ? 'Urgent Action' : 'All Clear'}</span>
                </div>
            `;

            const labBox = document.getElementById('sup-lab-cards');
            if (myLabs.length === 0) {
                labBox.innerHTML = `<div class="col-span-full p-10 bg-white rounded-xl border border-dashed border-gray-300 text-center text-gray-500">No labs assigned to your account yet. Please contact Admin.</div>`;
            } else {
                labBox.innerHTML = myLabs.map(lab => {
                    const bgStatus = lab.current_status === 'online' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-red-100 text-red-700 border-red-200';
                    return `
                    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm cursor-pointer hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="font-bold text-gray-800">${lab.lab_name}</h4>
                            <span class="text-[10px] uppercase font-bold px-2 py-1 rounded border ${bgStatus}">${lab.current_status || 'offline'}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-2 rounded border border-gray-100 text-center">
                                <span class="block text-xs text-gray-500">Temp</span>
                                <span class="font-bold text-gray-800 text-lg">${parseFloat(lab.current_temp || 0).toFixed(1)}°C</span>
                            </div>
                            <div class="bg-gray-50 p-2 rounded border border-gray-100 text-center">
                                <span class="block text-xs text-gray-500">Hum</span>
                                <span class="font-bold text-gray-800 text-lg">${parseFloat(lab.current_hum || 0).toFixed(1)}%</span>
                            </div>
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
        fetchSupDashboard();
        setInterval(fetchSupDashboard, 30000);
    </script>
</body>
</html>