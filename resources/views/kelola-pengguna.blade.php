@extends('layouts.admin')

@section('content')
<div class="p-6">

    {{-- 📢 Notifikasi Sukses --}}
    @if (session('success'))
        <div id="notif-success"
            class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg border border-green-300">
            {{ session('success') }}
        </div>
        <script>
            // Hilangkan notifikasi otomatis setelah 3 detik
            setTimeout(() => {
                document.getElementById('notif-success').style.display = 'none';
            }, 3000);
        </script>
    @endif

    {{-- ⚠️ Notifikasi Error --}}
    @if ($errors->any())
        <div id="notif-error"
            class="mb-4 p-3 bg-red-100 text-red-800 rounded-lg border border-red-300">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tombol Tambah --}}
    <div class="flex justify-end mb-4">
        <button onclick="openModal()"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center gap-1">
            <span class="text-lg font-semibold">+</span> Tambah
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full text-sm border border-gray-200">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">NO</th>
                    <th class="py-3 px-4 text-left">Nama Pengguna</th>
                    <th class="py-3 px-4 text-left">Username</th>
                    <th class="py-3 px-4 text-left">Role</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @php $no = 1; @endphp
                @foreach ($users as $u)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4">{{ $no++ }}</td>
                        <td class="py-2 px-4">{{ $u->name }}</td>
                        <td class="py-2 px-4">{{ $u->username }}</td>
                        <td class="py-2 px-4">{{ ucfirst($u->role) }}</td>
                        <td class="py-2 px-4 text-center">
                            <button onclick="openEditModal({{ $u->id }}, '{{ $u->name }}', '{{ $u->username }}', '{{ $u->role }}')"
                                class="text-blue-600 hover:text-blue-800 mr-3" title="Edit">
                                <i class="fas fa-pen"></i>
                            </button>
                            <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin ingin hapus?')"
                                    class="text-red-600 hover:text-red-800" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Modal Tambah Pengguna --}}
    <div id="addModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-1/3 p-6">
            <h2 class="text-xl font-bold mb-4">Tambah Pengguna</h2>
            <form action="{{ route('users.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium">Nama Pengguna</label>
                    <input type="text" 
                           name="name" 
                           id="addName"
                           value=""
                           placeholder="Masukkan nama lengkap"
                           autocomplete="off"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300"
                           required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Username</label>
                    <input type="text" 
                           name="username" 
                           id="addUsername"
                           value=""
                           placeholder="Masukkan username"
                           autocomplete="off"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300"
                           required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Password</label>
                    <input type="password" 
                           name="password" 
                           id="addPassword"
                           value=""
                           placeholder="Min. 8 karakter (huruf besar, kecil, angka, spesial)"
                           autocomplete="new-password"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300"
                           required>
                    <small class="text-gray-500 text-xs">Contoh: Password@123</small>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Role</label>
                    <select name="role"
                            id="addRole"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300"
                            required>
                        <option value="">Pilih Role</option>
                        <option value="admin">Admin</option>
                        <option value="pegawai">Pegawai</option>
                    </select>
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

    {{-- Modal Edit Pengguna --}}
    <div id="editModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-1/3 p-6">
            <h2 class="text-xl font-bold mb-4">Edit Pengguna</h2>
            <form id="editForm" method="POST" autocomplete="off">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block text-sm font-medium">Nama Pengguna</label>
                    <input type="text" 
                           id="editName" 
                           name="name"
                           autocomplete="off"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300"
                           required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Username</label>
                    <input type="text" 
                           id="editUsername" 
                           name="username"
                           autocomplete="off"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300"
                           required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Password (biarkan kosong jika tidak diubah)</label>
                    <input type="password" 
                           id="editPassword"
                           name="password"
                           value=""
                           placeholder="Kosongkan jika tidak ingin mengubah password"
                           autocomplete="new-password"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                    <small class="text-gray-500 text-xs">Min. 8 karakter (huruf besar, kecil, angka, spesial)</small>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Role</label>
                    <select id="editRole" 
                            name="role"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300"
                            required>
                        <option value="admin">Admin</option>
                        <option value="pegawai">Pegawai</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- Script untuk Modal --}}
<script>
    function openModal() {
        const modal = document.getElementById('addModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // 🔥 RESET SEMUA INPUT FIELD KE KOSONG
        document.getElementById('addName').value = '';
        document.getElementById('addUsername').value = '';
        document.getElementById('addPassword').value = '';
        document.getElementById('addRole').value = '';
        
        // Focus ke input pertama
        setTimeout(() => {
            document.getElementById('addName').focus();
        }, 100);
    }

    function closeModal() {
        const modal = document.getElementById('addModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        
        // Reset form saat ditutup
        document.getElementById('addName').value = '';
        document.getElementById('addUsername').value = '';
        document.getElementById('addPassword').value = '';
        document.getElementById('addRole').value = '';
    }

    function openEditModal(id, name, username, role) {
        const modal = document.getElementById('editModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.getElementById('editName').value = name;
        document.getElementById('editUsername').value = username;
        
        // 🔥 PASTIKAN PASSWORD SELALU KOSONG SAAT EDIT
        document.getElementById('editPassword').value = '';

        // Normalize role to lowercase
        if (role && typeof role === 'string') {
            try { role = role.toLowerCase(); } catch (e) {}
        }
        document.getElementById('editRole').value = role || '';

        const form = document.getElementById('editForm');
        const usersBase = "{{ url('users') }}";
        form.action = usersBase + '/' + id;
        
        // Focus ke input pertama
        setTimeout(() => {
            document.getElementById('editName').focus();
        }, 100);
    }

    function closeEditModal() {
        const modal = document.getElementById('editModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        
        // Reset password field
        document.getElementById('editPassword').value = '';
    }

    // 🔥 PREVENT AUTOFILL SAAT HALAMAN LOAD
    window.addEventListener('load', function() {
        // Disable autofill untuk semua input password dan username
        const inputs = document.querySelectorAll('input[type="password"], input[name="username"], input[name="name"]');
        inputs.forEach(input => {
            input.setAttribute('autocomplete', 'off');
            input.value = '';
        });
    });

    // 🔥 CLEAR FIELDS SAAT MODAL PERTAMA KALI MUNCUL
    document.addEventListener('DOMContentLoaded', function() {
        // Clear add modal fields
        const addModal = document.getElementById('addModal');
        const editModal = document.getElementById('editModal');
        
        // Observer untuk detect kapan modal muncul
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    if (!addModal.classList.contains('hidden')) {
                        // Clear semua field di add modal
                        document.getElementById('addName').value = '';
                        document.getElementById('addUsername').value = '';
                        document.getElementById('addPassword').value = '';
                        document.getElementById('addRole').value = '';
                    }
                    if (!editModal.classList.contains('hidden')) {
                        // Clear password di edit modal
                        document.getElementById('editPassword').value = '';
                    }
                }
            });
        });
        
        observer.observe(addModal, { attributes: true });
        observer.observe(editModal, { attributes: true });
    });
</script>
@endsection