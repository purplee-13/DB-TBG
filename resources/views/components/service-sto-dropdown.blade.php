@props(['selectedServiceArea' => null, 'selectedSto' => null])

<div class="flex items-center gap-2">
    <form method="GET" action="{{ route('dashboard') }}">
        <select name="service_area" onchange="this.form.submit()" class="px-3 py-2 border rounded">
            <option value="">-- All Service Area --</option>
            <option value="SA1" {{ $selectedServiceArea == 'SA1' ? 'selected' : '' }}>SA1</option>
            <option value="SA2" {{ $selectedServiceArea == 'SA2' ? 'selected' : '' }}>SA2</option>
        </select>
    </form>

    <form method="GET" action="{{ route('dashboard') }}">
        <select name="sto" onchange="this.form.submit()" class="px-3 py-2 border rounded">
            <option value="">-- All STO --</option>
            <option value="STO1" {{ $selectedSto == 'STO1' ? 'selected' : '' }}>STO1</option>
            <option value="STO2" {{ $selectedSto == 'STO2' ? 'selected' : '' }}>STO2</option>
        </select>
    </form>
</div>