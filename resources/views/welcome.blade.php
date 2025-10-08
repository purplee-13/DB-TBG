@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h2 class="text-3xl font-bold text-[#022CB8]">Selamat Datang, FIRA!</h2>
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
    <div class="flex items-center justify-center bg-white p-4 rounded-xl shadow gap-4 hover:shadow-lg transition">
        <img src="{{ asset('assets/icon/tower.png') }}" alt="Total" class="w-20 h-20">
        <div class="text-left">
            <h3 class="text-[#022CB8] font-semibold">TOTAL</h3>
            <p class="text-4xl font-bold">500</p>
            <p class="text-green-500 text-sm">+1 dari bulan lalu</p>
        </div>
    </div>

    <div class="flex items-center justify-center bg-white p-4 rounded-xl shadow gap-4 hover:shadow-lg transition">
        <img src="{{ asset('assets/icon/visit.png') }}" alt="Sudah Visit" class="w-20 h-20">
        <div class="text-left">
            <h3 class="text-[#022CB8] font-semibold">SUDAH VISIT</h3>
            <p class="text-4xl font-bold">350</p>
            <p class="text-green-500 text-sm">+1 dari bulan lalu</p>
        </div>
    </div>

    <div class="flex items-center justify-center bg-white p-4 rounded-xl shadow gap-4 hover:shadow-lg transition">
        <img src="{{ asset('assets/icon/visitno.png') }}" alt="Belum Visit" class="w-20 h-20">
        <div class="text-left">
            <h3 class="text-[#022CB8] font-semibold">BELUM VISIT</h3>
            <p class="text-4xl font-bold">150</p>
            <p class="text-red-500 text-sm">-19 dari bulan lalu</p>
        </div>
    </div>

    <div class="flex items-center justify-center bg-white p-4 rounded-xl shadow gap-4 hover:shadow-lg transition">
        <img src="{{ asset('assets/icon/persen.png') }}" alt="Persentase" class="w-20 h-20">
        <div class="text-left">
            <h3 class="text-[#022CB8] font-semibold">PERSENTASE</h3>
            <p class="text-4xl font-bold">0.2%</p>
            <p class="text-gray-500 text-sm">telah visit dari total Sites</p>
        </div>
    </div>
</div>

{{-- Grafik dan Pie --}}
<div class="grid grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-6 rounded-xl shadow col-span-2">
        <h4 class="font-semibold mb-3 text-[#022CB8]">Trend Visit PM CSA per Oktober</h4>
        <canvas id="trendChart" class="w-full"></canvas>
    </div>
    <div class="bg-white p-6 rounded-xl shadow">
        <h4 class="font-semibold mb-3 text-[#022CB8]">INTERSITE FO</h4>
        <canvas id="pieChart" class="w-full"></canvas>
    </div>
</div>

{{-- Tabel Update Progres --}}
<div class="bg-white p-4 rounded-xl shadow">
    <h4 class="font-semibold mb-3 text-[#022CB8]">Update Progress PM</h4>
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
    const ctx = document.getElementById('trendChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(37, 99, 235, 0.3)');
    gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

    const hariData = Array.from({ length: 31 }, () => Math.floor(Math.random() * 25) + 5);
    const targetData = Array(31).fill(17);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: Array.from({ length: 31 }, (_, i) => i + 1),
            datasets: [
                {
                    label: 'Hari',
                    data: hariData,
                    borderColor: '#3B82F6',
                    backgroundColor: gradient,
                    tension: 0.4,
                    pointBackgroundColor: '#1D4ED8',
                    pointRadius: 4,
                    fill: true,
                    borderWidth: 2
                },
                {
                    label: 'Target',
                    data: targetData,
                    borderColor: '#EF4444',
                    borderDash: [6, 6],
                    pointRadius: 0,
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    title: { display: true, text: 'Tanggal', color: '#555' }
                },
                y: {
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    title: { display: true, text: 'Jumlah Visit', color: '#555' },
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: { color: '#333', font: { size: 12 } }
                }
            }
        }
    });

    // Pie chart
    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: ['Visit', 'Belum Visit'],
            datasets: [{
                data: [75, 25],
                backgroundColor: ['#EF4444', '#60A5FA'],
                hoverOffset: 8
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#333', font: { size: 12 } }
                }
            }
        }
    });
</script>
@endsection
