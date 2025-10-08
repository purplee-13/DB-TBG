@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    {{-- Header & Greeting --}}
    <div class="flex justify-between items-center mb-6 bg-gradient-to-r from-blue-600 to-indigo-700 p-6 rounded-xl shadow text-white">
        <div>
            <h2 class="text-3xl font-bold">Selamat Datang, <span class="text-yellow-300">FIRA!</span></h2>
            <p class="text-sm opacity-90 mt-1">Pantau status dan progres maintenance tower hari ini.</p>
        </div>
        {{-- Filter Dropdown --}}
        <div class="flex gap-3">
            <select class="bg-white text-gray-700 border-none rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
                <option>Service Area</option>
                <option>SA Luwu Utara</option>
            </select>
            <select class="bg-white text-gray-700 border-none rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
                <option>STO</option>
                <option>MAS</option>
            </select>
        </div>
    </div>

    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $cards = [
                ['img' => 'tower.png', 'title' => 'TOTAL', 'value' => '500', 'color' => 'text-green-500', 'desc' => '+1 dari bulan lalu'],
                ['img' => 'visit.png', 'title' => 'SUDAH VISIT', 'value' => '350', 'color' => 'text-green-500', 'desc' => '+1 dari bulan lalu'],
                ['img' => 'visitno.png', 'title' => 'BELUM VISIT', 'value' => '150', 'color' => 'text-red-500', 'desc' => '-19 dari bulan lalu'],
                ['img' => 'persen.png', 'title' => 'PERSENTASE', 'value' => '0.2%', 'color' => 'text-blue-500', 'desc' => 'telah visit dari total Sites'],
            ];
        @endphp

        @foreach($cards as $card)
            <div class="bg-white p-5 rounded-2xl shadow hover:shadow-lg transition duration-300 flex items-center gap-4">
                <img src="{{ asset('assets/icon/' . $card['img']) }}" class="w-20 h-20" alt="">
                <div>
                    <h3 class="text-blue-800 font-semibold text-sm uppercase">{{ $card['title'] }}</h3>
                    <p class="text-4xl font-bold">{{ $card['value'] }}</p>
                    <p class="{{ $card['color'] }} text-sm">{{ $card['desc'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Grafik & Pie --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow col-span-2">
            <h4 class="font-semibold text-gray-700 mb-4">📈 Trend Visit PM CSA - Oktober</h4>
            <canvas id="trendChart"></canvas>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow flex flex-col justify-center items-center">
            <h4 class="font-semibold text-gray-700 mb-4">🌐 INTERSITE FO</h4>
            <canvas id="pieChart" width="150" height="150"></canvas>
        </div>
    </div>

    {{-- Tabel Progress --}}
    <div class="bg-white p-6 rounded-2xl shadow">
        <h4 class="font-semibold text-gray-700 mb-4">🛠️ Update Progress PM</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-blue-50 text-gray-800 uppercase text-xs">
                    <tr>
                        <th class="p-3 border">Service Area</th>
                        <th class="p-3 border">STO</th>
                        <th class="p-3 border">Jumlah Teknisi</th>
                        <th class="p-3 border">Progress HI</th>
                        <th class="p-3 border">Belum Visit</th>
                        <th class="p-3 border">Sudah Visit</th>
                        <th class="p-3 border">Grand Total</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <tr class="hover:bg-blue-50 transition">
                        <td class="p-3 border font-medium">SA LUWU UTARA</td>
                        <td class="p-3 border">MAS</td>
                        <td class="p-3 border text-center">4</td>
                        <td class="p-3 border text-center">6</td>
                        <td class="p-3 border text-center text-red-600 font-semibold">44</td>
                        <td class="p-3 border text-center text-green-600 font-semibold">11</td>
                        <td class="p-3 border text-center font-bold text-blue-800">49</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ChartJS --}}
<script>
const ctx = document.getElementById('trendChart');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: Array.from({ length: 31 }, (_, i) => i + 1),
        datasets: [
            {
                label: 'Hari',
                data: [5, 10, 15, 10, 25, 40, 38, 20, 15, 30, 45, 40, 50, 35, 20],
                borderColor: '#2563EB',
                backgroundColor: 'rgba(37, 99, 235, 0.2)',
                tension: 0.4,
                borderWidth: 3,
                fill: true
            },
            {
                label: 'Target',
                data: Array(31).fill(17),
                borderColor: '#EF4444',
                backgroundColor: '#EF4444',
                borderDash: [8, 5],
                tension: 0.3,
                borderWidth: 2
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true } }
    }
});

new Chart(document.getElementById('pieChart'), {
    type: 'doughnut',
    data: {
        labels: ['Visit', 'Belum Visit'],
        datasets: [{
            data: [75, 25],
            backgroundColor: ['#22C55E', '#94A3B8']
        }]
    },
    options: {
        plugins: { legend: { position: 'bottom' } }
    }
});
</script>
@endsection