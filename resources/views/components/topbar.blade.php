<div class="flex flex-col w-full pb-1.5 border-b border-gray-700/50 mb-1">
    <!-- BARIS 1 -->
    <div class="flex justify-between items-center w-full mb-1">
        <div class="flex-1 flex justify-start min-w-max">
            <a href="#" class="font-bold text-white hover:text-blue-400 transition-colors" style="font-size: 26.46px;">
                Lab Monitoring - Overall Status
            </a>
        </div>
        <!-- Jam (Bisa diklik untuk login) -->
        <div class="flex-1 flex justify-end min-w-max cursor-pointer hover:opacity-75 transition-opacity" onclick="window.location.href='/users'" title="Admin Login">
            <p id="current-time-display" class="font-bold text-white tracking-wide select-none" style="font-size: 26.46px;">
                --:--:--
            </p>
        </div>
    </div>

    <!-- BARIS 2 -->
    <div class="flex justify-between items-end w-full mt-1">
        <!-- Filter Tabs -->
        <div class="flex-1 flex justify-start min-w-max">
            <div class="flex border border-white rounded-none" id="filter-buttons-container">
                <button onclick="setFilter('Daily')" class="filter-btn flex items-center justify-center font-['Arial'] border-r border-white transition" style="width: 126px; height: 27.36px; background-color: #34465A; font-size: 14.7px; color: white;">Daily</button>
                <button onclick="setFilter('Weekly')" class="filter-btn flex items-center justify-center font-['Arial'] border-r border-white transition" style="width: 126px; height: 27.36px; background-color: #171717; font-size: 14.7px; color: white;">Weekly</button>
                <button onclick="setFilter('Monthly')" class="filter-btn flex items-center justify-center font-['Arial'] border-r border-white transition" style="width: 126px; height: 27.36px; background-color: #171717; font-size: 14.7px; color: white;">Monthly</button>
            </div>
        </div>

        <!-- Status Koneksi -->
        <div class="flex-1 flex justify-end items-center gap-2 text-gray-400 pb-1 min-w-max" style="font-size: 14.7px;">
            <span>Last: <span id="last-update-time">--</span></span>
            <span>|</span>
            <span class="text-[#9FD678] font-semibold"><span id="top-connected">0</span> Connected</span>
            <span>|</span>
            <span class="text-[#F09A95] font-semibold"><span id="top-disconnected">0</span> Disconnected</span>
        </div>
    </div>
</div>