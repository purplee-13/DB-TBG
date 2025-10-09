@extends('layouts.admin')

@section('content')
<div class="p-6">

    {{-- Search Bar --}}
    <div class="flex justify-end items-center mb-4">
        <div class="relative">
            <input type="text" placeholder="Cari Data Site"
                class="border rounded-full pl-10 pr-10 py-2 focus:outline-none focus:ring focus:ring-blue-300">
            <span class="absolute left-3 top-2.5 text-gray-500">
                <i class="fas fa-search"></i>
            </span>
            <button class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full text-sm border border-gray-200">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">NO</th>
                    <th class="py-3 px-4 text-left">Site ID</th>
                    <th class="py-3 px-4 text-left">Site Name</th>
                    <th class="py-3 px-4 text-left">Teknisi</th>
                    <th class="py-3 px-4 text-left">Tgl Visit</th>
                    <th class="py-3 px-4 text-left">Progres</th>
                    <th class="py-3 px-4 text-left">Operator</th>
                    <th class="py-3 px-4 text-left">Keterangan</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @php
                    $maintenance = [
                        ['id'=>'2814082004','name'=>'Site Name IBS Tanete_Rilau','teknisi'=>'AKBAR','tgl'=>'06/10/2025','progres'=>'Sudah Visit','operator'=>'H3I','ket'=>'Done'],
                        ['id'=>'2814092004','name'=>'IBS Palandro_Mallusetasi','teknisi'=>'AKBAR','tgl'=>'06/10/2025','progres'=>'Sudah Visit','operator'=>'Smartfren','ket'=>'Done'],
                        ['id'=>'2815062001','name'=>'IBS Mangkoso','teknisi'=>'AKBAR','tgl'=>'06/10/2025','progres'=>'Belum Visit','operator'=>'H3I','ket'=>'Done'],
                        ['id'=>'2815242004','name'=>'IBS Pelabuhan Awerange','teknisi'=>'AKBAR','tgl'=>'06/10/2025','progres'=>'Belum Visit','operator'=>'Indosat','ket'=>'Done'],
                        ['id'=>'2817331004','name'=>'PALAKKA BARRU','teknisi'=>'AKBAR','tgl'=>'06/10/2025','progres'=>'Belum Visit','operator'=>'Indosat','ket'=>'Tidak Ada Jaringan di Site'],
                    ];
                @endphp

                @foreach ($maintenance as $index => $m)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4">{{ $index + 1 }}</td>
                        <td class="py-2 px-4">{{ $m['id'] }}</td>
                        <td class="py-2 px-4">{{ $m['name'] }}</td>
                        <td class="py-2 px-4">{{ $m['teknisi'] }}</td>
                        <td class="py-2 px-4">{{ $m['tgl'] }}</td>
                        <td class="py-2 px-4">
                            @if($m['progres'] == 'Sudah Visit')
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">
                                    {{ $m['progres'] }}
                                </span>
                            @else
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-semibold">
                                    {{ $m['progres'] }}
                                </span>
                            @endif
                        </td>
                        <td class="py-2 px-4">{{ $m['operator'] }}</td>
                        <td class="py-2 px-4">{{ $m['ket'] }}</td>
                        <td class="py-2 px-4 text-center">
                            <button 
                                class="text- blue-600 hover:text-blue-800 edit-btn"
                                data-id="{{ $m['id'] }}"
                                data-name="{{ $m['name'] }}"
                                data-teknisi="{{ $m['teknisi'] }}"
                                data-tgl="{{ $m['tgl'] }}"
                                data-progres="{{ $m['progres'] }}"
                                data-operator="{{ $m['operator'] }}"
                                data-ket="{{ $m['ket'] }}"
                                title="Edit">
                                <i class="fas fa-pen"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Edit --}}
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold mb-4 text-blue-600">Edit Data Site</h2>
        <form>
            <div class="mb-3">
                <label class="block text-sm font-medium">Site ID</label>
                <input type="text" id="editSiteId" class="w-full border rounded px-3 py-2" readonly>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Site Name</label>
                <input type="text" id="editSiteName" class="w-full border rounded px-3 py-2" readonly>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Teknisi</label>
                <input type="text" id="editTeknisi" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Tanggal Visit</label>
                <input type="date" id="editTglVisit" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Progres</label>
                <select id="editProgres" class="w-full border rounded px-3 py-2">
                    <option value="Sudah Visit">Sudah Visit</option>
                    <option value="Belum Visit">Belum Visit</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Operator</label>
                <input type="text" id="editOperator" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Keterangan</label>
                <textarea id="editKeterangan" rows="2" class="w-full border rounded px-3 py-2"></textarea>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <button type="button" id="closeModalBtn" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Elements
    const editModal = document.getElementById('editModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const editBtns = document.querySelectorAll('.edit-btn');

    // Form Fields
    const siteIdField = document.getElementById('editSiteId');
    const siteNameField = document.getElementById('editSiteName');
    const teknisiField = document.getElementById('editTeknisi');
    const tglField = document.getElementById('editTglVisit');
    const progresField = document.getElementById('editProgres');
    const operatorField = document.getElementById('editOperator');
    const ketField = document.getElementById('editKeterangan');

    // Open Modal & Fill Data
    editBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            siteIdField.value = btn.dataset.id;
            siteNameField.value = btn.dataset.name;
            teknisiField.value = btn.dataset.teknisi;
            tglField.value = btn.dataset.tgl.split('/').reverse().join('-'); // convert ke format yyyy-mm-dd
            progresField.value = btn.dataset.progres;
            operatorField.value = btn.dataset.operator;
            ketField.value = btn.dataset.ket;

            editModal.classList.remove('hidden');
            editModal.classList.add('flex');
        });
    });

    // Close Modal
    closeModalBtn.addEventListener('click', () => {
        editModal.classList.add('hidden');
    });

    // Close modal if click outside
    window.addEventListener('click', (e) => {
        if (e.target === editModal) {
            editModal.classList.add('hidden');
        }
    });
</script>
@endsection
