@extends('layouts.admin')

@section('content')
<div class="p-6">
    {{-- Notifikasi --}}
    @if(session('success'))
        <div id="success-alert" class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 border border-green-300">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-lg border border-red-300">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Search Bar, Date Filter & STO Filter --}}
    <div class="flex justify-end items-center gap-3 mb-4 flex-wrap">
        {{-- Search --}}
        <form method="GET" action="{{ route('update-maintenance') }}" class="relative" id="searchForm">
            <input type="text" name="search" placeholder="Cari berdasarkan Site ID atau Site Name"
                value="{{ request('search') }}"
                class="border rounded-full pl-10 pr-10 py-2 focus:outline-none focus:ring focus:ring-blue-300 w-80"
                id="searchInput">
            <span class="absolute left-3 top-2.5 text-gray-500">
                <i class="fas fa-search"></i>
            </span>
            @if(request('search'))
                <a href="{{ route('update-maintenance') }}@if(request('filter_date'))?filter_date={{ request('filter_date') }}@endif" 
                   class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </a>
            @endif
            {{-- Simpan filter lain --}}
            @if(request('filter_date'))
                <input type="hidden" name="filter_date" value="{{ request('filter_date') }}">
            @endif
            @if(request('sto'))
                <input type="hidden" name="sto" value="{{ request('sto') }}">
            @endif
        </form>

        {{-- Date Filter --}}
        <form method="GET" action="{{ route('update-maintenance') }}" class="relative" id="dateFilterForm">
            <input type="date" name="filter_date" 
                value="{{ request('filter_date') }}"
                class="border rounded-full pl-10 pr-10 py-2 focus:outline-none focus:ring focus:ring-blue-300"
                id="dateFilterInput">
            <span class="absolute left-3 top-2.5 text-gray-500">
                <i class="fas fa-calendar-alt"></i>
            </span>
            @if(request('filter_date'))
                <button type="button" onclick="clearDateFilter()" 
                        class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            @endif
            {{-- Simpan filter lain --}}
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            @if(request('sto'))
                <input type="hidden" name="sto" value="{{ request('sto') }}">
            @endif
        </form>

        {{-- STO Filter --}}
        <form method="GET" action="{{ route('update-maintenance') }}" id="stoFilterForm">
            <select name="sto" id="stoFilter" class="border rounded-full px-4 py-2">
                <option value="">Semua STO</option>
                @foreach($listSto as $sto)
                    <option value="{{ $sto }}" {{ request('sto') == $sto ? 'selected' : '' }}>
                        {{ $sto }}
                    </option>
                @endforeach
            </select>


            {{-- Simpan filter lain --}}
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            @if(request('filter_date'))
                <input type="hidden" name="filter_date" value="{{ request('filter_date') }}">
            @endif
        </form>
    </div>

    {{-- Search Results Info --}}
    @if(request('search') || request('filter_date') || request('sto'))
        <div class="mb-4 text-sm text-gray-600">
            <span class="font-medium">{{ $sites->count() }}</span> hasil ditemukan
            @if(request('search'))
                untuk "<span class="font-medium">{{ request('search') }}</span>"
            @endif
            @if(request('filter_date'))
                pada tanggal <span class="font-medium">{{ \Carbon\Carbon::parse(request('filter_date'))->format('d/m/Y') }}</span>
            @endif
            @if(request('sto'))
                di STO <span class="font-medium">{{ request('sto') }}</span>
            @endif
            <a href="{{ route('update-maintenance') }}" class="text-blue-600 hover:text-blue-800 ml-2">Tampilkan semua</a>
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <div class="max-h-[600px] overflow-y-auto">
            <table class="min-w-full text-sm border border-gray-200">
                <thead class="bg-blue-600 text-white sticky top-0 z-10">
                    <tr>
                        <th class="py-3 px-4 text-left">NO</th>
                        <th class="py-3 px-4 text-left">Site ID</th>
                        <th class="py-3 px-4 text-left">Site Name</th>
                        <th class="py-3 px-4 text-left">STO</th>
                        <th class="py-3 px-4 text-left">Teknisi</th>
                        <th class="py-3 px-4 text-left">Tgl Visit</th>
                        <th class="py-3 px-4 text-left">Progres</th>
                        <th class="py-3 px-4 text-left">Operator</th>
                        <th class="py-3 px-4 text-left">Keterangan</th>
                        @if(session('role') == 'admin' || session('role') == 'master')
                        <th class="py-3 px-4 text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($sites as $index => $site)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4">{{ $index + 1 }}</td>
                            <td class="py-2 px-4">{{ $site->site_code }}</td>
                            <td class="py-2 px-4">{{ $site->site_name }}</td>
                            <td class="py-2 px-4">{{ $site->sto }}</td>
                            <td class="py-2 px-4">{{ $site->teknisi ?? '-' }}</td>
                            <td class="py-2 px-4">
                                {{ $site->tgl_visit ? \Carbon\Carbon::parse($site->tgl_visit)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="py-2 px-4">
                                @if($site->progres == 'Sudah Visit')
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">
                                        {{ $site->progres }}
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-semibold">
                                        {{ $site->progres }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-2 px-4">{{ $site->operator ?? '-' }}</td>
                            <td class="py-2 px-4">{{ $site->keterangan ?? '-' }}</td>
                            @if(session('role') == 'admin' || session('role') == 'master')
                            <td class="py-2 px-4 text-center">
                                <button class="text-blue-600 hover:text-blue-800 edit-btn"
                                    data-id="{{ $site->id }}"
                                    data-site-code="{{ $site->site_code }}"
                                    data-site-name="{{ $site->site_name }}"
                                    data-teknisi="{{ $site->teknisi }}"
                                    data-tgl="{{ $site->tgl_visit ? \Carbon\Carbon::parse($site->tgl_visit)->format('d/m/Y') : '' }}"
                                    data-progres="{{ $site->progres }}"
                                    data-operator="{{ $site->operator }}"
                                    data-ket="{{ $site->keterangan }}"
                                    title="Edit">
                                    <i class="fas fa-pen"></i>
                                </button>
                            </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Script --}}
<script>
    // Elements
    const editModal = document.getElementById('editModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const editBtns = document.querySelectorAll('.edit-btn');

    // Form Fields
    const editForm = document.getElementById('editForm');
    const siteIdField = document.getElementById('editSiteId');
    const siteNameField = document.getElementById('editSiteName');
    const teknisiField = document.getElementById('editTeknisi');
    const tglField = document.getElementById('editTglVisit');
    const progresField = document.getElementById('editProgres');
    const operatorField = document.getElementById('editOperator');
    const ketField = document.getElementById('editKeterangan');

    // Open Modal & Fill Data (only if modal exists)
    if (editForm && editModal && closeModalBtn) {
        editBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Update form action with correct site ID
                editForm.action = editForm.action.replace(/\/\d+$/, '/' + btn.dataset.id);
                
                siteIdField.value = btn.dataset.siteCode;
                siteNameField.value = btn.dataset.siteName;
                teknisiField.value = btn.dataset.teknisi || '';
                // Convert date format from dd/mm/yyyy to yyyy-mm-dd
                if (btn.dataset.tgl) {
                    tglField.value = btn.dataset.tgl.split('/').reverse().join('-');
                } else {
                    tglField.value = '';
                }
                progresField.value = btn.dataset.progres;
                operatorField.value = btn.dataset.operator || '';
                ketField.value = btn.dataset.ket || '';

                editModal.classList.remove('hidden');
                editModal.classList.add('flex');
            });
        });

        // Close Modal
        closeModalBtn.addEventListener('click', () => {
            editModal.classList.add('hidden');
        });

        // Close modal if click outside
        window.addEventListener('click', (e) => {
            if (e.target === editModal) {
                editModal.classList.add('hidden');
            }
        });
    }

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    let searchTimeout;

    searchInput.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchForm.submit();
        }, 500); // Submit after 500ms of no typing
    });

    // Submit on Enter key
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            clearTimeout(searchTimeout);
            searchForm.submit();
        }
    });

    // Auto-hide success alert after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.getElementById('success-alert');
        if (alert) {
            setTimeout(() => {
                alert.style.display = 'none';
            }, 5000);
        }
    });

    // Date filter functionality
    const dateFilterInput = document.getElementById('dateFilterInput');
    const dateFilterForm = document.getElementById('dateFilterForm');
    let dateTimeout;

    dateFilterInput.addEventListener('change', (e) => {
        clearTimeout(dateTimeout);
        dateTimeout = setTimeout(() => {
            dateFilterForm.submit();
        }, 300);
    });

    // Auto-submit STO filter
    const stoFilter = document.getElementById('stoFilter');
    const stoFilterForm = document.getElementById('stoFilterForm');
    stoFilter.addEventListener('change', () => {
        stoFilterForm.submit();
    });

    // Function to clear date filter
    function clearDateFilter() {
        const url = new URL(window.location.href);
        url.searchParams.delete('filter_date');
        if (!url.searchParams.toString()) {
            window.location.href = url.pathname;
        } else {
            window.location.href = url.toString();
        }
    }
</script>
@endsection
