<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="page-meta-title">Lab Monitoring - Graph</title>
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
                <a href="#" onclick="window.history.back()" class="font-bold text-white hover:text-blue-400 cursor-pointer transition-colors" style="font-size: 26px;" id="topbar-title">
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

    <!-- ==================== MAIN GRAPHS & EXPORT ==================== -->
    <div class="flex-1 w-full bg-[#1F2221] rounded-lg border border-gray-700 flex flex-col overflow-hidden min-h-0 relative">
        
        <div id="loading-overlay" class="absolute inset-0 bg-[#1F2221]/80 backdrop-blur-sm z-50 flex items-center justify-center">
            <span class="text-white font-bold text-lg animate-pulse">Loading Data...</span>
        </div>

        <div class="bg-gradient-to-r from-[#1E87FF] to-[#295383] px-4 flex-none border-b border-gray-600 h-[38px] flex items-center">
            <h2 class="text-white font-bold text-[22px] leading-none tracking-wide">Temperature and Humidity Graph</h2>
        </div>

        <div class="flex-1 flex flex-col p-2 gap-2 min-h-0 relative">
            
            <!-- Export Buttons -->
            <div class="flex justify-end gap-2 flex-none pr-1 mt-1">
                <button onclick="handleExportCSV()" class="w-[110px] h-[28px] bg-[#171717] border border-gray-400 flex items-center justify-center text-white text-[12px] font-medium transition hover:bg-[#34465A] rounded-none">
                    Export CSV
                </button>
                <button onclick="handleExportForm()" class="w-[110px] h-[28px] bg-[#171717] border border-gray-400 flex items-center justify-center text-white text-[12px] font-medium transition hover:bg-[#34465A] rounded-none">
                    Export Form
                </button>
            </div>

            <!-- Temperature Graph -->
            <div class="flex-1 bg-[#2A2D2B] rounded border border-gray-700 flex flex-col overflow-visible min-h-0 pb-2">
                <div class="bg-gradient-to-r from-[#1E87FF] to-[#295383] px-4 h-[34px] flex-none flex items-center rounded-t">
                    <h3 class="text-white font-bold text-[18px] leading-none">Temperature Graph</h3>
                    <div class="ml-3 bg-[#171717] border border-gray-600 rounded-sm px-2 py-0.5 flex items-baseline gap-1 shadow-sm h-[22px]">
                        <span id="current-temp" class="text-white font-bold text-[12px] leading-none">0</span>
                        <span class="text-gray-300 text-[10px]">°C</span>
                    </div>
                </div>
                <div class="flex-1 flex flex-col mt-2 mr-6 min-h-0">
                    <div class="flex-1 flex min-h-0">
                        <div class="w-10 relative flex-none">
                            <span class="absolute right-2 text-gray-300 font-medium text-[10px] top-[0%] transform -translate-y-1/2">50</span>
                            <span class="absolute right-2 text-gray-300 font-medium text-[10px] top-[20%] transform -translate-y-1/2">40</span>
                            <span class="absolute right-2 text-gray-300 font-medium text-[10px] top-[40%] transform -translate-y-1/2">30</span>
                            <span class="absolute right-2 text-gray-300 font-medium text-[10px] top-[60%] transform -translate-y-1/2">20</span>
                            <span class="absolute right-2 text-gray-300 font-medium text-[10px] top-[80%] transform -translate-y-1/2">10</span>
                            <span class="absolute right-2 text-gray-300 font-medium text-[10px] top-[100%] transform -translate-y-1/2">0</span>
                        </div>
                        <div id="temp-graph-area" class="flex-1 relative border-l border-b border-gray-400 min-h-0 transition-all duration-300"></div>
                    </div>
                    <div id="temp-xaxis" class="h-[35px] relative ml-10 mr-0 flex-none transition-all duration-300"></div>
                </div>
            </div>

            <!-- Humidity Graph -->
            <div class="flex-1 bg-[#2A2D2B] rounded border border-gray-700 flex flex-col overflow-visible min-h-0 pb-2">
                <div class="bg-gradient-to-r from-[#1E87FF] to-[#295383] px-4 h-[34px] flex-none flex items-center rounded-t">
                    <h3 class="text-white font-bold text-[18px] leading-none">Humidity Graph</h3>
                    <div class="ml-3 bg-[#171717] border border-gray-600 rounded-sm px-2 py-0.5 flex items-baseline gap-1 shadow-sm h-[22px]">
                        <span id="current-hum" class="text-white font-bold text-[12px] leading-none">0</span>
                        <span class="text-gray-300 text-[10px]">%RH</span>
                    </div>
                </div>
                <div class="flex-1 flex flex-col mt-2 mr-6 min-h-0">
                    <div class="flex-1 flex min-h-0">
                        <div class="w-10 relative flex-none">
                            <span class="absolute right-2 text-gray-300 font-medium text-[10px] top-[0%] transform -translate-y-1/2">90</span>
                            <span class="absolute right-2 text-gray-300 font-medium text-[10px] top-[16.66%] transform -translate-y-1/2">75</span>
                            <span class="absolute right-2 text-gray-300 font-medium text-[10px] top-[33.33%] transform -translate-y-1/2">60</span>
                            <span class="absolute right-2 text-gray-300 font-medium text-[10px] top-[50%] transform -translate-y-1/2">45</span>
                            <span class="absolute right-2 text-gray-300 font-medium text-[10px] top-[66.66%] transform -translate-y-1/2">30</span>
                            <span class="absolute right-2 text-gray-300 font-medium text-[10px] top-[83.33%] transform -translate-y-1/2">15</span>
                            <span class="absolute right-2 text-gray-300 font-medium text-[10px] top-[100%] transform -translate-y-1/2">0</span>
                        </div>
                        <div id="hum-graph-area" class="flex-1 relative border-l border-b border-gray-400 min-h-0 transition-all duration-300"></div>
                    </div>
                    <div id="hum-xaxis" class="h-[35px] relative ml-10 mr-0 flex-none transition-all duration-300"></div>
                </div>
            </div>

        </div>
    </div>

    <!-- ==================== JAVASCRIPT ENGINE ==================== -->
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const currentLabName = urlParams.get('lab') || 'Grooming Lab 01';
        
        document.getElementById('page-meta-title').innerText = `Lab Monitoring - Graph ${currentLabName}`;
        document.getElementById('topbar-title').innerText = `Lab Monitoring - ${currentLabName}`;

        let currentFilter = 'Daily';
        let customDateRange = null;
        let globalGraphData = []; 

        const TEMP_MAX = 50;
        const HUM_MAX = 90;

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
            document.getElementById('temp-xaxis').className = filter === 'Daily' ? 'h-[35px] relative ml-10 mr-0 flex-none' : 'h-[70px] relative ml-10 mr-0 flex-none';
            document.getElementById('hum-xaxis').className = filter === 'Daily' ? 'h-[35px] relative ml-10 mr-0 flex-none' : 'h-[70px] relative ml-10 mr-0 flex-none';
            
            if (filter !== 'Custom') fetchGraphData();
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
    
    // Simpan range dengan jam presisi
    customDateRange = { 
        start: `${startDate} ${startTime}:00`, 
        end: `${endDate} ${endTime}:00`, 
        interval: interval 
    };
    
    Swal.fire({
        icon: 'success',
        title: 'Filter Applied!',
        text: 'Graph Data is updated.',
        timer: 1500,
        showConfirmButton: false
    });
    // Ganti dengan fungsi fetch masing-masing (fetchDashboardData / fetchSpecificData / fetchGraphData)
    fetchGraphData(); 
}


        // --- FETCH & RENDER LOGIC ---
        async function fetchGraphData() {
            document.getElementById('loading-overlay').classList.remove('hidden');
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

                let safeHistory = [];
                if (raw.history && raw.history.length > 0) {
                    safeHistory = raw.history.filter(h => h.temperature !== null && h.humidity !== null).map(h => ({
                        ...h, temperature: parseFloat(h.temperature), humidity: parseFloat(h.humidity)
                    }));
                }
                
                globalGraphData = safeHistory;

                if (safeHistory.length > 0) {
                    const last = safeHistory[safeHistory.length - 1];
                    document.getElementById('current-temp').innerText = last.temperature;
                    document.getElementById('current-hum').innerText = last.humidity;
                } else {
                    document.getElementById('current-temp').innerText = '0';
                    document.getElementById('current-hum').innerText = '0';
                }

                const myLatest = raw.latest || {};
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

                renderGraph('temp', safeHistory, 'temperature', TEMP_MAX, 25, 15, '#FBBF24');
                renderGraph('hum', safeHistory, 'humidity', HUM_MAX, 75, 25, '#2DD4BF');

            } catch (err) {
                console.error("Gagal menarik data Graph:", err);
            } finally {
                document.getElementById('loading-overlay').classList.add('hidden');
            }
        }

        function renderGraph(type, data, key, maxVal, limitMax, limitMin, strokeColor) {
            const area = document.getElementById(`${type}-graph-area`);
            const xaxis = document.getElementById(`${type}-xaxis`);
            
            if (data.length === 0) {
                area.innerHTML = ''; xaxis.innerHTML = ''; return;
            }

            const points = data.map((item, i) => {
                const x = (i / (data.length - 1 || 1)) * 100;
                const y = 100 - (item[key] / maxVal * 100);
                return `${x},${y}`;
            }).join(' ');

            const topMax = 100 - (limitMax / maxVal * 100);
            const topMin = 100 - (limitMin / maxVal * 100);

            area.innerHTML = `
                <div class="absolute w-full border-t border-[${strokeColor}]/40 border-dashed z-0 pointer-events-none" style="top: ${topMax}%;"></div>
                <div class="absolute w-full border-t border-[${strokeColor}]/40 border-dashed z-0 pointer-events-none" style="top: ${topMin}%;"></div>
                <svg class="absolute inset-0 w-full h-full overflow-visible z-10 pointer-events-none" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <polyline points="${points}" fill="none" stroke="${strokeColor}" stroke-width="2.5" vector-effect="non-scaling-stroke" stroke-linejoin="round" />
                </svg>
                <div class="absolute inset-0 w-full h-full z-20">
                    ${data.map((item, i) => {
                        const x = (i / (data.length - 1 || 1)) * 100;
                        const y = 100 - (item[key] / maxVal * 100);
                        return `
                        <div class="absolute w-4 h-full -ml-2 group cursor-pointer pointer-events-auto" style="left: ${x}%; top: 0%;">
                            <div class="absolute w-2.5 h-2.5 bg-[${strokeColor}] rounded-full shadow-[0_0_8px_2px_${type === 'temp' ? 'rgba(251,191,36,0.6)' : 'rgba(45,212,191,0.6)'}] opacity-0 group-hover:opacity-100 transition-opacity duration-200 transform -translate-x-1/2 -translate-y-1/2" style="left: 50%; top: ${y}%;"></div>
                            <div class="absolute left-1/2 transform -translate-x-1/2 -translate-y-full hidden group-hover:flex flex-col items-center bg-[#111111]/95 backdrop-blur-sm border border-[${strokeColor}] text-white px-3 py-1.5 rounded shadow-2xl min-w-max z-50" style="top: calc(${y}% - 12px);">
                                <span class="text-[#9FD678] font-bold text-[13px] leading-tight whitespace-nowrap">${item[key]} ${type === 'temp' ? '°C' : '%RH'}</span>
                                <span class="text-gray-300 text-[10px] mt-0.5 whitespace-nowrap">${item.time || ''}</span>
                                <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-[5px] border-r-[5px] border-t-[5px] border-transparent border-t-[${strokeColor}]"></div>
                            </div>
                        </div>`;
                    }).join('')}
                </div>`;

            // X-Axis Ticks
            const maxTicks = 10;
            const ticks = [];
            const step = Math.max(1, Math.floor((data.length - 1) / (maxTicks - 1)));
            for (let i = 0; i < data.length; i += step) {
                let disp = data[i].time || '';
                if (currentFilter === 'Daily' && disp.includes(' ')) disp = disp.split(' ')[2];
                ticks.push({ index: i, displayTime: disp });
            }
            if (ticks[ticks.length - 1].index !== data.length - 1) {
                let disp = data[data.length - 1].time || '';
                if (currentFilter === 'Daily' && disp.includes(' ')) disp = disp.split(' ')[2];
                ticks.push({ index: data.length - 1, displayTime: disp });
            }

            xaxis.innerHTML = ticks.map(t => `
                <div class="absolute top-0 transition-all duration-300" style="left: ${(t.index / (data.length - 1 || 1)) * 100}%;">
                    <div class="w-[1.5px] h-[5px] bg-gray-400 absolute top-0 -ml-[0.75px]"></div>
                    <div class="absolute top-[8px] right-0 w-0 h-0 flex justify-end overflow-visible">
                        <span class="block text-gray-400 text-[10px] xl:text-[11px] whitespace-nowrap transform -rotate-45 origin-top-right pr-1">${t.displayTime}</span>
                    </div>
                </div>`).join('');
        }

        // --- EXPORT CSV ---
        function handleExportCSV() {
            if (globalGraphData.length === 0) {
                Swal.fire('Info', 'Tidak ada data untuk di-export pada rentang waktu ini.', 'info');
                return;
            }
            let csvContent = "Time,Temperature (C),Humidity (%RH)\n";
            globalGraphData.forEach(row => {
                csvContent += `${row.time},${row.temperature},${row.humidity}\n`;
            });
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement("a");
            link.href = url;
            link.download = `Export_${currentLabName.replace(/\s+/g, '_')}_${currentFilter}.csv`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // --- EXPORT FORM (EXCEL) ---
        async function handleExportForm() {
            try {
                let startDate = new Date();
                let endDate = new Date();
                startDate.setDate(endDate.getDate() - 30); // Default monthly retro
                if (currentFilter === 'Custom' && customDateRange) {
                    startDate = new Date(customDateRange.start.split(' ')[0]);
                    endDate = new Date(customDateRange.end.split(' ')[0]);
                }

                const startStr = startDate.toISOString().split('T')[0];
                const endStr = endDate.toISOString().split('T')[0];
                const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                const startMonth = monthNames[startDate.getMonth()];
                const endMonth = monthNames[endDate.getMonth()];
                const fileSuffix = startMonth === endMonth ? `${startMonth}_${endDate.getFullYear()}` : `${startMonth}-${endMonth}_${endDate.getFullYear()}`;

                const url = `/api/export/download-form?labName=${encodeURIComponent(currentLabName)}&startDate=${startStr}&endDate=${endStr}`;
                const response = await fetch(url);

                if (!response.ok) {
            let errorText = "Gagal mengunduh form dari backend";
            try {
                // Coba parse ke JSON terlebih dahulu
                const errorJson = await response.json();
                errorText = errorJson.error || errorText;
            } catch (parseErr) {
                // Jika gagal (masih HTML), potong agar tidak memenuhi layar
                const rawText = await response.text();
                errorText = rawText.length > 200 ? "Fatal Backend Error. Cek laravel.log" : rawText;
            }
            throw new Error(errorText);
        }

                const blob = await response.blob();
                const downloadUrl = URL.createObjectURL(blob);
                const link = document.createElement("a");
                link.href = downloadUrl;
                link.download = `Control_Form_${currentLabName.replace(/\s+/g, '_')}_${fileSuffix}.xlsx`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(downloadUrl);
            } catch (error) {
                console.error("Export Form Error:", error);
                Swal.fire('Gagal Export', 'Terjadi kesalahan saat mengunduh form. Pastikan backend sudah merespons Excel dengan benar.', 'error');
            }
        }

        // Init
        fetchGraphData();
    </script>
</body>
</html>