@extends('layouts.admin')

@section('content')
<div class="p-6">

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg border border-green-300">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-lg border border-red-300">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
                @foreach ($sites as $index => $site)
                    @php
                        $last = $site->maintenances->first();
                        // format date for display
                        $tgl = $last && $last->tngl_visit ? $last->tngl_visit->format('d/m/Y') : '-';
                        $teknisi = $last->teknisi ?? '';
                        $progres = $last->progres ?? 'Belum Visit';
                        $operator = $last->operator ?? '';
                        $keterangan = $last->keterangan ?? '';
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4">{{ $index + 1 }}</td>
                        <td class="py-2 px-4">{{ $site->site_code }}</td>
                        <td class="py-2 px-4">{{ $site->site_name }}</td>
                        <td class="py-2 px-4">{{ $teknisi }}</td>
                        <td class="py-2 px-4">{{ $tgl }}</td>
                        <td class="py-2 px-4">
                            @if($progres == 'Visit')
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">
                                    {{ $progres }}
                                </span>
                            @else
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-semibold">
                                    {{ $progres }}
                                </span>
                            @endif
                        </td>
                        <td class="py-2 px-4">{{ $operator }}</td>
                        <td class="py-2 px-4">{{ $keterangan }}</td>
                        <td class="py-2 px-4 text-center">
                            <button 
                                class="text-blue-600 hover:text-blue-800 edit-btn"
                                data-id="{{ $site->id }}"
                                data-code="{{ $site->site_code }}"
                                data-name="{{ $site->site_name }}"
                                data-teknisi="{{ $teknisi }}"
                                data-tgl="{{ $last && $last->tngl_visit ? $last->tngl_visit->format('Y-m-d') : '' }}"
                                data-progres="{{ $progres }}"
                                data-operator="{{ $operator }}"
                                data-ket="{{ $keterangan }}"
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
        <form method="POST" action="{{ route('update-maintenance.store') }}">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-medium">Site ID</label>
                <input type="text" id="editSiteId" class="w-full border rounded px-3 py-2" readonly>
                <input type="hidden" id="editSiteHiddenId" name="site_id">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Site Name</label>
                <input type="text" id="editSiteName" class="w-full border rounded px-3 py-2" readonly>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Teknisi</label>
                <input type="text" id="editTeknisi" name="teknisi" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Tanggal Visit</label>
                <input type="date" id="editTglVisit" name="tngl_visit" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Progres</label>
                <select id="editProgres" name="progres" class="w-full border rounded px-3 py-2">
                    <option value="Visit">Visit</option>
                    <option value="Belum Visit">Belum Visit</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Operator</label>
                <input type="text" id="editOperator" name="operator" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Keterangan</label>
                <textarea id="editKeterangan" name="keterangan" rows="2" class="w-full border rounded px-3 py-2"></textarea>
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
            siteIdField.value = btn.dataset.code || btn.dataset.id;
            document.getElementById('editSiteHiddenId').value = btn.dataset.id;
            siteNameField.value = btn.dataset.name;
            teknisiField.value = btn.dataset.teknisi || '';
            // Accept either dd/mm/yyyy or yyyy-mm-dd in data attribute
            const rawDate = btn.dataset.tgl || '';
            if (rawDate.includes('/')) {
                tglField.value = rawDate.split('/').reverse().join('-'); // dd/mm/yyyy -> yyyy-mm-dd
            } else {
                tglField.value = rawDate; // already yyyy-mm-dd or empty
            }
            progresField.value = btn.dataset.progres || 'Belum Visit';
            operatorField.value = btn.dataset.operator || '';
            ketField.value = btn.dataset.ket || '';

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
