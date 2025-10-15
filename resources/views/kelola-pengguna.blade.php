@extends('layouts.admin')

@section('content')
<div class="p-6">

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
                        <td class="py-2 px-4">{{ $u->role }}</td>
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
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium">Nama Pengguna</label>
                    <input type="text" name="name" placeholder="Masukkan nama"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Username</label>
                    <input type="text" name="username" placeholder="Masukkan username"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Password</label>
                    <input type="password" name="password" placeholder="Masukkan password"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Role</label>
                    <select name="role"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                        <option value="">Pilih Role</option>
                        <option value="Admin">Admin</option>
                        <option value="Pegawai">Pegawai</option>
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
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block text-sm font-medium">Nama Pengguna</label>
                    <input type="text" id="editName" name="name"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Username</label>
                    <input type="text" id="editUsername" name="username"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Password (biarkan kosong jika tidak diubah)</label>
                    <input type="password" name="password"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Role</label>
                    <select id="editRole" name="role"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                        <option value="Admin">Admin</option>
                        <option value="Pegawai">Pegawai</option>
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
        document.getElementById('addModal').classList.remove('hidden');
        document.getElementById('addModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('addModal').classList.remove('flex');
        document.getElementById('addModal').classList.add('hidden');
    }

    function openEditModal(id, name, username, role) {
        const modal = document.getElementById('editModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.getElementById('editName').value = name;
        document.getElementById('editUsername').value = username;
        document.getElementById('editRole').value = role;

        const form = document.getElementById('editForm');
        form.action = `/users/${id}`;
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('flex');
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endsection