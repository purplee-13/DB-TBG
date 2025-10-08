<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Monitoring Tower</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 via-white to-blue-200 min-h-screen flex items-center justify-center font-sans">

    <div class="bg-white rounded-2xl shadow-2xl flex flex-col md:flex-row w-[900px] max-w-4xl">
        <!-- Kiri: Logo dan Info -->
        <div class="bg-blue-600 text-white rounded-t-2xl md:rounded-l-2xl md:rounded-tr-none flex flex-col justify-center items-center md:w-1/2 p-10">
            <img src="{{ asset('assets/icon/tbg.png') }}" alt="Logo" class="w-32 h-32 mb-4">
            <h2 class="text-3xl font-bold mb-2">Monitoring Tower</h2>
            <p class="text-blue-100 text-center px-6">
                Selamat datang di sistem monitoring tower!  
                Silakan login untuk melanjutkan ke dashboard.
            </p>
        </div>

        <!-- Kanan: Form Login -->
        <div class="flex flex-col justify-center items-center md:w-1/2 p-10">
            <h2 class="text-3xl font-bold text-blue-700 mb-6">Login</h2>

            @if(session('error'))
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 w-full text-center">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login.proses') }}" method="POST" class="w-full max-w-sm space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-gray-700 font-semibold mb-1">Email</label>
                    <input type="email" name="email" id="email" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label for="password" class="block text-gray-700 font-semibold mb-1">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div class="flex items-center justify-between mt-2">
                    <label class="flex items-center text-sm text-gray-600">
                        <input type="checkbox" class="mr-2"> Ingat saya
                    </label>
                    <a href="#" class="text-blue-600 text-sm hover:underline">Lupa password?</a>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg mt-4 hover:bg-blue-700 transition duration-300">
                    Masuk
                </button>
            </form>

            <p class="text-gray-500 text-sm mt-6">
                © 2025 Monitoring Tower. All rights reserved.
            </p>
        </div>
    </div>

</body>
</html>
