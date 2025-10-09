@extends('layouts.admin')

@section('content')
<div class="p-6">

    {{-- =================== SEARCH & ADD =================== --}}
    <div class="flex justify-end items-center mb-4 gap-3">
        <div class="relative">
            <input type="text" placeholder="Cari"
                class="border rounded-full pl-10 pr-10 py-2 focus:outline-none focus:ring focus:ring-blue-300">
            <span class="absolute left-3 top-2.5 text-gray-500">
                <i class="fas fa-search"></i>
            </span>
            <button class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <button onclick="openAddModal()" 
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center gap-1">
            <span class="text-lg font-semibold">+</span> Tambah
        </button>
    </div>

    {{-- =================== TABLE =================== --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full text-sm">
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
            <tbody class="divide-y">
                @php
                    $sites = [
                        ['id' => '2814082004','name'=>'Site Name IBS Tanete_Rilau','area'=>'SA PAREPARE','sto'=>'BAR','product'=>'MMP (Fiberization)','tikor'=>'-4.26268, 119.63047'],
                        ['id' => '2814092004','name'=>'IBS Palandro_Mallusetasi','area'=>'SA PAREPARE','sto'=>'BAR','product'=>'MMP (Fiberization)','tikor'=>'-4.1841, 119.63792'],
                        ['id' => '2815062001','name'=>'IBS Mangkoso','area'=>'SA PAREPARE','sto'=>'BAR','product'=>'MMP (Fiberization)','tikor'=>'-4.21705, 119.61688'],
                    ];
                @endphp

                @foreach ($sites as $index => $site)
                    <tr class="hover:bg-gray-100">
                        <td class="py-2 px-4">{{ $index + 1 }}</td>
                        <td class="py-2 px-4">{{ $site['id'] }}</td>
                        <td class="py-2 px-4">{{ $site['name'] }}</td>
                        <td class="py-2 px-4">{{ $site['area'] }}</td>
                        <td class="py-2 px-4">{{ $site['sto'] }}</td>
                        <td class="py-2 px-4">{{ $site['product'] }}</td>
                        <td class="py-2 px-4">{{ $site['tikor'] }}</td>
                        <td class="py-2 px-4 text-center">
                            {{-- Tombol Edit --}}
                            <button class="text-blue-600 hover:text-blue-800 mr-3" 
                                    title="Edit"
                                    onclick="openEditModal('{{ $site['id'] }}','{{ $site['name'] }}','{{ $site['area'] }}','{{ $site['sto'] }}','{{ $site['product'] }}','{{ $site['tikor'] }}')">
                                <i class="fas fa-pen"></i>
                            </button>
                            {{-- Tombol Hapus --}}
                            <button class="text-red-600 hover:text-red-800" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- =================== MODAL TAMBAH =================== --}}
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-1/3 p-6">
            <h2 class="text-xl font-bold mb-4">Tambah Site</h2>
            <form>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Site ID</label>
                    <input type="text" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Site Name</label>
                    <input type="text" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Service Area</label>
                    <select class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                        <option>Pilih Service Area</option>
                        <option>SA PAREPARE</option>
                        <option>SA PALOPO</option>
                        <option>SA MAJENE</option>
                        <option>SA PINRANG</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">STO</label>
                    <select class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                        <option>Pilih STO</option>
                        <option>BAR</option>
                        <option>BLP</option>
                        <option>ENR</option>
                        <option>MAJ</option>
                        <option>MAK</option>
                        <option>MAM</option>
                        <option>MAS</option>
                        <option>MLL</option>
                        <option>MMS</option>
                        <option>PIN</option>
                        <option>PKA</option>
                        <option>PLP</option>
                        <option>PLW</option>
                        <option>PRE</option>
                        <option>RTP</option>
                        <option>SID</option>
                        <option>SIW</option>
                        <option>SKG</option>
                        <option>TMN</option>
                        <option>TPY</option>
                        <option>TTE</option>
                        <option>WTG</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Product</label>
                    <input type="text" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Tikor</label>
                    <input type="text" placeholder="-4.12345, 120.67890"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                </div>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closeAddModal()" 
                        class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- =================== MODAL EDIT =================== --}}
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-1/3 p-6">
            <h2 class="text-xl font-bold mb-4">Edit Site</h2>
            <form>
                <input type="hidden" id="editSiteIndex">

                <div class="mb-3">
                    <label class="block text-sm font-medium">Site ID</label>
                    <input id="editSiteID" type="text" 
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Site Name</label>
                    <input id="editSiteName" type="text" 
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Service Area</label>
                    <select id="editServiceArea" 
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                        <option>SA PAREPARE</option>
                        <option>SA PALOPO</option>
                        <option>SA MAJENE</option>
                        <option>SA PINRANG</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">STO</label>
                    <select id="editSTO"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                        <option>BAR</option>
                        <option>BLP</option>
                        <option>ENR</option>
                        <option>MAJ</option>
                        <option>MAK</option>
                        <option>MAM</option>
                        <option>MAS</option>
                        <option>MLL</option>
                        <option>MMS</option>
                        <option>PIN</option>
                        <option>PKA</option>
                        <option>PLP</option>
                        <option>PLW</option>
                        <option>PRE</option>
                        <option>RTP</option>
                        <option>SID</option>
                        <option>SIW</option>
                        <option>SKG</option>
                        <option>TMN</option>
                        <option>TPY</option>
                        <option>TTE</option>
                        <option>WTG</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Product</label>
                    <input id="editProduct" type="text"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Tikor</label>
                    <input id="editTikor" type="text"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                </div>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closeEditModal()" 
                        class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- =================== SCRIPT =================== --}}
<script>
    // Modal Tambah
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
        document.getElementById('addModal').classList.add('flex');
    }
    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
    }

    // Modal Edit
    function openEditModal(id, name, area, sto, product, tikor) {
        document.getElementById('editSiteID').value = id;
        document.getElementById('editSiteName').value = name;
        document.getElementById('editServiceArea').value = area;
        document.getElementById('editSTO').value = sto;
        document.getElementById('editProduct').value = product;
        document.getElementById('editTikor').value = tikor;

        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endsection
