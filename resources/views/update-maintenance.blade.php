@extends('layouts.admin')

@section('content')
<div class="p-6">

    {{-- Search Bar --}}
    <div class="flex justify-end items-center mb-4">
        <div class="relative">
            <input type="text" placeholder="Cari Data Site" id="searchInput"
                class="border rounded-full pl-10 pr-10 py-2 focus:outline-none focus:ring focus:ring-blue-300">
            <span class="absolute left-3 top-2.5 text-gray-500">
                <i class="fas fa-search"></i>
            </span>
            <button onclick="clearSearch()" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full text-sm border border-gray-200" id="maintenanceTable">
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
            <tbody class="divide-y" id="tableBody">
                @php
                    $maintenance = [
                        ['id'=>'2814082004','name'=>'Site Name IBS Tanete_Rilau','teknisi'=>'AKBAR','tgl'=>'06/10/2025','progres'=>'Sudah Visit','operator'=>'H3I','ket'=>'Done'],
                        ['id'=>'2814092004','name'=>'IBS Palandro_Mallusetasi','teknisi'=>'AKBAR','tgl'=>'06/10/2025','progres'=>'Sudah Visit','operator'=>'Smartfren','ket'=>'Done'],
                        ['id'=>'2815062001','name'=>'IBS Mangkoso','teknisi'=>'AKBAR','tgl'=>'06/10/2025','progres'=>'Belum Visit','operator'=>'H3I','ket'=>'Done'],
                    ];
                @endphp
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Edit --}}
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-1/3 p-6">
        <h2 class="text-xl font-bold mb-4">Edit Data Maintenance</h2>
        <form id="editForm">
            <input type="hidden" id="editIndex">
            <div class="mb-3">
                <label class="block text-sm font-medium">Site ID</label>
                <input type="text" id="editSiteId" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Site Name</label>
                <input type="text" id="editSiteName" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Teknisi</label>
                <input type="text" id="editTeknisi" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Tgl Visit</label>
                <input type="date" id="editTgl" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Progres</label>
                <select id="editProgres" class="w-full border rounded-lg px-3 py-2">
                    <option>Sudah Visit</option>
                    <option>Belum Visit</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Operator</label>
                <input type="text" id="editOperator" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Keterangan</label>
                <input type="text" id="editKet" class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Script --}}
<script>
    let maintenance = @json($maintenance);

    function renderTable() {
        const tbody = document.getElementById("tableBody");
        tbody.innerHTML = "";
        maintenance.forEach((m, index) => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td class="py-2 px-4">${index + 1}</td>
                <td class="py-2 px-4">${m.id}</td>
                <td class="py-2 px-4">${m.name}</td>
                <td class="py-2 px-4">${m.teknisi}</td>
                <td class="py-2 px-4">${m.tgl}</td>
                <td class="py-2 px-4">
                    <span class="${m.progres == 'Sudah Visit' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'} px-2 py-1 rounded-full text-xs font-semibold">
                        ${m.progres}
                    </span>
                </td>
                <td class="py-2 px-4">${m.operator}</td>
                <td class="py-2 px-4">${m.ket}</td>
                <td class="py-2 px-4 text-center flex justify-center gap-2">
                    <button class="text-blue-600 hover:text-blue-800 edit-btn" onclick="openEditModal(${index})"><i class="fas fa-pen"></i></button>
                    <button class="text-red-600 hover:text-red-800 delete-btn" onclick="deleteRow(${index})"><i class="fas fa-trash"></i></button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function deleteRow(index) {
        if (confirm("Yakin ingin menghapus data ini?")) {
            maintenance.splice(index, 1);
            renderTable();
        }
    }

    function openEditModal(index) {
        document.getElementById("editModal").classList.remove("hidden");
        document.getElementById("editModal").classList.add("flex");
        document.getElementById("editIndex").value = index;

        const m = maintenance[index];
        document.getElementById("editSiteId").value = m.id;
        document.getElementById("editSiteName").value = m.name;
        document.getElementById("editTeknisi").value = m.teknisi;
        document.getElementById("editTgl").value = m.tgl;
        document.getElementById("editProgres").value = m.progres;
        document.getElementById("editOperator").value = m.operator;
        document.getElementById("editKet").value = m.ket;
    }

    function closeModal() {
        document.getElementById("editModal").classList.add("hidden");
    }

    document.getElementById("editForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const index = document.getElementById("editIndex").value;
        maintenance[index] = {
            id: document.getElementById("editSiteId").value,
            name: document.getElementById("editSiteName").value,
            teknisi: document.getElementById("editTeknisi").value,
            tgl: document.getElementById("editTgl").value,
            progres: document.getElementById("editProgres").value,
            operator: document.getElementById("editOperator").value,
            ket: document.getElementById("editKet").value
        };
        renderTable();
        closeModal();
    });

    document.addEventListener("DOMContentLoaded", renderTable);

    function clearSearch() {
        document.getElementById("searchInput").value = "";
    }
</script>
@endsection
