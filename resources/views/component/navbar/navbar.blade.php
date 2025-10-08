<nav class="bg-white shadow-md px-6 py-3 flex items-center justify-between">
    {{-- Kiri: Logo + Nama + Tanggal/Jam --}}
    <div class="flex items-center gap-3">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10">
        <div>
            <h1 class="text-xl font-semibold text-gray-700">TOWER BERSAMA GROUP</h1>
            <p class="text-sm text-gray-500">
                Sel, 7 Oktober 2025, <span id="current-time"></span>
            </p>
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
        <span class="text-gray-600 font-medium">Dea Binti Ingrid</span>
        <div class="bg-gray-400 rounded-full w-8 h-8 flex items-center justify-center">
            <i class="fas fa-user text-white"></i>
        </div>
    </div>
</nav>

{{-- Script Jam --}}
<script>
    function updateClock() {
        const now = new Date();
        document.getElementById("current-time").textContent = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
