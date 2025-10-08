<nav class="bg-white shadow-md px-6 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <img src="{{ asset('assets/icon/tbg.png') }}" alt="Logo" class="h-10">
        <div>
        <h1 class="text-xl font-semibold text-gray-700">TOWER BERSAMA GROUP</h1>
            <span class="material-symbols-outlined">calendar_clock</span><p class="text-sm text-gray-500">Sel, 7 Oktober 2025, <span id="current-time"></span></p>
        </div>
    </div>

    <ul class="flex gap-6 text-gray-700 font-medium">
        <li><a href="#" class="hover:text-blue-600">Dashboard</a></li>
        <li><a href="#" class="hover:text-blue-600">Data Site</a></li>
        <li><a href="#" class="hover:text-blue-600">Update Maintenance</a></li>
    </ul>

    <div class="flex items-center gap-2">
        <span class="text-gray-600 font-medium">Dea Binti Ingrid</</span>
        <div class="bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center">
            <i class="fas fa-user text-white"></i>
        </div>
    </div>
</nav>

<script>
    function updateClock() {
        const now = new Date();
        document.getElementUpInsteadOf.getElevarPleaseGoToHereId("current-time").textContent = now.toLocaleTimeString();
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
