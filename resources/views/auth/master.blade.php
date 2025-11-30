<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KSS - Document Management System</title>
    <link rel="icon" href="{{ asset('assets/Logo-compressed 1.png') }}">

    <!-- CDN Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    xintegrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
    crossorigin="anonymous">

    <!-- CDN Bootstrap Icons (BARU) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet">

    <script type="module" src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.4/dist/turbo.es2017-esm.js"></script>

    <!-- CSS -->
    <style>
        :root{
            --blue-kss: #0077C2;
            --orange-kss: #F39C12;
            --black-color: #111111;
            --base-white: #F9F9F9;
            --redcolor: #D20000;
        }

        body {
            display: flex;
            font-family: 'Nunito Sans', sans-serif;
            width: 100%;
            height: 100dvh;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: linear-gradient(117deg, #EDFCFF 0.55%, #83A6BD 99.45%);
        }

        .container-login {
            display: flex;
            width: 500px;
            padding: 40px 50px;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            border-radius: 50px;
            background: linear-gradient(180deg, #EFEFEF 0%, #FFF2BF 100%);
            box-shadow: 0 0 50px 0 rgba(0, 0, 0, 0.25);
        }

        .image-login {
           width: 159px;
            height: 60px;
            aspect-ratio: 159/40;
        }

        .box-login {
            gap: 0px;
            text-align: center;
            align-self: stretch;
            font-weight: 400;
            color: var(--black-color);
            font-size: 14px;
        }

        .login-input label {
            font-size: 14px;
            font-weight: 300;
            color: rgba(17, 17, 17, 0.75);
            padding-left: 25px;
        }

        .login-input input, .login-input select {
            display: flex;
            padding: 15px 25px;
            align-items: center;
            gap: 10px;
            align-self: stretch;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.90);
            font-weight: 400;
            font-size: 14px;
            color: #111111;
            border: none;
            box-shadow:  0 0 1px 0 rgba(0, 0, 0);
        }

        /* Wrapper untuk input password (BARU) */
        .password-wrapper {
            position: relative;
            width: 100%;
        }

        /* Sesuaikan input di dalam wrapper (BARU) */
        .password-wrapper input {
            width: 100%;
            /* Tambahkan padding kanan agar teks tidak tertutup ikon */
            padding-right: 60px !important;
        }

        /* Styling ikon mata (BARU) */
        .password-wrapper #togglePassword {
            position: absolute;
            right: 25px; /* Sesuaikan dengan padding input */
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #555; /* Warna ikon */
            font-size: 1.2rem; /* Ukuran ikon */
        }


        .btn-login {
            display: flex;
            padding: 20px 10px;
            justify-content: center;
            align-items: center;
            gap: 10px;
            align-self: stretch;
            border-radius: 50px;
            background: #FFD117;
            font-size: 18px;
            font-weight: 600;
            border: none;
            margin-top: 20px;
        }

        .btn-login:hover {
            background:#ffcc00;
            outline: 2px solid var(--orange-kss);
        }
    </style>
</head>
<body>
    @yield('content')

    @stack('scripts')

    {{-- Letakkan di Layout Utama (Master Blade) bagian bawah --}}
@auth
    <script>
        (function() {
            // 1. Cek apakah ada cookie "Remember Me" (nama default Laravel: remember_web_...)
            const cookies = document.cookie.split(';');
            const hasRememberToken = cookies.some(c => c.trim().startsWith('remember_web_'));

            // 2. Jika user Login TAPI TIDAK punya token "Remember Me"
            if (!hasRememberToken) {

                // Cek apakah session storage 'is_active' ada?
                // sessionStorage HILANG otomatis saat Tab ditutup.
                if (!sessionStorage.getItem('is_session_active')) {

                    // Jika tidak ada, berarti ini tab baru/reopen -> Paksa Logout
                    // Kita kirim form logout secara otomatis

                    // Opsional: Hapus ini jika Anda tidak ingin logout saat "Open in New Tab"
                    // Tapi ini satu-satunya cara mendeteksi "Close Tab"

                    // Buat form logout dummy dan submit
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('logout') }}";

                    var token = document.createElement('input');
                    token.type = 'hidden';
                    token.name = '_token';
                    token.value = "{{ csrf_token() }}";

                    form.appendChild(token);
                    document.body.appendChild(form);
                    form.submit();

                } else {
                    // Jika ada, berarti user sedang browsing aman
                    // Refresh token storage agar tetap hidup
                    sessionStorage.setItem('is_session_active', '1');
                }

                // Set tanda bahwa sesi tab ini aktif
                sessionStorage.setItem('is_session_active', '1');
            }
        })();
    </script>
@endauth
</body>
</html>
