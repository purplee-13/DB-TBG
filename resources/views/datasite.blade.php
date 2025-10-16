{{-- Notifikasi Sukses dan Gagal --}}
@if (session('success'))
    <div id="notif-success" class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg border border-green-300">
        {{ session('success') }}
    </div>
@endif
@if ($errors->any())
    <div id="notif-error" class="mb-4 p-3 bg-red-100 text-red-800 rounded-lg border border-red-300">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@extends('layouts.admin')

@section('content')
<div class="p-6">

    {{-- =================== SEARCH & ADD =================== --}}
    <div class="flex justify-end items-center mb-4 gap-3">
        <div class="relative">
            <input type="text" id="searchInput" placeholder="Cari Site ID / Name / Area"
                class="border rounded-full pl-10 pr-10 py-2 focus:outline-none focus:ring focus:ring-blue-300" oninput="searchSiteId()">
            <span class="absolute left-3 top-2.5 text-gray-500">
                <i class="fas fa-search"></i>
            </span>
            <button onclick="clearSearch()" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Tombol Tambah hanya muncul untuk admin dan master --}}
        @if(session('role') === 'admin' || session('role') === 'master')
            <button onclick="openAddModal()" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center gap-1">
                <span class="text-lg font-semibold">+</span> Tambah
            </button>
            
            <button id="bulkDeleteBtn" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 items-center gap-1 p-1.5">Hapus Terpilih</button>
           
        @endif
    </div>

    {{-- =================== TABLE =================== --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <div style="max-height:595px; overflow-y:auto;">
            <table class="min-w-full text-sm" id="sitesTable">
                <thead class="bg-blue-600 text-white" style="position:sticky;top:0;z-index:2;">
                    <tr>
                        <th class="py-3 px-4 text-left">NO</th>
                        <th class="py-3 px-4 text-left">Site ID</th>
                        <th class="py-3 px-4 text-left">Site Name</th>
                        <th class="py-3 px-4 text-left">Service Area</th>
                        <th class="py-3 px-4 text-left">STO</th>
                        <th class="py-3 px-4 text-left">Product</th>
                        <th class="py-3 px-4 text-left">Tikor</th>
                        {{-- Kolom Aksi hanya muncul untuk admin dan master --}}
                        @if(session('role') == 'admin' || session('role') == 'master')
                            <th class="py-2 px-4 text-center">Aksi</th>
                            <th class="py-3 px-4 text-center" id="selectAll">Pilih</th>
                        @endif
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y">
                    @foreach ($sites as $index => $site)
                    <tr>
                        <td class="py-2 px-4">{{ $index + 1 }}</td>
                        <td class="py-2 px-4">{{ $site->site_code }}</td>
                        <td class="py-2 px-4">{{ $site->site_name }}</td>
                        <td class="py-2 px-4">{{ $site->service_area }}</td>
                        <td class="py-2 px-4">{{ $site->sto }}</td>
                        <td class="py-2 px-4">{{ $site->product }}</td>
                        <td class="py-2 px-4">{{ $site->tikor }}</td>

                        {{-- Tombol aksi hanya muncul untuk admin dan master --}}
                        @if(session('role') == 'admin' || session('role') == 'master')
                        <td class="py-2 px-4 text-center flex justify-center gap-3">
                            <button class="text-blue-600 hover:text-blue-800 edit-btn" title="Edit" onclick="openEditModal({{ $site->id }})">
                                <i class="fas fa-pen"></i>
                            </button>
                            <form method="POST" action="{{ route('datasite.delete', $site->id) }}" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800 delete-btn" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                        <td class="py-2 px-4 text-center"><input type="checkbox" class="row-checkbox" value="{{ $site->id }}"></td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    {{-- =================== MODAL TAMBAH =================== --}}
    @if(session('role') == 'admin' || session('role') == 'master')
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-1/3 p-6 relative">
            <h2 id="modalTitle" class="text-xl font-bold mb-4">Tambah Site</h2>
            <form method="POST" action="{{ route('datasite.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium">Site Id</label>
                    <input type="number" name="site_code" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Site Name</label>
                    <input type="text" name="site_name" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Service Area</label>
                    <select name="service_area" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="">Pilih Service Area</option>
                        @foreach(array_keys(config('sto')) as $area)
                            <option value="{{ $area }}">{{ $area }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">STO</label>
                    <select name="sto" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="">Pilih STO</option>
                        @foreach(array_unique(array_merge(...array_values(config('sto')))) as $sto)
                            <option value="{{ $sto }}">{{ $sto }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Product</label>
                    <select name="product" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="">Pilih Product</option>
                        <option value="INTERSITE FO">INTERSITE FO</option>
                        <option value="MMP">MMP</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Tikor</label>
                    <input type="text" name="tikor" placeholder="-4.12345, 120.67890"
                        class="w-full border rounded-lg px-3 py-2">
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
    @endif

    {{-- =================== MODAL EDIT =================== --}}
    @if(session('role') == 'admin' || session('role') == 'master')
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-1/3 p-6 relative">
            <h2 class="text-xl font-bold mb-4">Edit Site</h2>
            <form id="editSiteForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <div class="mb-3">
                    <label class="block text-sm font-medium">Site Id</label>
                    <input type="number" id="editSiteID" name="site_code" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Site Name</label>
                    <input type="text" id="editSiteName" name="site_name" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Service Area</label>
                    <select id="editServiceArea" name="service_area" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="">Pilih Service Area</option>
                        @foreach(array_keys(config('sto')) as $area)
                            <option value="{{ $area }}">{{ $area }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">STO</label>
                    <select id="editSTO" name="sto" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="">Pilih STO</option>
                        @foreach(array_unique(array_merge(...array_values(config('sto')))) as $sto)
                            <option value="{{ $sto }}">{{ $sto }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Product</label>
                    <select id="editProduct" name="product" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="">Pilih Product</option>
                        <option value="INTERSITE FO">INTERSITE FO</option>
                        <option value="MMP">MMP</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Tikor</label>
                    <input type="text" id="editTikor" name="tikor" class="w-full border rounded-lg px-3 py-2">
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
    @endif

</div>

<script>
    let sites = @json($sites);
    let role = @json(session('role'));

    function openEditModal(siteId) {
        const site = sites.find(s => s.id === siteId);
        if (!site) return;
        document.getElementById('editSiteID').value = site.site_code;
        document.getElementById('editSiteName').value = site.site_name;
        document.getElementById('editServiceArea').value = site.service_area;
        document.getElementById('editSTO').value = site.sto;
        document.getElementById('editProduct').value = site.product;
        document.getElementById('editTikor').value = site.tikor;
        document.getElementById('editSiteForm').action = `/datasite/${siteId}/update`;
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }

    function openAddModal() {
        const modal = document.getElementById('addModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeAddModal() {
        const modal = document.getElementById('addModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function searchSiteId() {
        const input = document.getElementById('searchInput').value.trim().toLowerCase();
        if (input === "") {
            renderTable(sites);
            return;
        }
        const filtered = sites.filter(site => {
            return String(site.site_code).toLowerCase().includes(input)
                || String(site.site_name).toLowerCase().includes(input)
                || String(site.service_area).toLowerCase().includes(input);
        });
        renderTable(filtered);
    }

    // ✅ renderTable fix - kolom aksi tidak hilang
    function renderTable(data) {
        const tableBody = document.getElementById('tableBody');
        tableBody.innerHTML = "";
        data.forEach((site, index) => {
            let row = `
                <tr>
                    <td class='py-2 px-4'>${index + 1}</td>
                    <td class='py-2 px-4'>${site.site_code}</td>
                    <td class='py-2 px-4'>${site.site_name}</td>
                    <td class='py-2 px-4'>${site.service_area}</td>
                    <td class='py-2 px-4'>${site.sto}</td>
                    <td class='py-2 px-4'>${site.product}</td>
                    <td class='py-2 px-4'>${site.tikor}</td>
            `;

            if (role === 'admin' || role === 'master') {
                row += `
                    <td class='py-2 px-4 text-center flex justify-center gap-3'>
                        <button class='text-blue-600 hover:text-blue-800' title='Edit' onclick='openEditModal(${site.id})'>
                            <i class='fas fa-pen'></i>
                        </button>
                        <form method='POST' action='/datasite/${site.id}/delete' style='display:inline;' onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            <input type='hidden' name='_token' value='{{ csrf_token() }}'>
                            <button type='submit' class='text-red-600 hover:text-red-800' title='Hapus'>
                                <i class='fas fa-trash'></i>
                            </button>
                        </form>
                    </td>
                    <td class='py-2 px-4'><input type='checkbox' class='row-checkbox' value='${site.id}'></td>
                `;
            }

            row += `</tr>`;
            tableBody.innerHTML += row;
        });

        // Re-bind selectAll behavior for dynamic rows
        const selectAll = document.getElementById('selectAll');
        if (selectAll) {
            selectAll.checked = false;
            selectAll.addEventListener('change', function() {
                const checked = this.checked;
                document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = checked);
            });
        }

        // Bulk delete button
        const bulkBtn = document.getElementById('bulkDeleteBtn');
        if (bulkBtn) {
            bulkBtn.addEventListener('click', async function() {
                const selected = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
                if (selected.length === 0) {
                    alert('Pilih minimal satu data untuk dihapus.');
                    return;
                }
                if (!confirm('Yakin ingin menghapus ' + selected.length + ' data terpilih?')) return;

                try {
                    const res = await fetch('{{ route('datasite.deleteMultiple') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ ids: selected })
                    });
                    const json = await res.json();
                    if (json.success) {
                        location.reload();
                    } else {
                        alert(json.message || 'Terjadi kesalahan saat menghapus.');
                    }
                } catch (err) {
                    console.error(err);
                    alert('Gagal menghapus data. Cek console untuk detail.');
                }
            });
        }
    }
    renderTable(sites);

    function clearSearch() {
        document.getElementById('searchInput').value = "";
        renderTable(sites);
    }

    // Auto-hide notifikasi
    window.onload = function() {
        setTimeout(function() {
            document.getElementById('notif-success')?.remove();
            document.getElementById('notif-error')?.remove();
        }, 5000);
    };
</script>
@endsection
