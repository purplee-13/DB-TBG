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
                        ['id'=>'2802351003','name'=>'TBG Lengkidi Ex New-Redep259','teknisi'=>'AKBAR','tgl'=>'06/10/2025','progres'=>'Sudah Visit','operator'=>'XL','ket'=>'Done'],
                        ['id'=>'2802361003','name'=>'MARINDING (RD TO BONELEMO)','teknisi'=>'AKBAR','tgl'=>'06/10/2025','progres'=>'Belum Visit','operator'=>'XL','ket'=>'Done'],
                        ['id'=>'2802371003','name'=>'SUL-SR-MJN-0177;SUL-SR-MJN-0203','teknisi'=>'AKBAR','tgl'=>'06/10/2025','progres'=>'Belum Visit','operator'=>'XL','ket'=>'Done'],
                        ['id'=>'2802381003','name'=>'29ENR0031;29ENR0032','teknisi'=>'AKBAR','tgl'=>'06/10/2025','progres'=>'Sudah Visit','operator'=>'XL','ket'=>'Done'],
                        ['id'=>'2802391003','name'=>'TBG Rumah Dinas Gubernur Sulbar','teknisi'=>'AKBAR','tgl'=>'06/10/2025','progres'=>'Belum Visit','operator'=>'XL','ket'=>'Done'],
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
                            <button class="text-blue-600 hover:text-blue-800" title="Edit">
                                <i class="fas fa-pen"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
