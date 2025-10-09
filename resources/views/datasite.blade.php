@extends('layouts.admin')

@section('content')
<div class="p-6">

    {{-- Search & Add Button --}}
    <div class="flex justify-end items-center mb-4 gap-3">
        <div class="relative">
            <input type="text" id="searchInput" placeholder="Cari"
                class="border rounded-full pl-10 pr-10 py-2 focus:outline-none focus:ring focus:ring-blue-300">
            <span class="absolute left-3 top-2.5 text-gray-500">
                <i class="fas fa-search"></i>
            </span>
            <button onclick="clearSearch()" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <button onclick="openModal()" 
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center gap-1">
            <span class="text-lg font-semibold">+</span> Tambah
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full text-sm" id="sitesTable">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">NO</th>
                    <th class="py-3 px-4 text-left">Site ID</th>
                    <th class="py-3 px-4 text-left">Site Name</th>
                    <th class="py-3 px-4 text-left">Service Area</th>
                    <th class="py-3 px-4 text-left">STO</th>
                    <th class="py-3 px-4 text-left">Product</th>
                    <th class="py-3 px-4 text-left">Tikor</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody" class="divide-y">
                @php
                    $sites = [
                        ['id' => '2814082004','name'=>'Site Name IBS Tanete_Rilau','area'=>'SA PAREPARE','sto'=>'BAR','product'=>'MMP (Fiberization)','tikor'=>'-4.26268, 119.63047'],
                        ['id' => '2814092004','name'=>'IBS Palandro_Mallusetasi','area'=>'SA PAREPARE','sto'=>'BAR','product'=>'MMP (Fiberization)','tikor'=>'-4.1841, 119.63792'],
                        ['id' => '2815062001','name'=>'IBS Mangkoso','area'=>'SA PAREPARE','sto'=>'BAR','product'=>'MMP (Fiberization)','tikor'=>'-4.21705, 119.61688'],
                    ];
                @endphp

                @foreach ($sites as $index => $site)
                    <tr>
                        <td class="py-2 px-4">{{ $index + 1 }}</td>
                        <td class="py-2 px-4">{{ $site['id'] }}</td>
                        <td class="py-2 px-4">{{ $site['name'] }}</td>
                        <td class="py-2 px-4">{{ $site['area'] }}</td>
                        <td class="py-2 px-4">{{ $site['sto'] }}</td>
                        <td class="py-2 px-4">{{ $site['product'] }}</td>
                        <td class="py-2 px-4">{{ $site['tikor'] }}</td>
                        <td class="py-2 px-4 text-center flex justify-center gap-3">
                            <button class="text-blue-600 hover:text-blue-800 edit-btn" title="Edit"><i class="fas fa-pen"></i></button>
                            <button class="text-red-600 hover:text-red-800 delete-btn" title="Hapus"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Modal Tambah/Edit Site --}}
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-1/3 p-6">
            <h2 id="modalTitle" class="text-xl font-bold mb-4">Tambah Site</h2>
            <form id="siteForm">
                <input type="hidden" id="editIndex">
                <div class="mb-3">
                    <label class="block text-sm font-medium">Site ID</label>
                    <input type="text" id="siteId" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Site Name</label>
                    <input type="text" id="siteName" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Service Area</label>
                    <select id="siteArea" class="w-full border rounded-lg px-3 py-2">
                        <option>Pilih Service Area</option>
                        @foreach(array_keys(config('sto')) as $area)
                            <option value="{{ $area }}">{{ $area }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">STO</label>
                    <select id="siteSTO" class="w-full border rounded-lg px-3 py-2">
                        <option value="">Pilih STO</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Product</label>
                    <input type="text" id="siteProduct" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Tikor</label>
                    <input type="text" id="siteTikor" placeholder="-4.12345, 120.67890"
                        class="w-full border rounded-lg px-3 py-2">
                </div>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closeModal()" 
                        class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    let sites = @json($sites);

    function openModal(editIndex = null) {
        document.getElementById('addModal').classList.remove('hidden');
        document.getElementById('addModal').classList.add('flex');

        if (editIndex !== null) {
            document.getElementById('modalTitle').innerText = "Edit Site";
            document.getElementById('editIndex').value = editIndex;
            const site = sites[editIndex];
            document.getElementById('siteId').value = site.id;
            document.getElementById('siteName').value = site.name;
            document.getElementById('siteArea').value = site.area;
            const areaSelect = document.getElementById('siteArea');
            areaSelect.dispatchEvent(new Event('change'));
            setTimeout(() => {
                document.getElementById('siteSTO').value = site.sto;
            }, 100);
            document.getElementById('siteProduct').value = site.product;
            document.getElementById('siteTikor').value = site.tikor;
        } else {
            document.getElementById('modalTitle').innerText = "Tambah Site";
            document.getElementById('editIndex').value = "";
            document.getElementById('siteForm').reset();
        }
    }

    function closeModal() {
        document.getElementById('addModal').classList.add('hidden');
    }

    document.getElementById('siteForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const id = document.getElementById('siteId').value;
        const name = document.getElementById('siteName').value;
        const area = document.getElementById('siteArea').value;
        const sto = document.getElementById('siteSTO').value;
        const product = document.getElementById('siteProduct').value;
        const tikor = document.getElementById('siteTikor').value;
        const editIndex = document.getElementById('editIndex').value;

        if (editIndex) {
            // Edit
            sites[editIndex] = {id, name, area, sto, product, tikor};
        } else {
            // Tambah
            sites.push({id, name, area, sto, product, tikor});
        }

        renderTable();
        closeModal();
    });

    function renderTable() {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = "";
        sites.forEach((site, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="py-2 px-4">${index + 1}</td>
                <td class="py-2 px-4">${site.id}</td>
                <td class="py-2 px-4">${site.name}</td>
                <td class="py-2 px-4">${site.area}</td>
                <td class="py-2 px-4">${site.sto}</td>
                <td class="py-2 px-4">${site.product}</td>
                <td class="py-2 px-4">${site.tikor}</td>
                <td class="py-2 px-4 text-center flex justify-center gap-3">
                    <button class="text-blue-600 hover:text-blue-800 edit-btn" onclick="openModal(${index})"><i class="fas fa-pen"></i></button>
                    <button class="text-red-600 hover:text-red-800 delete-btn" onclick="deleteRow(${index})"><i class="fas fa-trash"></i></button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function deleteRow(index) {
        if (confirm("Yakin ingin menghapus data ini?")) {
            sites.splice(index, 1);
            renderTable();
        }
    }

    function clearSearch() {
        document.getElementById('searchInput').value = "";
    }

    const stoData = @json(config('sto'));

    document.getElementById('siteArea').addEventListener('change', function() {
        const area = this.value;
        const stoSelect = document.getElementById('siteSTO');
        stoSelect.innerHTML = '<option value="">-- Pilih STO --</option>';

        if (stoData[area]) {
            stoData[area].forEach(sto => {
                const opt = document.createElement('option');
                opt.value = sto;
                opt.textContent = sto;
                stoSelect.appendChild(opt);
            });
        }
    });

    document.getElementById('siteSTO').addEventListener('change', function() {
        const area = this.value;
        const stoSelect = document.getElementById('siteSTO');
        stoSelect.innerHTML = '<option value="">Pilih STO</option>';

        const stoData = @json(config('sto'));
        
        if (stoData[area]) {
            Object.entries(stoData[area]).forEach(([code, name]) => {
                const option = document.createElement('option');
                option.value = code;
                option.textContent = `${code} - ${name}`;
                stoSelect.appendChild(option);
            });
        }
    });

    document.addEventListener("DOMContentLoaded", renderTable);
</script>
@endsection
