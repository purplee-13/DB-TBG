<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tower Bersama Group</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: url('{{ asset('assets/bg.png') }}') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
        }

        .login-container {
            display: flex;
            background: white;
            border-radius: 25px;
            overflow: hidden;
            width: 900px;
            height: 520px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        /* Kolom kiri */
        .login-left {
            background: url('{{ asset('assets/icon/tbg.png') }}') no-repeat center center;
            background-size: cover;
            position: relative;
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            border-right: 1px solid rgba(255, 255, 255, 0.2);
        }

        .overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            border-radius: 0 0 0 25px;
        }

        .login-left .text-box {
            position: relative;
            z-index: 2;
            color: white;
            text-align: left;
            padding: 40px;
            max-width: 320px;
        }

        .login-left .text-box h1 {
            font-weight: 600;
            line-height: 1.3;
        }

        /* Kolom kanan */
        .login-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 50px;
        }

        .login-right img {
            width: 180px;
            margin-bottom: 15px;
        }

        .login-right p {
            color: #777;
            font-size: 0.9rem;
            margin-bottom: 25px;
        }

        .form-control {
            border: none;
            background-color: #f1f1f1;
            border-radius: 10px;
            padding: 12px 15px;
            margin-bottom: 15px;
            box-shadow: inset 1px 1px 2px rgba(0,0,0,0.1);
        }

        .btn-login {
            background-color: #0056D2;
            color: white;
            border-radius: 10px;
            padding: 10px;
            font-weight: 500;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .btn-login:hover {
            background-color: #003ea3;
        }
    </style>
</head>
<body>

<div class="login-container">
    <!-- Kiri -->
    <div class="login-left">
        <div class="overlay"></div>
        <div class="text-box">
            <h1>Accelerating<br>Connectivity with<br>Sustainable Growth</h1>
        </div>
    </div>

    <!-- Kanan -->
    <div class="login-right">
        <img src="{{ asset('assets/icon/tbg_logo.png') }}" alt="Tower Bersama Group">
        <p>Silahkan login menggunakan Username & Password anda!</p>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3 w-100">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="mb-3 w-100">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-login">Login</button>
        </form>
    </div>
</div>

</body>
</html>