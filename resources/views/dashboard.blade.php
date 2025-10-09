@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-3xl font-bold">Selamat Datang, FIRA!</h2>
        <div class="flex gap-4">
            <select class="border rounded-lg px-3 py-2">
                <option>Service Area</option>
                <option>SA Luwu Utara</option>
                <option>SA Majene</option>
                <option>SA Mamuju</option>
                <option>SA Palopo</option>
                <option>SA Parepare</option>
                <option>SA PINRANG</option>
                <option>SA TORAJA</option>
                <option>SA WAJO</option>
            </select>
            <select class="border rounded-lg px-3 py-2">
                <option>STO</option>
                <option>PRE</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-4 gap-4 mb-4">
        <div class="col-span-1 flex items-center justify-center bg-white p-4 rounded-xl shadow gap-4">
            <img src="{{ asset('assets/icon/tower.png') }}" alt="Terverifikasi" class="w-16 h-16">
            <div class="text-left">
                <h3 class="text-[#022CB8] font-semibold">TOTAL</h3>
                <p class="text-4xl font-bold text-black-600">500</p>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-green-500 text-sm">trending_up</span>
                    <p class="text-green-500 text-sm">+1 dari bulan lalu</p>
                </div>
            </div>
        </div>
        <div class="col-span-1 flex items-center justify-center bg-white p-4 rounded-xl shadow gap-4">
            <img src="{{ asset('assets/icon/visit.png') }}" alt="Sudah Visit" class="w-16 h-16">
            <div class="text-left">
                <h3 class="text-[#022CB8] font-semibold">SUDAH VISIT</h3>
                <p class="text-4xl font-bold text-black-600">350</p>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-green-500 text-sm">trending_up</span>
                    <p class="text-green-500 text-sm">+1 dari hari kemarin</p>
                </div>
            </div>
        </div>
        <div class="col-span-1 flex items-center justify-center bg-white p-4 rounded-xl shadow gap-4">
            <img src="{{ asset('assets/icon/visitno.png') }}" alt="Belum Visit" class="w-16 h-16">
            <div class="text-left">
                <h3 class="text-[#022CB8] font-semibold">BELUM VISIT</h3>
                <p class="text-4xl font-bold text-black-600">150</p>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-red-500 text-sm">trending_down</span>
                    <p class="text-red-500 text-sm">-19 dari hari kemarin</p>
                </div>
            </div>
        </div>
        <div class="col-span-1 bg-white p-4 rounded-xl shadow">
            <div class="grid grid-cols-2 gap-2">
                <div class="flex items-center gap-4">
                    <div class="text-left">
                        <div class="flex items-center gap-2 mb-2">
                            <img src="{{ asset('assets/icon/persen.png') }}" alt="Persentase" class="w-8 h-8">
                            <h3 class="text-[#022CB8] font-semibold">Persentase</h3>
                        </div>
                        <p class="text-5xl font-bold text-black-600">0.2%</p>
                        <p class="text-gray-500 text-sm">telah visit dari total Sites</p>
                    </div>
                </div>

                {{-- Right Side: Area & STO Info --}}
                <div class="flex flex-col gap-4 bg-[#5E6784] p-2 rounded-lg items-center justify-center">
                    {{-- Service Area Stats --}}
                    <div class="flex items-center gap-2 text-center">
                        <img src="{{ asset('assets/icon/area.png') }}" alt="Service Area" class="w-6 h-6">
                        <div class="text-center">
                            <h3 class="text-xs text-white font-semibold">Service Area</h3>
                            <p class="text-3xl text- font-bold text-white">6</p>
                        </div>
                    </div>
                    
                    {{-- STO Stats --}}
                    <div class="flex items-center gap-2 text-center">
                        <img src="{{ asset('assets/icon/sto.png') }}" alt="STO" class="w-6 h-6">
                        <div class="text-center">
                            <h3 class="text-xs text-white font-semibold">STO</h3>
                            <p class="text-3xl font-bold text-white">12</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-4 rounded-xl shadow col-span-2">
            <h4 class="font-semibold mb-3">Trend Visit PM CSA per Oktober</h4>
            <div style="height: 300px;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl shadow">
            <h4 class="font-semibold mb-3">INTERSITE FO</h4>
            <canvas id="pieChart1"></canvas>
        </div>

        <div class="bg-white p-4 rounded-xl shadow">
            <h4 class="font-semibold mb-3">MMP</h4>
            <canvas id="pieChart2"></canvas>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow">
        <h4 class="font-semibold mb-3">Update Progress PM</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm text-center">
                <thead>
                    <tr class="bg-blue-900 text-white">
                        <th class="border px-3 py-2">Service Area</th>
                        <th class="border px-3 py-2">STO</th>
                        <th class="border px-3 py-2">Jumlah Teknisi</th>
                        <th class="border px-3 py-2">Progres HI</th>
                        <th class="border px-3 py-2 bg-red-700" colspan="3">Belum Visit</th>
                        <th class="border px-3 py-2 bg-green-700" colspan="3">Sudah Visit</th>
                        <th class="border px-3 py-2 bg-gray-700" colspan="3">Grand Total</th>
                        <th class="border px-3 py-2">%</th>
                        <th class="border px-3 py-2">Keterangan</th>
                    </tr>
                    <tr class="bg-gray-100 font-semibold text-gray-700">
                        <th colspan="4"></th>
                        <th class="border px-2 py-1">IN FO</th>
                        <th class="border px-2 py-1">MMP</th>
                        <th class="border px-2 py-1">ALL</th>
                        <th class="border px-2 py-1">IN FO</th>
                        <th class="border px-2 py-1">MMP</th>
                        <th class="border px-2 py-1">ALL</th>
                        <th class="border px-2 py-1">IN FO</th>
                        <th class="border px-2 py-1">MMP</th>
                        <th class="border px-2 py-1">ALL</th>
                        <th colspan="2"></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $data = [
                            ['SA LUWU UTARA','MAS',4,2,[35,5,40],[14,1,15],[49,6,55],'27,27%','Belum Terassign'],
                            ['SA LUWU UTARA','MLL',1,0,[9,2,11],[0,0,0],[9,2,11],'0,00%','Belum Terassign'],
                            ['SA LUWU UTARA','TMN',2,1,[13,3,16],[11,0,11],[24,3,27],'40,74%','Belum Terassign'],
                            ['SA MAJENE','MAJ',1,4,[5,3,8],[4,0,4],[9,3,12],'33,33%','Belum Terassign'],
                            ['SA MAJENE','MMS',1,0,[1,0,1],[0,0,0],[1,0,1],'0,00%','Belum Terassign'],
                            ['SA MAJENE','PLW',2,1,[22,2,24],[4,2,6],[26,4,30],'20,00%','Belum Terassign'],
                        ];
                    @endphp

                    @foreach($data as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-2 py-1 font-semibold text-left">{{ $row[0] }}</td>
                            <td class="border px-2 py-1">{{ $row[1] }}</td>
                            <td class="border px-2 py-1">{{ $row[2] }}</td>
                            <td class="border px-2 py-1 bg-blue-100 font-bold text-blue-800">{{ $row[3] }}</td>

                            {{-- Belum Visit --}}
                            <td class="border px-2 py-1 bg-red-100 text-red-700">{{ $row[4][0] }}</td>
                            <td class="border px-2 py-1 bg-red-100 text-red-700">{{ $row[4][1] }}</td>
                            <td class="border px-2 py-1 bg-red-200 text-red-800 font-semibold">{{ $row[4][2] }}</td>

                            {{-- Sudah Visit --}}
                            <td class="border px-2 py-1 bg-green-100 text-green-800">{{ $row[5][0] }}</td>
                            <td class="border px-2 py-1 bg-green-100 text-green-800">{{ $row[5][1] }}</td>
                            <td class="border px-2 py-1 bg-green-200 text-green-900 font-semibold">{{ $row[5][2] }}</td>

                            {{-- Grand Total --}}
                            <td class="border px-2 py-1 bg-gray-100">{{ $row[6][0] }}</td>
                            <td class="border px-2 py-1 bg-gray-100">{{ $row[6][1] }}</td>
                            <td class="border px-2 py-1 bg-gray-200 font-semibold">{{ $row[6][2] }}</td>

                            {{-- Persentase --}}
                            <td class="border px-2 py-1 font-bold {{ floatval(str_replace(',','.',rtrim($row[7],'%'))) > 30 ? 'bg-green-100 text-green-700' : 'bg-red-600 text-white' }}">
                                {{ $row[7] }}
                            </td>

                            {{-- Keterangan --}}
                            <td class="border px-2 py-1 text-sm text-gray-700">{{ $row[8] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('trendChart').getContext('2d');
        const gradientBlue = ctx.createLinearGradient(0, 0, 0, 300);
        gradientBlue.addColorStop(0, 'rgba(37, 99, 235, 0.5)');
        gradientBlue.addColorStop(1, 'rgba(37, 99, 235, 0.05)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: Array.from({ length: 31 }, (_, i) => i + 1),
                datasets: [
                    {
                        label: 'Jumlah Visit per Hari',
                        data: [5, 8, 10, 12, 15, 16, 14, 18, 19, 20, 23, 21, 25, 26, 28, 27, 30, 29, 31, 33, 35, 38, 37, 36, 39, 40, 41, 43, 44, 45, 46],
                        borderColor: '#2563EB',
                        backgroundColor: gradientBlue,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#2563EB',
                        pointBorderColor: '#fff',
                        pointHoverRadius: 6,
                        pointRadius: 4,
                        pointHoverBackgroundColor: '#1E40AF',
                    },
                    {
                        label: 'Target (17)',
                        data: Array(31).fill(17),
                        borderColor: '#EF4444',
                        borderWidth: 2,
                        borderDash: [8, 6],
                        tension: 0.1,
                        pointRadius: 0,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: { display: true, text: 'Hari', color: '#374151', font: { weight: 'bold' } },
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Jumlah Visit', color: '#374151', font: { weight: 'bold' } },
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    }
                },
                plugins: {
                    legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 10, padding: 15 } },
                    tooltip: { backgroundColor: '#1E293B', titleColor: '#fff', bodyColor: '#E5E7EB', borderColor: '#3B82F6', borderWidth: 1 }
                }
            }
        });

        // Pie chart INTERSITE FO
                // Replace the existing pieChart1 configuration with:
        new Chart(document.getElementById('pieChart1'), {
            type: 'pie',
            data: {
                labels: ['Belum Visit', 'Visit'],
                datasets: [{
                    data: [75, 25],
                    backgroundColor: ['#ef4444', '#22c55e']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    const dataset = data.datasets[0];
                                    const total = dataset.data.reduce((a,b) => a+b, 0);
                                    return data.labels.map((label, i) => {
                                        const value = dataset.data[i];
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return {
                                            text: `<span class="material-symbols-outlined" style="color: ${dataset.backgroundColor[i]}">
                                                    ${label === 'Visit' ? 'where_to_vote' : 'location_off'}
                                                  </span> ${label} (${value}) - ${percentage}%`,
                                            fillStyle: dataset.backgroundColor[i],
                                            hidden: isNaN(dataset.data[i]),
                                            index: i,
                                            textAlign: 'left'
                                        };
                                    });
                                }
                                return [];
                            },
                            font: {
                                family: "'Inter', sans-serif"
                            },
                            padding: 20,
                            useHTML: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a,b) => a+b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${context.label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        // Pie chart MMP
        new Chart(document.getElementById('pieChart2'), {
            type: 'pie',
            data: {
                labels: ['Belum Visit', 'Visit'],
                datasets: [{ data: [45, 35], backgroundColor: ['#ef4444', '#22c55e'] }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let value = context.raw;
                                let total = context.dataset.data.reduce((a,b) => a+b, 0);
                                let percentage = ((value / total) * 100).toFixed(2);
                                return `${context.label}: ${percentage}% (${value})`;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
