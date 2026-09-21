<div class="w-full h-full bg-[#313533] rounded-lg border border-gray-700 flex flex-col overflow-hidden">
    <div class="bg-gradient-to-r from-[#1E87FF] to-[#295383] px-5 h-[40px] flex-none flex items-center">
        <h2 class="text-white font-bold text-[22px] leading-none tracking-wide">Connection Status</h2>
    </div>
    <div class="flex-1 p-2 flex items-center justify-center min-h-0">
        <div class="relative flex-shrink-0" style="max-width: 100%; max-height: 100%; aspect-ratio: 1920 / 1080;">
            <img src="{{ asset('assets/images/map.png') }}" alt="Map" class="block w-full h-full object-fill border border-white" />
            <!-- Container untuk titik map -->
            <div id="map-dots-container"></div>
        </div>
    </div>
</div>