@extends('layouts.admin')

@section('content')
<!-- GANTI: style shadow untuk semua item dalam grid -->
<style>
    /* filepath: c:\Users\asus\DB-TBG\resources\views\dashboard.blade.php */
    /* Base: posisi dan transisi untuk kartu */
    .grid > * {
        position: relative;            /* diperlukan untuk pseudo-element glow */
        overflow: visible;             /* pastikan bayangan tidak terpotong */
        border-radius: 0.75rem;        /* rounded-xl konsisten */
        transition: transform 220ms cubic-bezier(.2,.8,.2,1), 
                    box-shadow 220ms cubic-bezier(.2,.8,.2,1), 
                    opacity 220ms;
    }

    /* Kartu default shadow (lebih kentara) */
    .grid > * {
        box-shadow: 0 14px 40px rgba(2, 44, 184, 0.08), 0 6px 18px rgba(0,0,0,0.06);
        background-clip: padding-box;
    }

    /* Hover: efek lift dan shadow lebih tebal */
    .grid > *:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 70px rgba(2, 44, 184, 0.12), 0 14px 28px rgba(0,0,0,0.10);
        z-index: 10;
    }

    /* Soft colored glow menggunakan pseudo-element */
    .grid > *::before {
        content: "";
        position: absolute;
        inset: -6px; /* sedikit meluas keluar untuk glow */
        border-radius: inherit;
        background: linear-gradient(120deg, rgba(34,197,94,0.04), rgba(37,99,235,0.03) 50%, rgba(239,68,68,0.02));
        filter: blur(14px);
        opacity: 0.9;
        pointer-events: none;
        transition: opacity 220ms, transform 220ms;
    }
    .grid > *:hover::before {
        transform: scale(1.03);
        opacity: 1;
    }

    /* Target elemen .bg-white untuk variasi warna bayangan */
    .grid .bg-white {
        box-shadow: 0 16px 48px rgba(2, 44, 184, 0.07), 0 8px 22px rgba(0,0,0,0.06);
    }

    /* Pastikan elemen dengan rounded-xl tetap rapi */
    .rounded-xl { overflow: visible; }
</style>

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-3xl font-bold">Selamat Datang, {{ session('name') }}</h2>
        <div class="flex gap-4">
            <x-service-sto-dropdown :selectedServiceArea="$selectedServiceArea" :selectedSto="$selectedSto" />
        </div>
    </div>

    <div class="grid grid-cols-4 gap-4 mb-4">
        <div class="col-span-1 flex items-center justify-center bg-white p-4 rounded-xl shadow gap-4">
            <img src="{{ asset('assets/icon/tower.png') }}" alt="Terverifikasi" class="w-16 h-16">
            <div class="text-left">
                <h3 class="text-[#022CB8] font-semibold">TOTAL</h3>
                <p class="text-4xl font-bold text-black-600">{{ $totalSites  }}</p>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined {{ $color }} text-sm">{{ $icon }}</span>
                    @if ($growth > 0)
                        <p class="{{ $color }} text-sm">+{{ $growth }} dari bulan lalu</p>
                    @elseif ($growth < 0)
                        <p class="{{ $color }} text-sm">{{ $growth }} dari bulan lalu</p>
                    @else
                        <p class="{{ $color }} text-sm">0 dari hari kemarin ini</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-span-1 flex items-center justify-center bg-white p-4 rounded-xl shadow gap-4">
            <img src="{{ asset('assets/icon/visit.png') }}" alt="Sudah Visit" class="w-16 h-16">
            <div class="text-left">
                <h3 class="text-[#022CB8] font-semibold">SUDAH VISIT</h3>
                <p class="text-4xl font-bold text-black-600">{{ $visitedSites }}</p>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined {{ $colorVisit }} text-sm">{{ $iconVisit }}</span>
                    @if ($growthVisit > 0)
                        <p class="{{ $colorVisit }} text-sm">+{{ $growthVisit }} dari hari kemarin</p>
                    @elseif ($growthVisit < 0)
                        <p class="{{ $colorVisit }} text-sm">{{ $growthVisit }} dari hari kemarin</p>
                    @else
                        <p class="{{ $colorVisit }} text-sm">0 dari hari kemarin</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-span-1 flex items-center justify-center bg-white p-4 rounded-xl shadow gap-4">
            <img src="{{ asset('assets/icon/visitno.png') }}" alt="Belum Visit" class="w-16 h-16">
            <div class="text-left">
                <h3 class="text-[#022CB8] font-semibold">BELUM VISIT</h3>
                <p class="text-4xl font-bold text-black-600">{{ $notVisitedSites }}</p>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined {{ $colorNotVisit }} text-sm">{{ $iconNotVisit }}</span>
                    @if ($growthNotVisit > 0)
                        <p class="text-red-500 text-sm">-{{ $growthNotVisit }} dari hari kemarin</p>
                    @else
                        <p class="{{ $colorNotVisit }} text-sm">0 dari hari kemarin</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-span-1 bg-white p-4 rounded-xl shadow">
            <div class="grid grid-cols-2 gap-2">
                <div class="flex items-center gap-4">
                    <div class="text-left p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <img src="{{ asset('assets/icon/persen.png') }}" alt="Persentase" class="w-8 h-8">
                            <h3 class="text-[#022CB8] font-semibold">Persentase</h3>
                        </div>
                        <p class="text-4xl font-bold text-black-600">{{ $visitPercentage }}%</p>
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
                            <p class="text-3xl text- font-bold text-white">8</p>
                        </div>
                    </div>
                    
                    {{-- STO Stats --}}
                    <div class="flex items-center gap-2 text-center">
                        <img src="{{ asset('assets/icon/sto.png') }}" alt="STO" class="w-6 h-6">
                        <div class="text-center">
                            <h3 class="text-xs text-white font-semibold">STO</h3>
                            <p class="text-3xl font-bold text-white">22</p>
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
            <canvas id="pieChartFO"></canvas>
            <div id="pieChartFOLegend" class="flex justify-center gap-8"></div>
        </div>

        <div class="bg-white p-4 rounded-xl shadow">
            <h4 class="font-semibold mb-3">MMP</h4>
            <canvas id="pieChartMMP"></canvas>
            <div id="pieChartMMPLegend" class="flex justify-center gap-8"></div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow">
        <h4 class="font-semibold mb-3">Update Progress PM</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm text-center">
                <thead>
                    <tr class="bg-blue-900 text-white">
                        <th rowspan="2" class="border px-3 py-2 align-middle">Service Area</th>
                        <th rowspan="2" class="border px-3 py-2 align-middle">STO</th>
                        <th rowspan="2" class="border px-3 py-2 align-middle">Jumlah Teknisi</th>
                        <th rowspan="2" class="border px-3 py-2 align-middle">Progres HI</th>
                        <th colspan="3" class="border px-3 py-2 bg-red-700">Belum Visit</th>
                        <th colspan="3" class="border px-3 py-2 bg-green-700">Sudah Visit</th>
                        <th colspan="3" class="border px-3 py-2 bg-gray-700">Grand Total</th>
                        <th rowspan="2" class="border px-3 py-2 align-middle">%</th>
                        <th rowspan="2" class="border px-3 py-2 align-middle">Keterangan</th>
                    </tr>
                    <tr class=" font-semibold text-white">
                        <th class="border px-2 py-1 bg-red-700">IN FO</th>
                        <th class="border px-2 py-1 bg-red-700">MMP</th>
                        <th class="border px-2 py-1 bg-red-700">ALL</th>
                        <th class="border px-2 py-1 bg-green-700">IN FO</th>
                        <th class="border px-2 py-1 bg-green-700">MMP</th>
                        <th class="border px-2 py-1 bg-green-700">ALL</th>
                        <th class="border px-2 py-1 bg-gray-700">IN FO</th>
                        <th class="border px-2 py-1 bg-gray-700">MMP</th>
                        <th class="border px-2 py-1 bg-gray-700">ALL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($summary as $row)
                        @php
                            $belumAll = $row->notvisit_fo + $row->notvisit_mmp;
                            $sudahAll = $row->visited_fo + $row->visited_mmp;
                            $grandAll = $belumAll + $sudahAll;

                            // Mendapatkan key dan jumlah baris (rowspan) untuk merge service_area
                            $currentServiceArea = $row->service_area;
                            $printServiceArea = false;
                            if (!isset($serviceAreaCounter)) $serviceAreaCounter = [];
                            if (!isset($serviceAreaFirstRow)) $serviceAreaFirstRow = [];

                            if (!array_key_exists($currentServiceArea, $serviceAreaCounter)) {
                                // hitung berapa sto dalam summary untuk service area ini
                                $serviceAreaCounter[$currentServiceArea] = $summary->where('service_area', $currentServiceArea)->count();
                                // row ke berapa dari summary (dapatkan $loop->index dari baris service_area pertama)
                                $serviceAreaFirstRow[$currentServiceArea] = $loop->index;
                            }
                            if ($loop->index === $serviceAreaFirstRow[$currentServiceArea]) {
                                $printServiceArea = true;
                                $rowspan = $serviceAreaCounter[$currentServiceArea];
                            }
                        @endphp
                        <tr class="hover:bg-gray-50">
                            @if($printServiceArea)
                                <td class="border px-2 py-1 font-semibold text-left" rowspan="{{ $rowspan }}">
                                    {{ $row->service_area }}
                                </td>
                            @endif
                            <td class="border px-2 py-1">{{ $row->sto }}</td>
                            <td class="border px-2 py-1">{{ $row->jumlah_teknisi }}</td>
                            <td class="border px-2 py-1 bg-blue-100 font-bold text-blue-800">{{ $row->visited_today }}</td>

                            {{-- Belum Visit --}}
                            <td class="border px-2 py-1 bg-red-100 text-red-700">{{ $row->notvisit_fo }}</td>
                            <td class="border px-2 py-1 bg-red-100 text-red-700">{{ $row->notvisit_mmp }}</td>
                            <td class="border px-2 py-1 bg-red-200 text-red-800 font-semibold">{{ $belumAll }}</td>

                            {{-- Sudah Visit --}}
                            <td class="border px-2 py-1 bg-green-100 text-green-800">{{ $row->visited_fo }}</td>
                            <td class="border px-2 py-1 bg-green-100 text-green-800">{{ $row->visited_mmp }}</td>
                            <td class="border px-2 py-1 bg-green-200 text-green-900 font-semibold">{{ $sudahAll }}</td>

                            {{-- Grand Total --}}
                            <td class="border px-2 py-1 bg-gray-100">{{ $row->visited_fo + $row->notvisit_fo }}</td>
                            <td class="border px-2 py-1 bg-gray-100">{{ $row->visited_mmp + $row->notvisit_mmp }}</td>
                            <td class="border px-2 py-1 bg-gray-200 font-semibold">{{ $grandAll }}</td>

                            <!-- Persentase -->
                            <td class="border px-3 py-2 font-semibold
                                {{ $row->percent == 0 ? 'bg-gray-200 text-gray-700' : ($row->percent == 100 ? 'bg-green-100 text-green-700' : 'bg-red-200 text-red-700') }}">
                                {{ number_format($row->percent, 2) }}%
                            </td>

                            <td class="border px-2 py-1 text-sm text-gray-700 {{ $row->percent == 0 ? 'bg-gray-200 text-gray-700' : ($row->percent == 100 ? 'bg-green-100 text-green-700 font-bold' : 'bg-red-200 text-red-700') }}">
                                {{ $row->keterangan }}
                            </td>
                        </tr>
                        @if ($loop->last)
                            @php
                                // Total keseluruhan untuk setiap kolom
                                $totalJumlahTeknisi = $summary->sum('jumlah_teknisi');
                                $totalVisitedToday = $summary->sum('visited_today');
                                $totalNotVisitFO = $summary->sum('notvisit_fo');
                                $totalNotVisitMMP = $summary->sum('notvisit_mmp');
                                $totalBelumAll = $summary->sum(function($row){
                                    return $row->notvisit_fo + $row->notvisit_mmp;
                                });
                                $totalVisitedFO = $summary->sum('visited_fo');
                                $totalVisitedMMP = $summary->sum('visited_mmp');
                                $totalSudahAll = $summary->sum(function($row){
                                    return $row->visited_fo + $row->visited_mmp;
                                });
                                $totalGrandFO = $summary->sum(function($row){
                                    return $row->visited_fo + $row->notvisit_fo;
                                });
                                $totalGrandMMP = $summary->sum(function($row){
                                    return $row->visited_mmp + $row->notvisit_mmp;
                                });
                                $totalGrandAll = $summary->sum('total');
                                // Persentase total
                                $totalPercent = $totalGrandAll > 0
                                    ? number_format(($totalSudahAll / $totalGrandAll) * 100, 2)
                                    : '0.00';
                            @endphp
                            <tr class="font-bold bg-blue-100">
                                <td class="border px-2 py-2 text-center" colspan="2">TOTAL</td>
                                <td class="border px-2 py-2 text-center">{{ $totalJumlahTeknisi }}</td>
                                <td class="border px-2 py-2 text-center bg-blue-200">{{ $totalVisitedToday }}</td>
                                <td class="border px-2 py-2 text-center bg-red-200">{{ $totalNotVisitFO }}</td>
                                <td class="border px-2 py-2 text-center bg-red-200">{{ $totalNotVisitMMP }}</td>
                                <td class="border px-2 py-2 text-center bg-red-300">{{ $totalBelumAll }}</td>
                                <td class="border px-2 py-2 text-center bg-green-200">{{ $totalVisitedFO }}</td>
                                <td class="border px-2 py-2 text-center bg-green-200">{{ $totalVisitedMMP }}</td>
                                <td class="border px-2 py-2 text-center bg-green-300">{{ $totalSudahAll }}</td>
                                <td class="border px-2 py-2 text-center bg-gray-200">{{ $totalGrandFO }}</td>
                                <td class="border px-2 py-2 text-center bg-gray-200">{{ $totalGrandMMP }}</td>
                                <td class="border px-2 py-2 text-center bg-gray-300">{{ $totalGrandAll }}</td>
                                <td class="border px-2 py-2 text-center bg-blue-200">{{ $totalPercent }}%</td>
                                <td class="border px-2 py-2 text-center bg-blue-200">-</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const visitData = @json($dailyVisits);
        const dailyTarget = @json($dailyTarget);
        const daysInMonth = @json($daysInMonth);
        const ctx = document.getElementById('trendChart').getContext('2d');
        const gradientBlue = ctx.createLinearGradient(0, 0, 0, 300);
        gradientBlue.addColorStop(0, 'rgba(255, 255, 255, 0)');
        gradientBlue.addColorStop(1, 'rgba(255, 255, 255, 0.05)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: Array.from({ length: daysInMonth }, (_, i) => i + 1),
                datasets: [
                    {
                        label: 'Jumlah Visit per Hari',
                        data: visitData,
                        borderColor: '#2563EB',
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
                        label: `Target (${dailyTarget})`,
                        data: Array(daysInMonth).fill(dailyTarget),
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
        const chartData = @json($chartData);

        // Fungsi membuat pie chart
        function createPieChart(elementId, legendId, data, label) {
            const visit = data.visited || 0;
            const notVisit = data.notVisited || 0;
            const total = visit + notVisit || 1; // hindari pembagian 0

            const ctx = document.getElementById(elementId);
            const chart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Sudah Visit', 'Belum Visit'],
                    datasets: [{
                        data: [visit, notVisit],
                        backgroundColor: ['#22c55e', '#ef4444']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // Custom legend
            const legendContainer = document.getElementById(legendId);
            const legendHTML = `
                <div class="flex items-center justify-between gap-6 mt-4">
                    <!-- Visit -->
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-green-500 text-sm">where_to_vote</span>
                        <span class="font-medium text-xs">Sudah Visit: ${visit}</span>
                        <span class="text-gray-400 text-xs"> ${((visit / total) * 100).toFixed(1)}%</span>
                    </div>

                    <!-- Belum Visit -->
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-red-500 text-sm">location_off</span>
                        <span class="font-medium text-xs">Belum Visit: ${notVisit}</span>
                        <span class="text-gray-400 text-xs">${((notVisit / total) * 100).toFixed(1)}%</span>
                    </div>
                </div>
            `;
            legendContainer.innerHTML = legendHTML;
        }

        // Render kedua chart
        createPieChart('pieChartFO', 'pieChartFOLegend', chartData['INTERSITE FO'] || { visited: 0, notVisited: 0 }, 'INTERSITE FO');
        createPieChart('pieChartMMP', 'pieChartMMPLegend', chartData['MMP'] || { visited: 0, notVisited: 0 }, 'MMP');
    </script>
@endsection
