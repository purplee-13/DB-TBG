<nav class="bg-white shadow-md px-6 py-3 flex items-center justify-between relative">
    <div class="flex items-center gap-3">
        <div><a href="{{ url('/dashboard') }}"><img src="{{ asset('assets/icon/tbg.png') }}" alt="Logo" class="h-10"></a></div>
        <div class="flex items-center gap-1">
            <span class="material-symbols-outlined text-[#022CB8]">calendar_clock</span>
            <p class="text-sm text-[#022CB8]"><span id="current-date"></span>, <span id="current-time"></span></p>
        </div>
    </div>

    {{-- Tengah: Menu --}}
    <ul class="flex gap-6 text-gray-700 font-medium">
        <li><a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'text-[#022CB8] font-semibold border-b-2 border-[#022CB8] pb-1 transition-all duration-300' : 'hover:text-blue-600 hover:border-b-2 hover:border-blue-600 pb-1 transition-all duration-300' }}">Dashboard</a></li>
        <li><a href="{{ url('/datasite') }}" class="{{ request()->is('datasite') ? 'text-[#022CB8] font-semibold border-b-2 border-[#022CB8] pb-1 transition-all duration-300' : 'hover:text-blue-600 hover:border-b-2 hover:border-blue-600 pb-1 transition-all duration-300' }}">Data Site</a></li>
        <li><a href="{{ url('/update-maintenance') }}" class="{{ request()->is('update-maintenance') ? 'text-[#022CB8] font-semibold border-b-2 border-[#022CB8] pb-1 transition-all duration-300' : 'hover:text-blue-600 hover:border-b-2 hover:border-blue-600 pb-1 transition-all duration-300' }}">Update Maintenance</a></li>
    </ul>

    {{-- Kanan: Profil --}}
    <div class="relative">
        <div id="profile-btn" class="flex items-center gap-2 cursor-pointer">
            <span class="text-black-600 font-medium">{{ session('name') }}</span>
            <div class="bg-black rounded-full w-8 h-8 flex items-center justify-center">
                <span class="material-symbols-outlined text-white">person</span>
            </div>
        </div>

        {{-- Popup Logout --}}
        <div id="logout-popup" class="hidden absolute right-0 top-12 bg-white shadow-lg border rounded-xl py-2 w-36 z-50">
            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit" class="w-full text-red-600 hover:bg-red-50 py-2 rounded-lg transition-all duration-200">
                    <span class="material-symbols-outlined align-middle text-red-600">logout</span>
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

{{-- Script Jam --}}
<script>
    function updateDateTime() {
        const now = new Date();
        const witaTime = new Date(now.toLocaleString('en-US', { timeZone: 'Asia/Makassar' }));
        const dateOptions = { weekday: 'short', day: 'numeric', month: 'long', year: 'numeric' };
        const dateString = witaTime.toLocaleDateString('id-ID', dateOptions);
        const timeString = witaTime.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        });
        document.getElementById("current-date").textContent = dateString;
        document.getElementById("current-time").textContent = timeString;
    }
    setInterval(updateDateTime, 1000);
    updateDateTime();

    // Script popup logout
    const profileBtn = document.getElementById("profile-btn");
    const logoutPopup = document.getElementById("logout-popup");

    profileBtn.addEventListener("click", () => {
        logoutPopup.classList.toggle("hidden");
    });

    // Klik di luar popup untuk menutup
    window.addEventListener("click", (e) => {
        if (!profileBtn.contains(e.target) && !logoutPopup.contains(e.target)) {
            logoutPopup.classList.add("hidden");
        }
    });
</script>
