<nav class="bg-white shadow-md px-6 py-3 flex items-center justify-between relative">
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

    <!-- Profil dan Popup -->
    <div class="flex items-center gap-3 relative">
        <span class="text-black font-medium">{{ session('name') }}</span>

        <!-- Tombol Icon Person -->
        <button id="profile-btn" class="bg-black rounded-full w-8 h-8 flex items-center justify-center relative">
            <span class="material-symbols-outlined text-white">person</span>
        </button>

        <div id="logout-popup" class="hidden absolute right-0 top-12 bg-white shadow-lg border rounded-xl py-3 w-56 z-50">
            {{-- Info Profil --}}
            <div class="px-4 pb-2 border-b border-gray-200 text-left">
                <p class="font-semibold text-gray-800 text-sm">{{ session('name') }}</p>
                <p class="text-gray-500 text-xs">{{ session('email') }}</p>
                @if (session('role'))
                    <p class="text-gray-500 text-xs italic">{{ ucfirst(session('role')) }}</p>
                @endif
            </div>

        <!-- Popup Logout -->
        <form method="POST" action="{{ route('logout') }}" class="text-center mt-2">
                @csrf
                <button type="submit"
                    class="w-full text-red-600 hover:bg-red-50 py-2 rounded-lg transition-all duration-200">
                    <span class="material-symbols-outlined align-middle text-red-600">logout</span>
                    Logout
                </button>
        </form>
    </div>
</nav>

<!-- Jam real-time -->
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

    // Toggle popup logout
    const profileBtn = document.getElementById('profile-btn');
    const popup = document.getElementById('logout-popup');

    profileBtn.addEventListener('click', (e) => {
        e.stopPropagation(); // cegah event bubbling
        popup.classList.toggle('hidden');
    });

    // Klik di luar popup → popup tertutup
    document.addEventListener('click', (e) => {
        if (!popup.contains(e.target) && !profileBtn.contains(e.target)) {
            popup.classList.add('hidden');
        }
    });
</script>
