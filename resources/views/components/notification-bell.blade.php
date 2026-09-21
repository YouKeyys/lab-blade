<div class="relative" id="notif-dropdown-container">
    <!-- Bell Icon -->
    <div class="relative cursor-pointer hover:bg-gray-700/50 p-2 rounded-full transition-colors" onclick="notif_toggle()">
        <i data-lucide="bell" class="w-6 h-6 text-gray-300"></i>
        <span id="notif-badge" class="hidden absolute top-0 right-0 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center border-2 border-[#181C20] shadow-sm animate-pulse">0</span>
    </div>

    <!-- Dropdown Panel -->
    <div id="notif-panel" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden z-[9999] animate-in slide-in-from-top-2 duration-200">
        <div class="bg-gray-50 p-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Notifications</h3>
            <span id="notif-count" class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-0.5 rounded-full">0 New</span>
        </div>
        <div id="notif-list" class="max-h-[350px] overflow-y-auto">
            <div class="p-5 text-center text-gray-400 text-sm">Loading...</div>
        </div>
    </div>
</div>

<script>
    let notif_assignedLabs = [];
    const notif_role = localStorage.getItem('userRole') || 'admin';
    const notif_userId = localStorage.getItem('userId');

    function notif_getDismissed() {
        return JSON.parse(localStorage.getItem('dismissedNotifs') || '[]');
    }

    async function notif_fetch() {
        if (notif_role === 'supervisor' && notif_assignedLabs.length === 0) {
            try {
                const res = await fetch('/api/users');
                const users = await res.json();
                const me = users.find(u => u.user_id == notif_userId);
                if (me && me.assignedLabs) notif_assignedLabs = me.assignedLabs;
            } catch(e) {}
        }

        try {
            const [alertRes, deviceRes] = await Promise.all([
                fetch('/api/alerts'), fetch('/api/devices')
            ]);
            const alertsData = await alertRes.json();
            const devicesData = await deviceRes.json();

            let generated = [];

            alertsData.forEach(a => {
                if (a.status === 'active' && (notif_role === 'admin' || notif_assignedLabs.includes(a.location))) {
                    const val = parseFloat(a.triggered_value || 0);
                    const isOffline = val === 0;
                    const timeStr = a.triggered_at ? new Date(a.triggered_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '';
                    
                    // ... di dalam notif_fetch() ...
generated.push({
    id: `alert-${a.alert_id || a.id}`,
    type: 'alert',
    title: isOffline ? 'Connection Lost' : 'Sensor Alert',
    message: isOffline ? `Device ${a.device_id || a.deviceId} in ${a.location} is disconnected.` : `${a.device_id || a.deviceId} (${a.parameter}) is at ${val}`,
    time: timeStr,
    targetMenu: 'alerts', // HAPUS garis miring (/) agar cocok dengan parameter switchMenu()
    level: isOffline ? 'critical' : (a.level || 'warning')
});

// ... untuk notifikasi kalibrasi ...
targetMenu: notif_role === 'admin' ? 'devices' : 'labs', // Hapus garis miring (/)
                }
            });

            devicesData.forEach(d => {
                if ((d.calStatus === 'expired' || d.calStatus === 'recalibration_needed') && (notif_role === 'admin' || notif_assignedLabs.includes(d.location))) {
                    generated.push({
                        id: `calib-${d.device_id || d.id}`,
                        type: 'calibration',
                        title: d.calStatus === 'expired' ? 'Calibration Expired' : 'Calibration Due',
                        message: `Device ${d.device_id || d.id} in ${d.location || 'Unassigned'} needs attention.`,
                        time: 'System',
                        targetMenu: notif_role === 'admin' ? '/devices' : '/labs',
                        level: d.calStatus === 'expired' ? 'critical' : 'warning'
                    });
                }
            });

            const dismissed = notif_getDismissed();
            const unread = generated.filter(n => !dismissed.includes(n.id));
            notif_render(unread);
        } catch(e) { console.error("Notif error", e); }
    }

    function notif_render(notifs) {
        const badge = document.getElementById('notif-badge');
        const count = document.getElementById('notif-count');
        const list = document.getElementById('notif-list');

        if (notifs.length > 0) {
            badge.classList.remove('hidden');
            badge.innerText = notifs.length > 9 ? '9+' : notifs.length;
            count.innerText = `${notifs.length} New`;
        } else {
            badge.classList.add('hidden');
            count.innerText = `0 New`;
        }

        if (notifs.length === 0) {
            list.innerHTML = `<div class="p-8 text-center flex flex-col items-center justify-center text-gray-400"><i data-lucide="check-circle-2" class="w-8 h-8 mb-2 opacity-50"></i><p class="text-sm font-medium">You're all caught up!</p></div>`;
        } else {
            list.innerHTML = notifs.map(n => {
                const isCrit = n.level === 'critical';
                const icon = n.type === 'calibration' ? 'clock' : 'alert-triangle';
                const color = isCrit ? 'text-red-600' : 'text-amber-500';
                const bg = isCrit ? 'bg-red-50' : 'bg-amber-50';

                return `<div onclick="notif_click('${n.id}', '${n.targetMenu}')" class="p-4 border-b border-gray-50 hover:bg-blue-50/50 cursor-pointer transition-colors flex gap-3 group">
                    <div class="mt-1 p-2 rounded-full shrink-0 h-fit ${bg}">
                        <i data-lucide="${icon}" class="w-4.5 h-4.5 ${color}"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold ${isCrit ? 'text-red-600' : 'text-gray-800'}">${n.title}</h4>
                        <p class="text-xs text-gray-600 mt-0.5 leading-relaxed">${n.message}</p>
                        <span class="text-[10px] text-gray-400 font-medium mt-1.5 flex items-center justify-between">
                            ${n.time} <span class="opacity-0 group-hover:opacity-100 text-blue-500 font-bold transition-opacity">Click to view ➔</span>
                        </span>
                    </div>
                </div>`;
            }).join('');
        }
        if(window.lucide) lucide.createIcons();
    }

    function notif_click(id, targetMenu) {
    const dismissed = notif_getDismissed();
    dismissed.push(id);
    localStorage.setItem('dismissedNotifs', JSON.stringify(dismissed));
    notif_toggle();
    
    // Cek apakah ada fungsi switchMenu (berarti sedang di Admin/Supervisor)
    if (typeof switchMenu === 'function') {
        switchMenu(targetMenu);
    } else {
        // Fallback jika notifikasi dipakai di halaman luar
        window.location.href = '/' + targetMenu;
    }
}

    function notif_toggle() { document.getElementById('notif-panel').classList.toggle('hidden'); }

    document.addEventListener('mousedown', (e) => {
        const container = document.getElementById('notif-dropdown-container');
        const panel = document.getElementById('notif-panel');
        if (container && !container.contains(e.target)) panel.classList.add('hidden');
    });

    notif_fetch();
    setInterval(notif_fetch, 30000);
</script>