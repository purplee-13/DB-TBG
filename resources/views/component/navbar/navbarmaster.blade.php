<nav class="bg-white shadow-md px-6 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <img src="{{ asset('assets/icon/tbg.png') }}" alt="Logo" class="h-10">
        <div class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[#022CB8]">calendar_clock</span>
                <p class="text-sm text-[#022CB8]">Sel, 7 Oktober 2025, <span id="current-time"></span></p>
            </div>
    </div>

    <ul class="flex gap-6 text-gray-700 font-medium">
        <li><a href="#" class="hover:text-[#022CB8]">Dashboard</a></li>
        <li><a href="#" class="hover:text-[#022CB8]">kelola Pengguna</a></li>
    </ul>

    <div class="flex items-center gap-2">
        <span class="text-black-600 font-medium">{{ session('name') }}</span>
        <div class="bg-black rounded-full w-8 h-8 flex items-center justify-center">
            <span class="material-symbols-outlined text-white">person</span>
        </div>
    </div>
</nav>

<script>
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false,
            timeZone: 'Asia/Makassar'
        });
        document.getElementById("current-time").textContent = timeString;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>