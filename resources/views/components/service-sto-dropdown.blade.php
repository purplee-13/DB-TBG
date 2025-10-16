<form method="GET" action="{{ route('dashboard') }}" class="flex gap-4">
    <select name="service_area" id="serviceArea" class="border rounded-lg px-3 py-2" onchange="this.form.submit()">
        <option value="">Pilih Service Area</option>
        @foreach ($data as $area => $stos)
            <option value="{{ $area }}" {{ $selectedServiceArea == $area ? 'selected' : '' }}>{{ $area }}</option>
        @endforeach
    </select>

    <select name="sto" id="sto" class="border rounded-lg px-3 py-2" onchange="this.form.submit()">
        <option value="">Pilih STO</option>
        @if($selectedServiceArea && isset($data[$selectedServiceArea]))
            @foreach($data[$selectedServiceArea] as $sto)
                <option value="{{ $sto }}" {{ $selectedSto == $sto ? 'selected' : '' }}>{{ $sto }}</option>
            @endforeach
        @endif
    </select>
    @if($selectedServiceArea || $selectedSto)
        <button type="button" onclick="clearFilters()" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
            Clear Filters
        </button>
    @endif
</form>

<script>
    const stoOptions = @json($data);
    const serviceSelect = document.getElementById('serviceArea');
    const stoSelect = document.getElementById('sto');

    serviceSelect.addEventListener('change', function () {
        const selectedArea = this.value;
        stoSelect.innerHTML = '<option value="">Pilih STO</option>';

        if (stoOptions[selectedArea]) {
            stoOptions[selectedArea].forEach(sto => {
                const opt = document.createElement('option');
                opt.value = sto;
                opt.textContent = sto;
                stoSelect.appendChild(opt);
            });
        }
    });

    function clearFilters() {
        window.location.href = '{{ route("dashboard") }}';
    }
</script>