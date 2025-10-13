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
                    <th class="py-3 px-4 text-left">Password</th>
                    <th class="py-3 px-4 text-left">Role</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @php
                    $users = [
                        ['nama'=>'Ingrid Febrianti','username'=>'inggridje1212','password'=>'','role'=>'Admin'],
                        ['nama'=>'Nurul Meyti Dea Putri','username'=>'nurulmeyti99','password'=>'','role'=>'Pegawai'],
                        ['nama'=>'Syafira Fatwa','username'=>'firalopelope1212','password'=>'','role'=>'Pegawai'],
                        ['nama'=>'Muhammad Dicky Armansyah','username'=>'muhdicky123','password'=>'','role'=>'Pegawai'],
                        ['nama'=>'Febri Saputra','username'=>'febrisaputra0808','password'=>'','role'=>'Admin'],
                        ['nama'=>'Agung','username'=>'agunggagung02','password'=>'','role'=>'Pegawai'],
                        ['nama'=>'Akbar','username'=>'akbar12345','password'=>'','role'=>'Pegawai'],
                        ['nama'=>'Asep','username'=>'asep23234','password'=>'','role'=>'Pegawai'],
                        ['nama'=>'Dewi','username'=>'dewi99827','password'=>'','role'=>'Pegawai'],
                        ['nama'=>'Indah','username'=>'indah34324','password'=>'','role'=>'Pegawai'],
                    ];
                @endphp

                @foreach ($users as $index => $u)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4">{{ $index + 1 }}</td>
                        <td class="py-2 px-4">{{ $u['nama'] }}</td>
                        <td class="py-2 px-4">{{ $u['username'] }}</td>
                        <td class="py-2 px-4">{{ $u['password'] }}</td>
                        <td class="py-2 px-4">{{ $u['role'] }}</td>
                        <td class="py-2 px-4 text-center">
                            <button class="text-blue-600 hover:text-blue-800 mr-3" title="Edit">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-800" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Modal Tambah Pengguna --}}
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-1/3 p-6">
            <h2 class="text-xl font-bold mb-4">Tambah Pengguna</h2>
            <form>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Nama Pengguna</label>
                    <input type="text" placeholder="Masukkan nama"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Username</label>
                    <input type="text" placeholder="Masukkan username"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Password</label>
                    <input type="password" placeholder="Masukkan password"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Role</label>
                    <select class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                        <option>Pilih Role</option>
                        <option>Admin</option>
                        <option>Pegawai</option>
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
</div>

{{-- Script untuk modal --}}
<script>
    function openModal() {
        document.getElementById('addModal').classList.remove('hidden');
        document.getElementById('addModal').classList.add('flex');
    }
    function closeModal() {
        document.getElementById('addModal').classList.add('hidden');
    }
</script>
@endsection