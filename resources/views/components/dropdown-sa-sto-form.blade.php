@props([
    'serviceAreas' => [],    // array of service area strings or collection
    'stosByArea' => [],      // associative array: ['SA X' => ['STO1','STO2'], ...]
    'selectedServiceArea' => null,
    'selectedSto' => null,
    'idPrefix' => '',
])

<div>
    <label for="{{ $idPrefix }}ServiceArea" class="block text-sm font-medium">Service Area</label>
    <select name="service_area" id="{{ $idPrefix }}ServiceArea" class="border rounded-lg px-3 py-2 w-full" required>
        <option value="">Pilih Service Area</option>
        @foreach ($serviceAreas as $area)
            <option value="{{ $area }}" {{ $selectedServiceArea == $area ? 'selected' : '' }}>{{ $area }}</option>
        @endforeach
    </select>
</div>

<div class="mt-3">
    <label for="{{ $idPrefix }}STO" class="block text-sm font-medium">STO</label>
    <select name="sto" id="{{ $idPrefix }}STO" class="border rounded-lg px-3 py-2 w-full" required>
        <option value="">Pilih STO</option>
        @if ($selectedServiceArea && isset($stosByArea[$selectedServiceArea]))
            @foreach ($stosByArea[$selectedServiceArea] as $sto)
                <option value="{{ $sto }}" {{ $selectedSto == $sto ? 'selected' : '' }}>{{ $sto }}</option>
            @endforeach
        @endif
    </select>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stoMap = @json($stosByArea);
    const serviceSelect = document.getElementById('{{ $idPrefix }}ServiceArea');
    const stoSelect = document.getElementById('{{ $idPrefix }}STO');

    if (!serviceSelect) return;

    serviceSelect.addEventListener('change', function () {
        const area = this.value;
        stoSelect.innerHTML = '<option value="">Pilih STO</option>';
        if (stoMap[area]) {
            stoMap[area].forEach(s => {
                const o = document.createElement('option');
                o.value = s;
                o.textContent = s;
                stoSelect.appendChild(o);
            });
        }
    });
});
</script>