<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Tower Bersama Group</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

  <!-- Optional: font & icon -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="min-h-screen bg-cover bg-no-repeat bg-center flex items-center justify-center" style="background-image: url('/assets/bg.png');">

  <div class="bg-white w-[900px] md:w-[1000px] h-[480px] rounded-[2rem] shadow-lg flex overflow-hidden">

    <!-- Left Side -->
    <div class="w-1/2 bg-cover bg-left border-8 border-white rounded-[2rem] relative bg-blend-soft-light" 
         style="background-image: url('/assets/bg2.png'); background-color: rgba(0, 0, 0, 0.5);">
            <div class="absolute bottom-20 left-10 text-white z-10">
                <h1 class="text-2xl font-bold leading-snug">
                Accelerating<br>Connectivity with<br>Sustainable Growth
                </h1>
            </div>
        </div>

    <!-- Right Side -->
    <div class="w-1/2 flex flex-col items-center justify-center px-10 text-gray-700">
      <img src="{{ asset('assets/icon/tbg.png') }}" alt="Logo" class="w-40 mb-5">
      <p class="text-sm text-gray-500 mb-6 text-center">Silahkan login menggunakan Username & Password anda!</p>

      <form class="w-full max-w-sm">
        <div class="mb-4">
          <label for="username" class="block text-sm font-semibold mb-1">Username</label>
          <input id="username" type="text" placeholder="Username"
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <div class="mb-6 relative">
          <label for="password" class="block text-sm font-semibold mb-1">Password</label>
          <input id="password" type="password" placeholder="Password"
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
          <button type="button" class="absolute right-3 top-8 text-gray-400 hover:text-gray-600">
            <span class="material-symbols-outlined">
visibility
</span>
          </button>
        </div>

        <button type="submit" class="w-full py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 shadow-md transition">
          Login
        </button>
      </form>
    </div>
  </div>

</body>
</html>
