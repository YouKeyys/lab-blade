<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="page-meta-title">Lab Monitoring - Specific Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="h-screen w-screen flex flex-col p-2 gap-2 bg-[#181C20] text-white overflow-hidden min-w-[1024px] font-sans">

    <!-- ==================== TOP BAR ==================== -->
    <div class="flex-none flex flex-col w-full pb-1.5 border-b border-gray-700/50 mb-1">
        <div class="flex justify-between items-center w-full mb-1">
            <div class="flex-1 flex justify-start min-w-max">
                <a href="#" onclick="window.location.href='/'" class="font-bold text-white hover:text-blue-400 cursor-pointer transition-colors" style="font-size: 26px;" id="topbar-title">
                    Lab Monitoring - Loading...
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
                <span>Last: <span id="lab-last-seen">--</span></span><span>|</span>
                <span id="lab-connection-badge" class="font-semibold text-[#F09A95]">Disconnected</span>
            </div>
        </div>
    </div>

    <!-- ==================== MAIN CONTENT ==================== -->
    <div class="flex-1 flex flex-col gap-2 min-h-0 w-full pb-1">
        
        <!-- BARIS ATAS (METRIK & OVERALL STATUS & ALERT) -->
        <div class="flex gap-2 min-h-0" style="flex: 500;">
            
            <!-- Kolom Kiri: Kartu Metrik & Overall Status -->
            <div class="flex flex-col gap-2 min-h-0 h-full" style="flex: 1050;">
                
                <!-- 4 Kotak Metrik Utama -->
                <div class="grid grid-cols-4 gap-2 min-h-0 w-full" style="flex: 150;">
                    <div class="bg-[#2A2D2B] rounded-sm border-l-[6px] border-[#00C2FF] flex flex-col items-center justify-center">
                        <span class="text-gray-300 text-[14px]">Temperature</span>
                        <div class="flex items-baseline gap-1"><span id="metric-temp" class="text-[#9FD678] font-bold text-[32px] xl:text-[38px] leading-none mt-1">0</span><span class="text-[#9FD678] text-[14px]">°C</span></div>
                        <span id="metric-req-temp" class="text-gray-400 text-[11px] xl:text-[12px] mt-1">N/A</span>
                    </div>
                    <div class="bg-[#2A2D2B] rounded-sm border-l-[6px] border-[#00C2FF] flex flex-col items-center justify-center">
                        <span class="text-gray-300 text-[14px]">Humidity</span>
                        <div class="flex items-baseline gap-1"><span id="metric-hum" class="text-[#9FD678] font-bold text-[32px] xl:text-[38px] leading-none mt-1">0</span><span class="text-[#9FD678] text-[14px]">%RH</span></div>
                        <span id="metric-req-hum" class="text-gray-400 text-[11px] xl:text-[12px] mt-1">N/A</span>
                    </div>
                    <div class="bg-[#2A2D2B] rounded-sm border-l-[6px] border-red-600 flex flex-col items-center justify-center">
                        <span class="text-gray-300 text-[14px] mb-1">Active Alerts</span>
                        <span id="metric-alerts" class="text-white font-bold text-[40px] xl:text-[48px] leading-tight">0</span>
                    </div>
                    <div class="bg-[#2A2D2B] rounded-sm border-l-[6px] border-transparent flex flex-col items-center justify-center">
                        <span class="text-gray-300 text-[14px] mb-1">Device Calibrated</span>
                        <span id="metric-calibrated" class="font-bold text-[36px] xl:text-[42px] leading-tight text-[#F09A95]">No</span>
                    </div>
                </div>

                <!-- Tabel Overall Status -->
                <div class="w-full bg-[#313533] rounded-lg border border-gray-700 flex flex-col overflow-hidden" style="flex: 346;">
                    <div class="bg-gradient-to-r from-[#1E87FF] to-[#295383] px-5 flex-none h-[40px] flex items-center">
                        <h2 class="text-white font-bold text-[22px] leading-none tracking-wide">Overall Status</h2>
                    </div>
                    <div class="flex-1 flex flex-col justify-evenly px-8 xl:px-12 py-4">
                        <div class="flex w-full items-center">
                            <div class="w-1/2 flex items-center"><span class="w-[120px] xl:w-[150px] text-[18px] xl:text-[20px] font-bold">Avg. Temp</span><span class="text-[#9FD678] text-[22px] xl:text-[24px] font-bold"><span id="stat-avg-temp">0</span><span class="text-[14px]">°C</span></span></div>
                            <div class="w-1/2 flex items-center pl-8 xl:pl-16"><span class="w-[140px] xl:w-[160px] text-[18px] xl:text-[20px] font-bold">Warning</span><span id="stat-warning" class="text-yellow-500 text-[22px] xl:text-[24px] font-bold">0</span></div>
                        </div>
                        <div class="flex w-full items-center">
                            <div class="w-1/2 flex items-center"><span class="w-[120px] xl:w-[150px] text-[18px] xl:text-[20px] font-bold">Avg. Hum</span><span class="text-[#9FD678] text-[22px] xl:text-[24px] font-bold"><span id="stat-avg-hum">0</span><span class="text-[14px]">%</span></span></div>
                            <div class="w-1/2 flex items-center pl-8 xl:pl-16"><span class="w-[140px] xl:w-[160px] text-[18px] xl:text-[20px] font-bold">Critical</span><span id="stat-critical" class="text-[#F09A95] text-[22px] xl:text-[24px] font-bold">0</span></div>
                        </div>
                        <div class="flex w-full items-center">
                            <div class="w-1/2 flex items-center"><span class="w-[120px] xl:w-[150px] text-[18px] xl:text-[20px] font-bold">Min Temp</span><span class="text-[#9FD678] text-[22px] xl:text-[24px] font-bold"><span id="stat-min-temp">0</span><span class="text-[14px]">°C</span></span></div>
                            <div class="w-1/2 flex items-center pl-8 xl:pl-16"><span class="w-[140px] xl:w-[160px] text-[18px] xl:text-[20px] font-bold">Min Humidity</span><span class="text-[#9FD678] text-[22px] xl:text-[24px] font-bold"><span id="stat-min-hum">0</span><span class="text-[14px]">%</span></span></div>
                        </div>
                        <div class="flex w-full items-center">
                            <div class="w-1/2 flex items-center"><span class="w-[120px] xl:w-[150px] text-[18px] xl:text-[20px] font-bold">Max Temp</span><span class="text-[#F09A95] text-[22px] xl:text-[24px] font-bold"><span id="stat-max-temp">0</span><span class="text-[14px]">°C</span></span></div>
                            <div class="w-1/2 flex items-center pl-8 xl:pl-16"><span class="w-[140px] xl:w-[160px] text-[18px] xl:text-[20px] font-bold">Max Humidity</span><span class="text-[#9FD678] text-[22px] xl:text-[24px] font-bold"><span id="stat-max-hum">0</span><span class="text-[14px]">%</span></span></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Alert Status (Spesifik Lab) -->
            <div class="min-h-0 h-full w-full bg-[#313533] rounded-lg border border-gray-700 flex flex-col overflow-hidden" style="flex: 784;">
                <div class="bg-gradient-to-r from-[#1E87FF] to-[#295383] px-5 flex-none h-[40px] flex items-center">
                    <h2 class="text-white font-bold text-[22px] leading-none tracking-wide">Alert Status</h2>
                </div>
                <div class="flex-1 flex flex-col min-h-0 overflow-y-auto p-4 pr-2 pt-6 scrollbar-hide" id="lab-alert-container">
                    <div class="flex-1 flex flex-col items-center justify-center min-h-0 py-2 opacity-80">
                        <span class="text-gray-400 text-sm">Loading alerts...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- BARIS BAWAH (MINI GRAPH SVG - TEMPERATURE & HUMIDITY) -->
        <div class="flex gap-2 min-h-0 w-full" style="flex: 333;">
            
            <!-- Temperature Graph -->
            <div onclick="window.location.href='/graph?lab=' + encodeURIComponent(currentLabName)" class="flex-1 bg-[#2A2D2B] rounded-lg border border-gray-700 flex flex-col overflow-visible min-h-0 cursor-pointer hover:border-blue-400 transition-colors pb-1">
                <div class="bg-gradient-to-r from-[#1E87FF] to-[#295383] px-5 flex-none h-[36px] flex items-center rounded-t">
                    <h2 class="text-white font-bold text-[20px] leading-none tracking-wide">Temperature Graph</h2>
                </div>
                <div class="flex-1 flex flex-col mt-2 mr-4 min-h-0 pointer-events-none">
                    <div class="flex-1 flex min-h-0">
                        <div class="w-8 relative flex-none">
                            <span class="absolute right-2 text-gray-400 font-medium text-[9px] top-[0%]">- 50</span>
                            <span class="absolute right-2 text-gray-400 font-medium text-[9px] top-[50%]">- 25</span>
                            <span class="absolute right-2 text-gray-400 font-medium text-[9px] top-[100%]">- 0</span>
                        </div>
                        <div class="flex-1 relative border-l border-b border-gray-500 min-h-0" id="temp-graph-area">
                            <!-- Garis Batas & Polyline SVG Disini -->
                        </div>
                    </div>
                    <div id="temp-xaxis" class="h-[30px] relative ml-8 mr-0 flex-none transition-all duration-300"></div>
                </div>
            </div>

            <!-- Humidity Graph -->
            <div onclick="window.location.href='/graph?lab=' + encodeURIComponent(currentLabName)" class="flex-1 bg-[#2A2D2B] rounded-lg border border-gray-700 flex flex-col overflow-visible min-h-0 cursor-pointer hover:border-blue-400 transition-colors pb-1">
                <div class="bg-gradient-to-r from-[#1E87FF] to-[#295383] px-5 flex-none h-[36px] flex items-center rounded-t">
                    <h2 class="text-white font-bold text-[20px] leading-none tracking-wide">Humidity Graph</h2>
                </div>
                <div class="flex-1 flex flex-col mt-2 mr-4 min-h-0 pointer-events-none">
                    <div class="flex-1 flex min-h-0">
                        <div class="w-8 relative flex-none">
                            <span class="absolute right-2 text-gray-400 font-medium text-[9px] top-[0%]">- 90</span>
                            <span class="absolute right-2 text-gray-400 font-medium text-[9px] top-[50%]">- 45</span>
                            <span class="absolute right-2 text-gray-400 font-medium text-[9px] top-[100%]">- 0</span>
                        </div>
                        <div class="flex-1 relative border-l border-b border-gray-500 min-h-0" id="hum-graph-area">
                            <!-- Garis Batas & Polyline SVG Disini -->
                        </div>
                    </div>
                    <div id="hum-xaxis" class="h-[30px] relative ml-8 mr-0 flex-none transition-all duration-300"></div>
                </div>
            </div>

        </div>

    </div>

    <!-- ==================== JAVASCRIPT ENGINE ==================== -->
    <script>
        // Ambil parameter nama lab dari URL (contoh: /labs?lab=Grooming%20Lab%2001)
        const urlParams = new URLSearchParams(window.location.search);
        const currentLabName = urlParams.get('lab') || 'Grooming Lab 01';
        
        document.getElementById('page-meta-title').innerText = `Lab Monitoring - ${currentLabName}`;
        document.getElementById('topbar-title').innerText = `Lab Monitoring - ${currentLabName}`;

        let currentFilter = 'Daily';
        let customDateRange = null;

        // Clock Engine
        setInterval(() => {
            const now = new Date();
            const dateStr = now.toLocaleDateString('en-GB', { day: '2-digit', month: 'short' });
            const timeStr = now.toLocaleTimeString('en-GB', { hour12: false });
            document.getElementById('clock-display').innerText = `${dateStr} ${timeStr}`;
        }, 1000);

        function setFilter(filter) {
            currentFilter = filter;
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.style.backgroundColor = btn.innerText === filter ? '#34465A' : '#171717';
            });
            document.getElementById('custom-filter-inputs').style.display = filter === 'Custom' ? 'flex' : 'none';
            if (filter !== 'Custom') fetchSpecificData();
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
    
    // Panggil fungsi fetch yang BENAR untuk halaman Specific Lab
    fetchSpecificData(); 
    
    Swal.fire({
        icon: 'success',
        title: 'Filter Applied!',
        text: 'Lab Data is updated.',
        timer: 1500,
        showConfirmButton: false
    });
}

        // Fetch Data Specific Lab
        async function fetchSpecificData() {
            try {
                let rangeParam = '24h';
                let customQuery = '';
                if (currentFilter === 'Weekly') rangeParam = '7d';
                else if (currentFilter === 'Monthly') rangeParam = '30d';
                else if (currentFilter === 'Custom' && customDateRange) {
                    rangeParam = 'custom';
                    customQuery = `&start=${customDateRange.start}&end=${customDateRange.end}&interval=${customDateRange.interval}`;
                }

                const res = await fetch(`/api/overview/lab/${encodeURIComponent(currentLabName)}?range=${rangeParam}${customQuery}`);
                const raw = await res.json();
                if (raw.error) throw new Error(raw.error);

                renderSpecificLab(raw);
            } catch (err) {
                console.error("Gagal menarik data lab spesifik:", err);
            }
        }

        // Render Engine UI
        function renderSpecificLab({ latest, alerts, devices, rules, history }) {
            const myLatest = latest || {};
            const myAlerts = alerts || [];
            const myDevices = devices || [];
            
            // Connection & Last Seen
            const isConnected = myLatest.current_status === 'online';
            const badge = document.getElementById('lab-connection-badge');
            badge.innerText = isConnected ? 'Connected' : 'Disconnected';
            badge.className = `font-semibold ${isConnected ? 'text-[#9FD678]' : 'text-[#F09A95]'}`;

            if (myLatest.last_seen) {
                const d = new Date(myLatest.last_seen);
                document.getElementById('lab-last-seen').innerText = `${String(d.getDate()).padStart(2,'0')}-${["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"][d.getMonth()]} ${d.toTimeString().split(' ')[0]}`;
            } else {
                document.getElementById('lab-last-seen').innerText = 'Offline';
            }

            // Metrics Cards
            document.getElementById('metric-temp').innerText = myLatest.current_temp ? parseFloat(myLatest.current_temp).toFixed(1) : '0';
            document.getElementById('metric-hum').innerText = myLatest.current_hum ? parseFloat(myLatest.current_hum).toFixed(1) : '0';
            document.getElementById('metric-alerts').innerText = myAlerts.length;

            const isCal = myDevices.length > 0 && myDevices.every(d => d.calStatus === 'valid');
            const calEl = document.getElementById('metric-calibrated');
            calEl.innerText = isCal ? 'Yes' : 'No';
            calEl.className = `font-bold text-[36px] xl:text-[42px] leading-tight ${isCal ? 'text-[#9FD678]' : 'text-[#F09A95]'}`;

            // Rules Parsing
            let tLow = 15, tHigh = 25, hLow = 25, hHigh = 75;
            if (rules && rules.length > 0) {
                const tr = rules.filter(r => r.parameter === 'temperature' && r.severity === 'critical');
                const hr = rules.filter(r => r.parameter === 'humidity' && r.severity === 'critical');
                const tl = tr.find(r => r.operator === '<')?.threshold_value;
                const th = tr.find(r => r.operator === '>')?.threshold_value;
                const hl = hr.find(r => r.operator === '<')?.threshold_value;
                const hh = hr.find(r => r.operator === '>')?.threshold_value;
                if (tl !== undefined) tLow = parseFloat(tl); if (th !== undefined) tHigh = parseFloat(th);
                if (hl !== undefined) hLow = parseFloat(hl); if (hh !== undefined) hHigh = parseFloat(hh);
                
                document.getElementById('metric-req-temp').innerText = `${tLow}°C - ${tHigh}°C`;
                document.getElementById('metric-req-hum').innerText = `${hLow}%RH - ${hHigh}%RH`;
            }

            // History Calculation (Avg, Min, Max)
            let avgT = 0, avgH = 0, minT = 0, maxT = 0, minH = 0, maxH = 0;
            let safeHist = [];
            if (history && history.length > 0) {
                safeHist = history.filter(h => h.temperature !== null && h.humidity !== null).map(h => ({
                    ...h, temperature: parseFloat(h.temperature), humidity: parseFloat(h.humidity)
                }));
                if (safeHist.length > 0) {
                    const ts = safeHist.map(h => h.temperature);
                    const hs = safeHist.map(h => h.humidity);
                    avgT = (ts.reduce((a,b)=>a+b,0)/ts.length).toFixed(1);
                    avgH = (hs.reduce((a,b)=>a+b,0)/hs.length).toFixed(1);
                    minT = Math.min(...ts); maxT = Math.max(...ts);
                    minH = Math.min(...hs); maxH = Math.max(...hs);
                }
            }

            document.getElementById('stat-avg-temp').innerText = avgT;
            document.getElementById('stat-avg-hum').innerText = avgH;
            document.getElementById('stat-min-temp').innerText = minT;
            document.getElementById('stat-max-temp').innerText = maxT;
            document.getElementById('stat-min-hum').innerText = minH;
            document.getElementById('stat-max-hum').innerText = maxH;

            const warnings = myAlerts.filter(a => a.level === 'warning').length;
            const criticals = myAlerts.filter(a => a.level === 'critical').length;
            document.getElementById('stat-warning').innerText = warnings;
            document.getElementById('stat-critical').innerText = criticals;

            // Render Alert List
            const alertBox = document.getElementById('lab-alert-container');
            if (myAlerts.length === 0) {
                alertBox.innerHTML = `
                    <div class="flex-1 flex flex-col items-center justify-center min-h-0 py-2 opacity-80 mt-10">
                        <svg class="w-16 h-16 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-gray-300 text-[18px] font-semibold tracking-wide">No Active Alerts</span>
                        <span class="text-gray-500 text-[13px] mt-1 text-center">All parameters are within normal limits.</span>
                    </div>`;
} else {
                alertBox.innerHTML = myAlerts.map(a => {
                    // LOGIKA BARU: Format Waktu & Tanggal
                    let timeDetail = 'N/A';
                    if (a.triggered_at) {
                        const d = new Date(a.triggered_at);
                        const day = String(d.getDate()).padStart(2, '0');
                        const month = d.toLocaleString('en-US', { month: 'short' }); 
                        const hours = String(d.getHours()).padStart(2, '0');
                        const minutes = String(d.getMinutes()).padStart(2, '0');
                        timeDetail = `${day} ${month}, ${hours}:${minutes}`; 
                    }

                    let val = parseFloat(a.triggered_value || 0);
                    let isCrit = a.level === 'critical' || a.parameter === 'status' || val === 0;
                    
                    let txt = a.parameter === 'status' 
                        ? `Device is Disconnected (Since ${timeDetail})` 
                        : (val === 0 
                            ? `Sensor Error / Disconnected at ${timeDetail}` 
                            : `${a.parameter.charAt(0).toUpperCase() + a.parameter.slice(1)} limit exceeded (${val}) at ${timeDetail}`);
                    
                    return `
                    <div class="flex items-center gap-4 text-white border-b border-gray-600/30 pb-4 last:border-0 last:pb-0">
                        <div class="w-3.5 h-3.5 rounded-full ${!isCrit ? 'bg-yellow-500' : 'bg-[#A71818]'} border border-white flex-none shadow-sm"></div>
                        <span class="text-[14px] xl:text-[15px] font-normal leading-tight">${txt}</span>
                    </div>`;
                }).join('');
            }

            // Render Graphs (SVG Temperature & Humidity)
            renderGraph('temp', safeHist, 'temperature', 50, tHigh, tLow, '#FBBF24');
            renderGraph('hum', safeHist, 'humidity', 90, hHigh, hLow, '#2DD4BF');
        }

        function renderGraph(type, historyData, key, maxVal, limitMax, limitMin, strokeColor) {
            const area = document.getElementById(`${type}-graph-area`);
            const xaxis = document.getElementById(`${type}-xaxis`);
            
            if (historyData.length === 0) {
                area.innerHTML = '';
                xaxis.innerHTML = '';
                return;
            }

            const points = historyData.map((item, i) => {
                const x = (i / (historyData.length - 1 || 1)) * 100;
                const y = 100 - (item[key] / maxVal * 100);
                return `${x},${y}`;
            }).join(' ');

            const topMax = 100 - (limitMax / maxVal * 100);
            const topMin = 100 - (limitMin / maxVal * 100);

            area.innerHTML = `
                <div class="absolute w-full border-t border-[${strokeColor}]/40 border-dashed z-0" style="top: ${topMax}%;"></div>
                <div class="absolute w-full border-t border-[${strokeColor}]/40 border-dashed z-0" style="top: ${topMin}%;"></div>
                <svg class="absolute inset-0 w-full h-full overflow-visible z-10" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <polyline points="${points}" fill="none" stroke="${strokeColor}" stroke-width="2" vector-effect="non-scaling-stroke" stroke-linejoin="round" />
                </svg>
                <div class="absolute inset-0 w-full h-full z-20">
                    ${historyData.map((item, i) => {
                        const x = (i / (historyData.length - 1 || 1)) * 100;
                        const y = 100 - (item[key] / maxVal * 100);
                        return `
                        <div class="absolute w-4 h-full -ml-2 group pointer-events-auto" style="left: ${x}%; top: 0%;">
                            <div class="absolute w-2.5 h-2.5 bg-[${strokeColor}] rounded-full opacity-0 group-hover:opacity-100 transition-opacity transform -translate-x-1/2 -translate-y-1/2" style="left: 50%; top: ${y}%;"></div>
                            <div class="absolute left-1/2 transform -translate-x-1/2 -translate-y-full hidden group-hover:flex flex-col items-center bg-[#111111]/95 border border-[${strokeColor}] text-white px-3 py-1.5 rounded shadow-2xl w-max z-50" style="top: calc(${y}% - 12px);">
                                <span class="text-[#9FD678] font-bold text-[13px] whitespace-nowrap">${item[key]} ${type === 'temp' ? '°C' : '%RH'}</span>
                                <span class="text-gray-300 text-[10px] mt-0.5 whitespace-nowrap">${item.time || ''}</span>
                            </div>
                        </div>`;
                    }).join('')}
                </div>`;

            // X-Axis Ticks
            const maxTicks = 6;
            const ticks = [];
            const step = Math.max(1, Math.floor((historyData.length - 1) / (maxTicks - 1)));
            for (let i = 0; i < historyData.length; i += step) {
                let disp = historyData[i].time || '';
                if (currentFilter === 'Daily' && disp.includes(' ')) disp = disp.split(' ')[2];
                ticks.push({ index: i, displayTime: disp });
            }
            xaxis.className = currentFilter === 'Daily' ? 'h-[30px] relative ml-8 mr-0 flex-none' : 'h-[50px] relative ml-8 mr-0 flex-none';
            xaxis.innerHTML = ticks.map(t => `
                <div class="absolute top-0" style="left: ${(t.index / (historyData.length - 1 || 1)) * 100}%;">
                    <div class="w-[1px] h-[3px] bg-gray-400 absolute top-0 -ml-[0.5px]"></div>
                    <div class="absolute top-[6px] right-0 flex justify-end overflow-visible"><span class="block text-gray-400 text-[9px] whitespace-nowrap transform -rotate-45 origin-top-right pr-1">${t.displayTime}</span></div>
                </div>`).join('');
        }

        // Init Load
        fetchSpecificData();
        setInterval(fetchSpecificData, 60000);
    </script>
</body>
</html>