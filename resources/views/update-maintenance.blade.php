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
        <form method="GET" action="{{ route('maintenance.index') }}" class="relative" id="searchForm">
            <input type="text" name="search" placeholder="Cari berdasarkan Site ID atau Site Name"
                value="{{ request('search') }}"
                class="border rounded-full pl-10 pr-10 py-2 focus:outline-none focus:ring focus:ring-blue-300 w-80"
                id="searchInput">
            <span class="absolute left-3 top-2.5 text-gray-500">
                <i class="fas fa-search"></i>
            </span>
            @if(request('search'))
                <a href="{{ route('maintenance.index') }}" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
    </div>

    {{-- Search Results Info --}}
    @if(request('search'))
        <div class="mb-4 text-sm text-gray-600">
            <span class="font-medium">{{ $sites->count() }}</span> hasil ditemukan untuk "<span class="font-medium">{{ request('search') }}</span>"
            <a href="{{ route('maintenance.index') }}" class="text-blue-600 hover:text-blue-800 ml-2">Tampilkan semua</a>
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <div class="max-h-[600px] overflow-y-auto">
            <table class="min-w-full text-sm border border-gray-200">
            <thead class="bg-blue-600 text-white sticky top-0 z-10">
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
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4">{{ $index + 1 }}</td>
                        <td class="py-2 px-4">{{ $site->site_code }}</td>
                        <td class="py-2 px-4">{{ $site->site_name }}</td>
                        <td class="py-2 px-4">{{ $site->teknisi ?? '-' }}</td>
                        <td class="py-2 px-4">{{ $site->tgl_visit ? \Carbon\Carbon::parse($site->tgl_visit)->format('d/m/Y') : '-' }}</td>
                        <td class="py-2 px-4">
                            @if($site->progres == 'Sudah Visit')
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">
                                    {{ $site->progres }}
                                </span>
                            @else
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-semibold">
                                    {{ $site->progres }}
                                </span>
                            @endif
                        </td>
                        <td class="py-2 px-4">{{ $site->operator ?? '-' }}</td>
                        <td class="py-2 px-4">{{ $site->keterangan ?? '-' }}</td>
                        <td class="py-2 px-4 text-center">
                            <button 
                                class="text- blue-600 hover:text-blue-800 edit-btn"
                                data-id="{{ $site->id }}"
                                data-site-code="{{ $site->site_code }}"
                                data-site-name="{{ $site->site_name }}"
                                data-teknisi="{{ $site->teknisi }}"
                                data-tgl="{{ $site->tgl_visit ? \Carbon\Carbon::parse($site->tgl_visit)->format('d/m/Y') : '' }}"
                                data-progres="{{ $site->progres }}"
                                data-operator="{{ $site->operator }}"
                                data-ket="{{ $site->keterangan }}"
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
</div>

{{-- Modal Edit --}}
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold mb-4 text-blue-600">Edit Data Site</h2>
        <form method="POST" action="{{ route('maintenance.update', 0) }}" id="editForm">
            @csrf
            @method('PUT')
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
                <input type="date" id="editTglVisit" name="tgl_visit" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Progres</label>
                <select id="editProgres" name="progres" class="w-full border rounded px-3 py-2">
                    <option value="Sudah Visit">Sudah Visit</option>
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
    const editForm = document.getElementById('editForm');
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
            // Update form action with correct site ID
            editForm.action = editForm.action.replace(/\/\d+$/, '/' + btn.dataset.id);
            
            siteIdField.value = btn.dataset.siteCode;
            siteNameField.value = btn.dataset.siteName;
            teknisiField.value = btn.dataset.teknisi || '';
            // Convert date format from dd/mm/yyyy to yyyy-mm-dd
            if (btn.dataset.tgl) {
                tglField.value = btn.dataset.tgl.split('/').reverse().join('-');
            } else {
                tglField.value = '';
            }
            progresField.value = btn.dataset.progres;
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

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    let searchTimeout;

    searchInput.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchForm.submit();
        }, 500); // Submit after 500ms of no typing
    });

    // Submit on Enter key
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            clearTimeout(searchTimeout);
            searchForm.submit();
        }
    });
</script>
@endsection
