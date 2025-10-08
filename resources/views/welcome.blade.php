@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-3xl font-bold">Selamat Datang, FIRA!</h2>
        {{-- Filter Dropdown --}}
        <div class="flex gap-4">
            <select class="border rounded-lg px-3 py-2">
                <option>Service Area</option>
                <option>SA Luwu Utara</option>
            </select>
            <select class="border rounded-lg px-3 py-2">
                <option>STO</option>
                <option>MAS</option>
            </select>
        </div>
    </div>

    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="flex items-center justify-center bg-white p-4 rounded-xl shadow gap-4">
                <img src="{{ asset('assets/icon/tower.png') }}" alt="Terverifikasi" class="w-24 h-24">
                <div class="text-left">
                    <h3 class="text-[#022CB8] font-semibold">TOTAL</h3>
                    <p class="text-4xl font-bold text-black-600">500</p>
                    <p class="text-green-500 text-sm">+1 dari bulan lalu</p>
                </div>
            </div>
        
        <div class="flex items-center justify-center bg-white p-4 rounded-xl shadow gap-4">
                <img src="{{ asset('assets/icon/visit.png') }}" alt="Sudah Visit" class="w-24 h-24">
                <div class="text-left">
                    <h3 class="text-[#022CB8] font-semibold">SUDAH VISIT</h3>
                    <p class="text-4xl font-bold text-black-600">350</p>
                    <p class="text-green-500 text-sm">+1 dari bulan lalu</p>
                </div>
            </div>

        <div class="flex items-center justify-center bg-white p-4 rounded-xl shadow gap-4">
                <img src="{{ asset('assets/icon/visitno.png') }}" alt="Belum Visit" class="w-24 h-24">
                <div class="text-left">
                    <h3 class="text-[#022CB8] font-semibold">BELUM VISIT</h3>
                    <p class="text-4xl font-bold text-black-600">150</p>
                    <p class="text-red-500 text-sm">-19 dari bulan lalu</p>
                </div>
            </div>
        
        <div class="flex items-center justify-center bg-white p-4 rounded-xl shadow gap-4">
                <img src="{{ asset('assets/icon/persen.png') }}" alt="Persentase" class="w-24 h-24">
                <div class="text-left">
                    <h3 class="text-[#022CB8] font-semibold">Persentase</h3>
                    <p class="text-4xl font-bold text-black-600">0.2%</p>
                    <p class="text-gray-500 text-sm">telah visit dari total Sites</p>
                </div>
            </div>
    </div>

    {{-- Grafik dan Pie --}}
    <div class="grid grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-4 rounded-xl shadow col-span-2">
            <h4 class="font-semibold mb-3">Trend Visit PM CSA per Oktober</h4>
            <canvas id="trendChart"></canvas>
        </div>
        <div class="bg-white p-4 rounded-xl shadow">
            <h4 class="font-semibold mb-3">INTERSITE FO</h4>
            <canvas id="pieChart"></canvas>
        </div>
    </div>

    {{-- Tabel Update Progres --}}
    <div class="bg-white p-4 rounded-xl shadow">
        <h4 class="font-semibold mb-3">Update Progress PM</h4>
        <table class="w-full text-sm text-left border">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-2 border">Service Area</th>
                    <th class="p-2 border">STO</th>
                    <th class="p-2 border">Jumlah Teknisi</th>
                    <th class="p-2 border">Progress HI</th>
                    <th class="p-2 border">Belum Visit</th>
                    <th class="p-2 border">Sudah Visit</th>
                    <th class="p-2 border">Grand Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-2 border">SA LUWU UTARA</td>
                    <td class="p-2 border">MAS</td>
                    <td class="p-2 border text-center">4</td>
                    <td class="p-2 border text-center">6</td>
                    <td class="p-2 border text-center text-red-600">44</td>
                    <td class="p-2 border text-center text-green-600">11</td>
                    <td class="p-2 border text-center font-semibold">49</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- ChartJS --}}
    <script>
        // Line chart
        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: Array.from({ length: 31 }, (_, i) => i + 1),
                datasets: [{
                    label: 'Total Visit',
                    data: [5, 10, 15, 10, 25, 40, 38, 20, 15, 30, 45, 40, 50, 35, 20],
                    borderColor: 'blue',
                    tension: 0.3
                }]
            }
        });

        // Pie chart
        new Chart(document.getElementById('pieChart'), {
            type: 'pie',
            data: {
                labels: ['Visit', 'Belum Visit'],
                datasets: [{
                    data: [75, 25],
                    backgroundColor: ['#ef4444', '#60a5fa']
                }]
            }
        });
    </script>
@endsection
