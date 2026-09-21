<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Monitoring - Overall Status</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Sembunyikan scrollbar bawaan untuk tampilan TV/Dashboard yang mulus */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        /* Tooltip Hover untuk Map */
        .map-dot-group:hover .map-tooltip { display: flex; }
    </style>
</head>
<body class="h-screen w-screen flex flex-col p-2 gap-2 bg-[#181C20] text-white overflow-hidden min-w-[1024px] font-sans">

    <!-- ==================== TOP BAR ==================== -->
    <div class="flex-none flex flex-col w-full pb-1.5 border-b border-gray-700/50 mb-1">
        <!-- Baris 1: Judul & Jam -->
        <div class="flex justify-between items-center w-full mb-1">
            <div class="flex-1 flex justify-start min-w-max">
                <a href="#" class="font-bold text-white hover:text-blue-400 cursor-pointer transition-colors" style="font-size: 26px;">
                    Lab Monitoring - Overall Status
                </a>
            </div>
            
          <!-- Custom Filter Input -->
<div id="custom-filter-inputs" class="flex-none hidden justify-center items-center px-4 gap-2.5 text-[13px]">
    <span class="font-semibold text-gray-200">From :</span>
    <input type="date" id="c-start-date" class="bg-white text-black px-2 rounded-sm outline-none w-[115px] h-[26px]">
    <input type="time" id="c-start-time" value="00:00" class="bg-white text-black px-2 rounded-sm outline-none w-[80px] h-[26px]">
    <span class="text-gray-500 font-bold mx-2">|</span>
    <span class="font-semibold text-gray-200">To :</span>
    <input type="date" id="c-end-date" class="bg-white text-black px-2 rounded-sm outline-none w-[115px] h-[26px]">
    <input type="time" id="c-end-time" value="23:59" class="bg-white text-black px-2 rounded-sm outline-none w-[80px] h-[26px]">
    <span class="text-gray-500 font-bold mx-2">|</span>
    <span class="font-semibold text-gray-200">Interval :</span>
    <select id="c-interval" class="bg-white text-black px-1.5 rounded-sm outline-none w-[85px] h-[26px]">
        <option value="1">Minute</option>
        <option value="10">10 Mins</option>
        <option value="60" selected>Hour</option>
        <option value="1440">Day</option>
    </select>
    <button onclick="applyCustomFilter()" class="ml-3 px-4 h-[26px] bg-[#1E87FF] hover:bg-blue-500 text-white rounded-sm transition font-bold shadow-md">Apply</button>
</div>

            <!-- KANAN: Jam Besar (TOMBOL LOGIN RAHASIA) -->
<div class="flex-1 flex justify-end min-w-max cursor-pointer hover:opacity-75 transition-opacity" title="Admin Login" onclick="window.location.href='/login'">
    <p id="clock-display" class="font-bold text-white tracking-wide select-none" style="font-size: 26px;">--:--:--</p>
</div>
        </div>

        <!-- Baris 2: Tab & Status Koneksi -->
        <div class="flex justify-between items-end w-full mt-1">
            <div class="flex-1 flex justify-start min-w-max">
                <div class="flex border border-white rounded-none">
                    <button onclick="setFilter('Daily')" class="filter-btn bg-[#34465A] border-r border-white w-[126px] h-[27px] text-[14.7px] transition">Daily</button>
                    <button onclick="setFilter('Weekly')" class="filter-btn bg-[#171717] border-r border-white w-[126px] h-[27px] text-[14.7px] transition">Weekly</button>
                    <button onclick="setFilter('Monthly')" class="filter-btn bg-[#171717] border-r border-white w-[126px] h-[27px] text-[14.7px] transition">Monthly</button>
                    <button onclick="setFilter('Custom')" class="filter-btn bg-[#171717] w-[126px] h-[27px] text-[14.7px] transition">Custom</button>
                </div>
            </div>
            <div class="flex-1 flex justify-end items-center gap-2 text-gray-400 pb-1 min-w-max text-[14.7px]">
                <span>Last: <span id="last-update">--</span></span><span>|</span>
                <span class="text-[#9FD678] font-semibold"><span id="top-connected">0</span> Connected</span><span>|</span>
                <span class="text-[#F09A95] font-semibold"><span id="top-disconnected">0</span> Disconnected</span>
            </div>
        </div>
    </div>

    <!-- ==================== MAIN CONTENT ==================== -->
    <div class="flex-1 flex gap-2 min-h-0 w-full pb-1">
        
        <!-- KOLOM KIRI (60%) -->
        <div class="w-[60%] flex flex-col gap-2 min-h-0 h-full">
            <!-- Reading Status -->
            <div class="min-h-0" style="flex: 3;">
                <div class="w-full h-full bg-[#313533] rounded-lg border border-gray-700 flex flex-col overflow-hidden">
                    <div class="bg-gradient-to-r from-[#1E87FF] to-[#295383] px-5 h-[40px] flex-none flex items-center">
                        <h2 class="text-white font-bold text-[22px] leading-none tracking-wide">Reading Status</h2>
                    </div>
                    <div id="reading-container" class="flex-1 flex flex-col justify-evenly px-4 py-2 min-h-0">
                        <div class="text-center text-gray-400 text-sm">Loading readings...</div>
                    </div>
                </div>
            </div>
            
            <!-- Lab Overview -->
            <div class="min-h-0" style="flex: 5;">
                <div class="w-full h-full bg-[#313533] rounded-lg border border-gray-700 flex flex-col overflow-hidden">
                    <div class="bg-gradient-to-r from-[#1E87FF] to-[#295383] px-5 h-[40px] flex justify-between items-center flex-none">
                        <h2 class="text-white font-bold text-[22px] leading-none tracking-wide">Lab Overview</h2>
                        <div class="flex gap-3 text-xs text-white">
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#008D34]"></span> Online</span>
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-yellow-500"></span> Warning</span>
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#A71818]"></span> Offline</span>
                        </div>
                    </div>
                    <div id="lab-overview-container" class="flex-1 flex gap-3 p-3 min-h-0">
                        <div class="text-center text-gray-400 text-sm w-full mt-10">Loading labs...</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- KOLOM KANAN (40%) -->
        <div class="w-[40%] flex flex-col gap-2 min-h-0 h-full">
            <!-- Connection Status (MAP) -->
            <div class="min-h-0" style="flex: 4.9;">
                <div class="w-full h-full bg-[#313533] rounded-lg border border-gray-700 flex flex-col overflow-hidden">
                    <div class="bg-gradient-to-r from-[#1E87FF] to-[#295383] px-5 h-[40px] flex-none flex items-center">
                        <h2 class="text-white font-bold text-[22px] leading-none tracking-wide">Connection Status</h2>
                    </div>
                    <div class="flex-1 p-2 flex items-center justify-center min-h-0">
                        <div class="relative flex-shrink-0" style="max-width: 100%; max-height: 100%; aspect-ratio: 1920 / 1080;">
                            <img src="{{ asset('assets/images/map.png') }}" alt="Map" class="block w-full h-full object-fill border border-white" />
                            <div id="map-container"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Alert Status -->
            <div class="min-h-0" style="flex: 3.7;">
                <div class="w-full h-full bg-[#313533] rounded-lg border border-gray-700 flex flex-col overflow-hidden">
                    <div class="bg-gradient-to-r from-[#1E87FF] to-[#295383] px-5 h-[40px] flex-none flex items-center">
                        <h2 class="text-white font-bold text-[22px] leading-none tracking-wide">Alert Status</h2>
                    </div>
                    <div class="flex-1 flex flex-col p-4 min-h-0">
                        <div id="alert-container" class="flex-1 flex flex-col gap-3 overflow-y-auto min-h-0 pr-2 scrollbar-hide">
                            <div class="text-center text-gray-400 text-sm mt-5">Loading alerts...</div>
                        </div>
                        <div class="flex justify-end items-end flex-none pt-2 mt-auto border-t border-gray-600/20">
                            <span id="cal-status-text" class="text-[14px] font-bold transition-colors">Device Calibrated: 0 / 0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ==================== JAVASCRIPT ==================== -->
    <script>
        // Constants & Configs
        const STATIC_LOCATIONS = [
            { id: 1, labName: 'MCC Quality Lab', x: 46, y: 32 },
            { id: 2, labName: 'Calibration Lab 01', x: 83, y: 24 },
            { id: 3, labName: 'Calibration Lab 02', x: 86, y: 24 },
            { id: 4, labName: 'Oral Health Care Lab', x: 77, y: 29 },
            { id: 5, labName: 'Grooming Lab 01', x: 45, y: 89 },
            { id: 6, labName: 'Grooming Lab 02', x: 54, y: 70 },
            { id: 7, labName: 'Guardline Lab', x: 28, y: 89 },
        ];
        const B2_LABS = ['MCC Quality Lab', 'Calibration Lab 01', 'Oral Health Care Lab', 'Calibration Lab 02'];
        const B3_LABS = ['Guardline Lab', 'Grooming Lab 01', 'Grooming Lab 02'];
        
        let currentFilter = 'Daily';

        // 1. Clock Engine
        setInterval(() => {
            const now = new Date();
            const dateStr = now.toLocaleDateString('en-GB', { day: '2-digit', month: 'short' });
            const timeStr = now.toLocaleTimeString('en-GB', { hour12: false });
            document.getElementById('clock-display').innerText = `${dateStr} ${timeStr}`;
        }, 1000);

        // 2. Filter Tabs Logic
        function setFilter(filter) {
            currentFilter = filter;
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.style.backgroundColor = btn.innerText === filter ? '#34465A' : '#171717';
            });
            document.getElementById('custom-filter-inputs').style.display = filter === 'Custom' ? 'flex' : 'none';
            if (filter !== 'Custom') fetchDashboardData();
        }

function applyCustomFilter() {
    const startDate = document.getElementById('c-start-date').value;
    const startTime = document.getElementById('c-start-time').value;
    const endDate = document.getElementById('c-end-date').value;
    const endTime = document.getElementById('c-end-time').value;
    const interval = document.getElementById('c-interval').value;

    if (!startDate || !endDate) { 
        Swal.fire('Oops', 'Harap isi tanggal From dan To!', 'warning'); 
        return; 
    }
    
    customDateRange = { 
        start: `${startDate} ${startTime}:00`, 
        end: `${endDate} ${endTime}:00`, 
        interval: interval 
    };
    
    // Tetap menggunakan fetchDashboardData
    fetchDashboardData(); 
    
    Swal.fire({
        icon: 'success',
        title: 'Filter Applied!',
        text: 'Overall Status is updated.',
        timer: 1500,
        showConfirmButton: false
    });
}
            // biar url nya bagus
        function loadSpecificLab(labName) {
    window.location.href = `/labs?lab=${encodeURIComponent(labName)}`;
}

        // 3. Fetch Data API
        async function fetchDashboardData() {
            try {
                let rangeParam = '24h';
                if (currentFilter === 'Weekly') rangeParam = '7d';
                else if (currentFilter === 'Monthly') rangeParam = '30d';

                const response = await fetch(`/api/overview/overall?range=${rangeParam}`);
                const data = await response.json();
                if (data.error) throw new Error(data.error);

                renderDashboard(data);
                
                const now = new Date();
                document.getElementById('last-update').innerText = `${now.toLocaleDateString('en-GB', { day: '2-digit', month: 'short'})} ${now.toLocaleTimeString('en-GB', { hour12: false })}`;

            } catch (error) {
                console.error("Dashboard Fetch Error:", error);
            }
        }

        // 4. Master Render Function
        function renderDashboard({ sensors, alerts, devices, histories }) {
            
            // --- TOP BAR STATS ---
            let connected = 0, disconnected = 0;
            sensors.forEach(s => s.current_status === 'online' ? connected++ : disconnected++);
            document.getElementById('top-connected').innerText = connected;
            document.getElementById('top-disconnected').innerText = disconnected;

            // --- READING STATUS (Min/Max) ---
            let allHistory = [];
            histories.forEach((hist, index) => {
                if(Array.isArray(hist) && hist.length > 0) {
                    hist.forEach(record => {
                        if (record.temperature !== null && record.humidity !== null) {
                            allHistory.push({ temp: parseFloat(record.temperature), hum: parseFloat(record.humidity), lab: STATIC_LOCATIONS[index].labName });
                        }
                    });
                }
            });

            const rContainer = document.getElementById('reading-container');
            if (allHistory.length > 0) {
                const hTemp = allHistory.reduce((p, c) => c.temp > p.temp ? c : p);
                const lTemp = allHistory.reduce((p, c) => c.temp < p.temp ? c : p);
                const hHum = allHistory.reduce((p, c) => c.hum > p.hum ? c : p);
                const lHum = allHistory.reduce((p, c) => c.hum < p.hum ? c : p);

                const rd = [
                    { title: 'Highest Temp', val: hTemp.temp, unit: '°C', lab: hTemp.lab, alert: hTemp.temp > 25 }, 
                    { title: 'Lowest Temp', val: lTemp.temp, unit: '°C', lab: lTemp.lab, alert: lTemp.temp < 15 },
                    { title: 'Highest Hum', val: hHum.hum, unit: '%RH', lab: hHum.lab, alert: hHum.hum > 70 },
                    { title: 'Lowest Hum', val: lHum.hum, unit: '%RH', lab: lHum.lab, alert: lHum.hum < 30 },
                ];

                rContainer.innerHTML = rd.map(d => {
                    const color = d.alert ? '#F09A95' : '#9FD678';
                    return `
                    <div class="flex items-baseline justify-between w-full border-b border-gray-600/30 pb-1.5 last:border-0 last:pb-0">
                        <span class="text-white font-normal w-[22%]" style="font-size: 13px;">${d.title}</span>
                        <div class="flex items-baseline gap-1 w-[20%] justify-end">
                            <span class="font-bold leading-none" style="color: ${color}; font-size: 32px;">${d.val.toFixed(1)}</span>
                            <span class="text-white font-normal" style="font-size: 12px;">${d.unit}</span>
                        </div>
                        <span class="text-gray-400 font-normal text-center w-[10%]" style="font-size: 11px;">IN</span>
                        <span class="font-bold text-right w-[48%] leading-tight truncate" style="color: ${color}; font-size: 20px;">${d.lab}</span>
                    </div>`;
                }).join('');
            } else {
                rContainer.innerHTML = `<div class="text-center text-gray-500 italic mt-10">No reading data</div>`;
            }

            // --- LAB OVERVIEW (B2 & B3) ---
            const mapLab = (labName) => {
                const s = sensors.find(x => x.lab_name === labName);
                return { name: labName, temp: s?.current_temp ? parseFloat(s.current_temp) : 0, hum: s?.current_hum ? parseFloat(s.current_hum) : 0, status: s?.current_status === 'online' ? 'Online' : 'Offline' };
            };
            const b2Data = B2_LABS.map(mapLab); const b3Data = B3_LABS.map(mapLab);
            const bldgs = [
                { name: 'B2 Buildings', total: B2_LABS.length, online: b2Data.filter(l => l.status === 'Online').length, labs: b2Data, isB2: true },
                { name: 'B3 Buildings', total: B3_LABS.length, online: b3Data.filter(l => l.status === 'Online').length, labs: b3Data, isB2: false }
            ];

            document.getElementById('lab-overview-container').innerHTML = bldgs.map(b => `
                <div class="flex flex-col min-h-0 ${b.isB2 ? 'flex-[1.8]' : 'flex-1'}">
                    <div class="flex justify-between items-center text-white font-bold px-1 pb-1.5 flex-none">
                        <span style="font-size: 18px;">${b.name}</span>
                        <span class="bg-black border border-gray-600 px-3 py-[1px] rounded-sm text-[12px]">${b.online} / ${b.total}</span>
                    </div>
                    <div class="flex-1 bg-[#0C0024] rounded-md p-2.5 min-h-0 flex flex-col">
                        <div class="flex-1 ${b.isB2 ? 'grid grid-cols-2 gap-2' : 'flex flex-col gap-2'} min-h-0">
                            ${b.labs.map(l => {
    const bg = l.status === 'Online'
        ? 'bg-[#008D34]'
        : l.status === 'Offline'
            ? 'bg-[#A71818]'
            : 'bg-[#313533] border-yellow-500';

    return `
    <div
        onclick="loadSpecificLab('${l.name}')"
        class="cursor-pointer hover:brightness-110 rounded-sm flex flex-col items-center justify-center text-white border border-gray-600/30 transition p-1 h-full ${bg}"
    >
        <span class="text-[14px] font-semibold text-center leading-tight">${l.name}</span>
        <span class="text-[13px] font-normal mt-0.5 leading-none">${l.temp}°C</span>
        <span class="text-[13px] font-normal leading-tight">${l.hum}%RH</span>
    </div>`;
}).join('')}
                        </div>
                    </div>
                </div>
            `).join('');

 // --- MAP CONNECTION STATUS ---
document.getElementById('map-container').innerHTML = STATIC_LOCATIONS.map(loc => {

    const s = sensors.find(x => x.lab_name === loc.labName);

    const status = s?.current_status === 'online'
        ? 'Online'
        : 'Offline';

    const dDate = s?.last_seen
        ? new Date(s.last_seen)
        : null;

    const lastData = dDate
        ? `${String(dDate.getDate()).padStart(2,'0')}-${["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"][dDate.getMonth()]}-${dDate.getFullYear()} ${dDate.toTimeString().split(' ')[0]}`
        : 'No Data';

    const dotBg = status === 'Online'
        ? 'bg-[#008D34] shadow-[0_0_12px_4px_rgba(0,141,52,0.8)]'
        : 'bg-[#A71818] shadow-[0_0_12px_4px_rgba(167,24,24,0.8)]';

    const posTooltip = loc.x > 50
        ? 'right-[calc(100%+14px)]'
        : 'left-[calc(100%+14px)]';

    const posArr = loc.x > 50
        ? '-right-[6.5px] border-t border-r'
        : '-left-[6.5px] border-b border-l';

    const yPos = loc.y > 75
        ? 'bottom-[-10px]'
        : 'top-1/2 -translate-y-1/2';

    const yArr = loc.y > 75
        ? 'bottom-[14px]'
        : 'top-1/2 -translate-y-1/2';

    return `
    <div
        onclick="loadSpecificLab('${loc.labName}')"
        class="absolute map-dot-group z-10 cursor-pointer transform -translate-x-1/2 -translate-y-1/2"
        style="top: ${loc.y}%; left: ${loc.x}%;"
    >

        <div class="w-3.5 h-3.5 rounded-full border-2 border-white transition-all duration-300 ${dotBg}">
        </div>

        <div class="absolute map-tooltip hidden flex-col bg-[#111111]/90 backdrop-blur-sm border border-gray-600 text-white px-4 py-3 rounded-lg w-max z-50 shadow-2xl transition-all ${posTooltip} ${yPos}">

            <div class="absolute w-3 h-3 bg-[#111111]/90 backdrop-blur-sm border-gray-600 transform rotate-45 ${posArr} ${yArr}">
            </div>

            <span class="text-[20px] font-medium mb-1.5">
                ${loc.labName}
            </span>

            <span class="text-[18px] font-bold mb-2.5">
                Status:
                <span class="${status === 'Online'
                    ? 'text-[#008D34]'
                    : 'text-[#A71818]'}">
                    ${status}
                </span>
            </span>

            <span class="text-[16px] text-gray-300 leading-tight">
                Last Data:<br/>
                ${lastData}
            </span>

        </div>

    </div>`;

}).join('');

// --- ALERT STATUS ---
            const aContainer = document.getElementById('alert-container');
            
            if (alerts.length === 0) {
                aContainer.innerHTML = `
                    <div class="flex-1 flex flex-col items-center justify-center min-h-0 py-2 opacity-80 mt-10">
                        <svg class="w-14 h-14 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-gray-300 text-[18px] font-semibold tracking-wide">No Active Alerts</span>
                        <span class="text-gray-500 text-[13px] mt-1">All parameters are within normal limits.</span>
                    </div>`;
            } else {
                aContainer.innerHTML = alerts.map(a => {
                    // LOGIKA BARU: Format Waktu & Tanggal
                    let timeDetail = 'N/A';
                    if (a.triggered_at) {
                        const d = new Date(a.triggered_at);
                        const day = String(d.getDate()).padStart(2, '0');
                        const month = d.toLocaleString('en-US', { month: 'short' }); 
                        const hours = String(d.getHours()).padStart(2, '0');
                        const minutes = String(d.getMinutes()).padStart(2, '0');
                        timeDetail = `${day} ${month}, ${hours}:${minutes}`; // Contoh: 12 Sep, 11:22
                    }

                    const val = parseFloat(a.triggered_value || 0);
                    const isCrit = a.level === 'critical' || val === 0 || a.parameter === 'status';
                    
                    const txt = (a.parameter === 'status' || val === 0) 
                        ? `Connection Error in ${a.location} at ${timeDetail}` 
                        : `${a.parameter.charAt(0).toUpperCase() + a.parameter.slice(1)} at ${a.level} level (${val}) in ${a.location} at ${timeDetail}`;
                    
                    return `
                    <div class="flex items-center gap-3 text-white border-b border-gray-600/30 pb-3 last:border-0 last:pb-0">
                        <div class="w-3.5 h-3.5 rounded-full ${isCrit ? 'bg-[#A71818]' : 'bg-yellow-500'} border border-white flex-none shadow-sm"></div>
                        <span class="text-[14px] font-normal leading-tight">${txt}</span>
                    </div>`;
                }).join('');
            }

            // --- CALIBRATION TEXT ---
            const cal = devices.filter(d => d.calStatus === 'valid').length;
            const tot = devices.length;
            const calEl = document.getElementById('cal-status-text');
            calEl.innerText = `Device Calibrated: ${cal} / ${tot}`;
            calEl.className = `text-[14px] font-bold transition-colors ${cal === tot && tot > 0 ? 'text-[#9FD678]' : 'text-[#F09A95]'}`;
        }

        // INIT
        fetchDashboardData();
        setInterval(fetchDashboardData, 30000);
    </script>
</body>
</html>