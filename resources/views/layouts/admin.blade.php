<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=deviceWidth, initial-scale=1.0">
    <title>Monitoring Tower - Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen font-sans">

    {{-- Include Navbar --}}
    <!-- @include('component.navbar.navbar') -->

    @if(session('role') === 'super admin')
        @include('component.navbar.navbarmaster')
    @elseif(session('role') === 'admin')
        @include('component.navbar.navbar')
    @elseif(session('role') === 'pegawai')
        @include('component.navbar.navbar')
    @endif


    {{-- Konten Utama --}}
    <main class="p-6">
        @yield('content')
    </main>

</body>
</html>
