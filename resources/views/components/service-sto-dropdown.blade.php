<div class="flex gap-4">
    <select id="serviceArea" class="border rounded-lg px-3 py-2">
        <option value="">Pilih Service Area</option>
        @foreach ($data as $area => $stos)
            <option value="{{ $area }}">{{ $area }}</option>
        @endforeach
    </select>

    <select id="sto" class="border rounded-lg px-3 py-2">
        <option value="">Pilih STO</option>
    </select>
</div>

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
</script>
