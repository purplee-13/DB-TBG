<nav class="bg-white shadow-md px-6 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <img src="{{ asset('assets/icon/tbg.png') }}" alt="Logo" class="h-10">
        <div class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[#022CB8]">calendar_clock</span>
                <p class="text-sm text-[#022CB8]"><span id="current-date"></span>, <span id="current-time"></span></p>
            </div>
    </div>

    <ul class="flex gap-6 text-gray-700 font-medium">
        <li><a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'text-[#022CB8] font-semibold border-b-2 border-[#022CB8] pb-1 transition-all duration-300' : 'hover:text-blue-600 hover:border-b-2 hover:border-blue-600 pb-1 transition-all duration-300' }}">Dashboard</a></li>
        <li><a href="{{ url('/datasite') }}"  class="{{ request()->is('datasite') ? 'text-[#022CB8] font-semibold border-b-2 border-[#022CB8] pb-1 transition-all duration-300' : 'hover:text-blue-600 hover:border-b-2 hover:border-blue-600 pb-1 transition-all duration-300' }}">Data Site</a></li>
        <li><a href="{{ url('/update-maintenance') }}" class="{{ request()->is('update-maintenance') ? 'text-[#022CB8] font-semibold border-b-2 border-[#022CB8] pb-1 transition-all duration-300' : 'hover:text-blue-600 hover:border-b-2 hover:border-blue-600 pb-1 transition-all duration-300' }}">Update Maintenance</a></li>
        <li><a href="{{ url('/kelola-pengguna') }}" class="{{ request()->is('kelola-pengguna') ? 'text-[#022CB8] font-semibold border-b-2 border-[#022CB8] pb-1 transition-all duration-300' : 'hover:text-blue-600 hover:border-b-2 hover:border-blue-600 pb-1 transition-all duration-300' }}">Kelola Pengguna</a></li>
    </ul>

    <div class="flex items-center gap-2">
        <span class="text-black-600 font-medium">{{ session('name') }}</span>
        <div class="bg-black rounded-full w-8 h-8 flex items-center justify-center">
            <span class="material-symbols-outlined text-white">person</span>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-red-600 hover:underline text-sm">Logout</button>
        </form>
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