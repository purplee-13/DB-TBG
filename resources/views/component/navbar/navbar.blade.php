<nav class="bg-white shadow-md px-6 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div><a href="{{ url('/') }}"><img src="{{ asset('assets/icon/tbg.png') }}" alt="Logo" class="h-10"></a></div>
        <div class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[#022CB8]">calendar_clock</span>
                <p class="text-sm text-[#022CB8]">Rab, 8 Oktober 2025, <span id="current-time"></span></p>
            </div>
    </div>

    {{-- Tengah: Menu --}}
    <ul class="flex gap-6 text-gray-700 font-medium">
        <li><a href="{{ url('/') }}" class="hover:text-blue-600">Dashboard</a></li>
        <li><a href="{{ url('/datasite') }}" class="hover:text-blue-600">Data Site</a></li>
        <li><a href="{{ url('/update-maintenance') }}" class="hover:text-blue-600">Update Maintenance</a></li>
    </ul>

    {{-- Kanan: Profil --}}
    <div class="flex items-center gap-2">
        <span class="text-black-600 font-medium">Dea Binti Ingrid</span>
        <div class="bg-black rounded-full w-8 h-8 flex items-center justify-center">
            <span class="material-symbols-outlined text-white">person</span>
        </div>
    </div>
</nav>

{{-- Script Jam --}}
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