<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KSS - @yield('title')</title>
    <!-- Placeholder Icon -->
    <link rel="icon" href="{{ asset('assets/Logo-compressed 1.png') }}">

    <!-- CDN Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    xintegrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
    crossorigin="anonymous">

    <!-- Font Awesome (Untuk Icon Sun/Moon di toggle) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    @stack('styles')

    <!-- CSS -->
    <style>
        :root{
            /* Light Theme Defaults */
            --blue-kss: #0077C2;
            --blue-kss-dark: #005fa3;
            --orange-kss: #F39C12;
            --black-color: #171717;
            --redcolor: #D20000;
            --white-color: #FDFDFD;
            --green: #198754;
            --green-dark: #146c43;
            --gray-border: rgba(0, 0, 0, 0.10); /* Border halus Light */
            --input-bg: #FFFFFF;
            --shadow-color: rgba(0, 0, 0, 0.08); /* Shadow soft Light */

            /* Background Colors Unified */
            --navbar-bg: #FFFFFF;
            --body-bg: #FFFFFF;
            --table-head-bg: #F8F9FA;

            /* Tambahan untuk konsistensi UI */
            --hover-menu-bg: rgba(243, 157, 18, 0.10);
            --active-menu-bg: rgba(243, 156, 18, 0.15);
            --text-secondary: rgba(23, 23, 23, 0.6);
            --card-bg: #FFFFFF; /* Background khusus Card */
            --btn-soft-bg: rgba(0, 0, 0, 0.05); /* Background tombol soft */
        }

        /* --- DARK THEME OVERRIDES --- */
        [data-theme="dark"] {
            --blue-kss: #3FA9F5;
            --blue-kss-dark: #66bfff;
            --black-color: #E0E0E0;
            --white-color: #1E1E1E; /* Warna dasar komponen dark */
            --gray-border: rgba(255, 255, 255, 0.15); /* Border putih transparan */
            --input-bg: #2C2C2C;
            --shadow-color: rgba(0, 0, 0, 0.6); /* Shadow lebih gelap */
            --redcolor: #FF6B6B; /* Merah lebih terang agar terlihat di dark mode */

            /* Background Colors Unified Dark */
            --navbar-bg: #121212;
            --body-bg: #121212;
            --table-head-bg: #1F1F1F;

            /* Override tambahan */
            --hover-menu-bg: rgba(243, 157, 18, 0.25);
            --active-menu-bg: rgba(243, 156, 18, 0.35);
            --text-secondary: rgba(224, 224, 224, 0.6);
            --card-bg: #1E1E1E;
            --btn-soft-bg: rgba(255, 255, 255, 0.10); /* Background tombol soft jadi putih transparan */
        }

        /* Global CSS */
        body {
            font-family: 'Inter', sans-serif;
            width: 100%;
            display: flex;
            align-items: start;
            background-color: var(--body-bg);
            color: var(--black-color);
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
            /* Penting: opacity 0 di awal untuk mencegah FOUC (Flash of Unstyled Content) jika diperlukan,
               tapi script di body akan menangani sidebar */
        }

        /* Sidebar Styling */
        .sidebar {
            padding: 20px;
            /* Transisi width tetap ada, tapi kita handle load state via JS agar tidak animasi saat refresh */
            transition: width 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55), background-color 0.3s ease;
            width: 250px;
            height: 100vh;
            position: sticky;
            top: 0;
            flex-shrink: 0;
            background-color: var(--body-bg);
            border-right: 1px solid var(--gray-border);
            z-index: 99;
        }

        /* Disable transition saat halaman baru dimuat agar tidak ada animasi 'kaget' */
        body.preload .sidebar,
        body.preload .menu-text,
        body.preload .logo {
            transition: none !important;
        }

        .menu, .logout-button {
            padding: 10px 12px;
            gap: 20px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            color: var(--black-color);
        }

        /* Icon color in menu adjustments */
        .menu svg path,
        .logout-button svg path {
            fill: var(--black-color);
            transition: fill 0.3s ease;
        }

        /* Active Menu Styling Override */
        #active svg path {
            fill: var(--orange-kss);
        }

        .menu .text-sidebar, .logout-button .logout {
            font-size: 14px;
            font-weight: 500;
            color: var(--black-color);
            text-decoration: none;
            transition: opacity 0.2s ease, visibility 0.2s ease;
            white-space: nowrap;
        }
        .menu:hover {
            background: var(--hover-menu-bg);
            outline: 1px solid #f39d123e;
            border-radius: 8px;
            text-decoration: none;
            color: var(--black-color);
        }

        .logout-button:hover {
            outline: 1px solid #D20000;
            background: rgba(210, 0, 0, 0.05);
            color: var(--black-color);
        }

        #active {
            border-radius: 8px;
            background: var(--active-menu-bg);
        }
        #active .text-sidebar {
            font-weight: 700;
        }

        /* Sidebar Collapsed Logic */
        body.sidebar-collapsed .sidebar {
            width: 86px;
            align-items: center;
        }
        body.sidebar-collapsed .logo img[alt='Kaltim Satria Samudera'],
        body.sidebar-collapsed .menu-text,
        body.sidebar-collapsed .text-sidebar,
        body.sidebar-collapsed .logout {
            display: none;
        }
        body.sidebar-collapsed .logo {
            justify-content: center;
            width: 100%;
        }
        body.sidebar-collapsed .sidebar-menu {
            width: auto;
            align-items: center;
        }
        body.sidebar-collapsed .menu,
        body.sidebar-collapsed .logout-button {
            width: 46px;
            gap: 0;
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
        body.sidebar-collapsed .btn-close-sidebar svg {
            transform: rotate(180deg);
        }

        /* Main Content Styling */
        .main-content {
            background-color: var(--body-bg);
            transition: background-color 0.3s ease;
            flex: 1 0 0;
            z-index: 100;
            position: relative;
            box-shadow: -4px 0 20px var(--shadow-color);
            height: 100vh;
            overflow-y: auto;
        }

        .header {
            padding: 15px 20px;
            border-radius: 0;
            border-bottom: 1px solid var(--gray-border);
            background-color: var(--navbar-bg);
            transition: background-color 0.3s ease;
        }

        .btn-close-sidebar {
            border-radius: 50px;
            padding: 8px 10px;
            box-shadow: 0 0 5px 0 var(--shadow-color);
            border: 1px solid var(--gray-border);
            cursor: pointer;
            background-color: var(--navbar-bg);
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        /* Icon color on toggle button */
        .btn-close-sidebar svg path {
            fill: var(--black-color);
        }

        /* Header User Text */
        .akun-title .nama {
            font-weight: 600;
            color: var(--black-color) !important;
        }
        .akun-title .title {
            font-size: 10px;
            font-weight: 300;
            color: var(--black-color) !important;
        }

        /* --- THEME TOGGLE SWITCH CSS --- */
        .theme-switch-wrapper {
            display: flex;
            align-items: center;
        }
        .theme-switch {
            display: inline-block;
            height: 34px;
            position: relative;
            width: 60px;
        }
        .theme-switch input {
            display: none;
        }
        .slider {
            background-color: #ccc;
            bottom: 0;
            cursor: pointer;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            transition: .4s;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 8px;
        }
        .slider.round {
            border-radius: 34px;
        }
        .slider.round:before {
            background-color: white;
            bottom: 4px;
            content: "";
            height: 26px;
            left: 4px;
            position: absolute;
            transition: .4s;
            width: 26px;
            border-radius: 50%;
            z-index: 2;
        }
        /* Icon Styling inside toggle */
        .slider .fa-sun {
            color: #f39c12;
            font-size: 14px;
            z-index: 1;
        }
        .slider .fa-moon {
            color: #f1c40f;
            font-size: 14px;
            z-index: 1;
        }

        input:checked + .slider {
            background-color: var(--blue-kss);
        }
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        /* --- END THEME SWITCH --- */

        /* Content Styling */
        .title-page {
            font-size: 20px;
            font-weight: 600;
            color: var(--black-color);
        }

        /* ===========================================
            DASHBOARD STYLING (UPDATED FOR DARK MODE)
            ===========================================
        */
        .card {
            display: flex;
            padding: 8px 15px;
            flex-direction: row;
            min-width: 150px;
            justify-content: space-between;
            align-items: center;
            flex: 1 0 0;
            border-radius: 15px;

            /* UPDATED: Menggunakan variable agar berubah saat Dark Mode */
            background-color: var(--white-color); /* Light: Putih, Dark: #1E1E1E */
            border: 1px solid var(--gray-border);
            transition: background-color 0.3s ease, border-color 0.3s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            /* UPDATED: Shadow menggunakan variable */
            box-shadow: 0 0 8px 0 var(--shadow-color);
            cursor: pointer;
            border-color: var(--blue-kss); /* Optional: Memberi border biru halus saat hover */
        }

        .card-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            flex: 1 0 0;
        }

        .card-info .number {
            font-size: 28px;
            font-weight: 500;
            color: var(--black-color);
        }

        .card-info .card-title {
            font-size: 12px;
            font-weight: 300;
            align-self: stretch;
            color: var(--text-secondary); /* Menggunakan text-secondary agar tidak terlalu kontras */
        }

        .card-icon {
            display: flex;
            width: 45px;
            height: 45px;
            padding: 10px;
            justify-content: center;
            align-items: center;
            gap: 10px;
            border-radius: 50px;

            /* UPDATED: Menggunakan gray-border (gelap di light, putih transparan di dark) */
            background: var(--gray-border);
        }

        /* Tambahan: Pastikan SVG di dalam card ikut berubah warnanya */
        .card-icon svg path {
            fill: var(--black-color); /* Default fill */
            transition: fill 0.3s ease;
        }

        /* --- VARIANT WARNA ICON (SESUAI REQUEST GAMBAR) --- */
        /* Cara pakai: <div class="card-icon color-blue"> ... </div> */

        .card-icon.color-blue svg path {
            fill: var(--blue-kss) !important;
        }

        .card-icon.color-orange svg path {
            fill: var(--orange-kss) !important;
        }

        .card-icon.color-red svg path {
            fill: var(--redcolor) !important;
        }

        .card-icon.color-black svg path {
            fill: var(--black-color) !important;
        }

        /* --- END VARIANT WARNA --- */


        .dashboard-notif {
            border-radius: 15px;
            padding: 20px 25px;
            gap: 20px;
            /* UPDATED */
            background-color: var(--white-color);
            border: 1px solid var(--gray-border);
        }

        .notif-item {
            display: flex;
            padding: 15px 20px;
            justify-content: space-between;
            align-items: flex-start;
            align-self: stretch;
            border-radius: 10px;

            /* UPDATED */
            background-color: var(--body-bg); /* Atau var(--white-color) tergantung preferensi layer */
            border: 1px solid var(--gray-border);
            transition: background-color 0.3s ease, box-shadow 0.2s ease;
        }

        .notif-item:hover {
            /* UPDATED */
            box-shadow: 0 0 4px 0 var(--shadow-color);
            cursor: pointer;
            background-color: var(--table-head-bg); /* Menggunakan bg table head yg sedikit berbeda dari body */
        }

        .see-doc {
            font-size: 12px;
            font-weight: 400;
            padding: 6px 15px;
            gap: 7px;

            /* UPDATED: Warna teks mengikuti variabel agar terang di dark mode */
            color: var(--blue-kss);
            text-decoration: none;
            border: none;
            border-radius: 10px;

            /* Background tetap biru transparan, terlihat oke di dark mode */
            background-color: rgba(0, 120, 194, 0.20);
            transition: .2s ease-in-out;
        }

        .see-doc:hover {
            background-color: rgba(0, 120, 194, 0.30);
            outline: 1px solid var(--blue-kss); /* Menggunakan variable */
        }

        .download {
            font-size: 12px;
            font-weight: 400;
            padding: 6px 15px;
            gap: 7px;

            /* UPDATED */
            color: var(--black-color);
            text-decoration: none;
            border: none;
            border-radius: 10px;

            /* UPDATED: Menggunakan variable gray-border agar putih transparan di dark mode */
            background-color: var(--gray-border);
            transition: .2s ease-in-out;
        }

        .download:hover {
            /* Background sedikit lebih tebal/gelap */
            background-color: var(--hover-menu-bg);
            outline: 1px solid var(--text-secondary);
        }
        /* END DASHBOOARD STYLING */


        /* MASTER DATA STYLING */
        .master-tabs {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--gray-border);
            padding-bottom: 10px;
            overflow-x: auto;
        }
        .tab-btn {
            background: none;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        .tab-btn:hover {
            color: var(--blue-kss);
            background-color: rgba(0, 119, 194, 0.1);
        }
        .tab-btn.active {
            background-color: var(--blue-kss);
            color: #FFF;
            font-weight: 600;
        }

        /* BUTTONS */
        .btn-add {
            display: flex;
            align-items: center;
            background-color: var(--blue-kss);
            color: #FFF;
            font-size: 14px;
            font-weight: 600;
            padding: 10px 18px;
            gap: 10px;
            border-radius: 10px;
            border: none;
        }
        .btn-add:hover {
            background-color: var(--blue-kss-dark);
            color: #FFF;
        }

        /* --- CUSTOM SELECT (Reused) --- */
        select { display: none !important; }
        .custom-select-wrapper { position: relative; user-select: none; width: 100%; }
        .custom-select { position: relative; display: flex; flex-direction: column; }
        .custom-select__trigger {
            position: relative; display: flex; align-items: center; justify-content: space-between;
            padding: 0 15px; height: 42px; font-size: 12px; font-weight: 400; color: var(--black-color);
            background: var(--input-bg); border: 1px solid var(--gray-border); border-radius: 8px;
            cursor: pointer; transition: all 0.3s;
        }
        .custom-select__trigger:after {
            content: '\f078'; font-family: 'Font Awesome 6 Free'; font-weight: 900; font-size: 10px;
            color: var(--text-secondary); transition: transform 0.3s;
        }
        .custom-select-wrapper.open .custom-select__trigger { border-color: var(--blue-kss); box-shadow: 0 0 0 3px rgba(0, 119, 194, 0.1); }
        .custom-select-wrapper.open .custom-select__trigger:after { transform: rotate(180deg); color: var(--blue-kss); }
        .custom-options {
            position: absolute; display: block; top: 100%; left: 0; right: 0;
            border: 1px solid var(--gray-border); border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1); background: var(--input-bg);
            transition: all 0.2s; opacity: 0; visibility: hidden; pointer-events: none;
            transform: translateY(-10px); z-index: 100; margin-top: 5px; overflow: hidden;
        }
        .custom-select-wrapper.open .custom-options { opacity: 1; visibility: visible; pointer-events: all; transform: translateY(0); }
        .custom-option { position: relative; display: block; padding: 10px 15px; font-size: 12px; font-weight: 400; color: var(--black-color); cursor: pointer; transition: all 0.2s; }
        .custom-option:hover { cursor: pointer; background-color: var(--orange-kss); color: #fff; font-weight: 500; }
        .custom-option.selected { background-color: rgba(243, 157, 18, 0.1); color: var(--orange-kss); font-weight: 600; }
        .custom-option.selected:hover { background-color: var(--orange-kss); color: #fff; }


        /* TABLE STYLING */
        .document-table {
            border-radius: 10px;
            background-color: var(--white-color);
            border: 1px solid var(--gray-border);
            transition: background-color 0.3s ease, border-color 0.3s ease;
            width: 100%;
        }

        .box-title .title-table {
            font-size: 14px;
            font-weight: 600;
            color: var(--black-color) !important;
        }

        .table {
            color: var(--black-color);
            margin-bottom: 0;
            border-color: var(--gray-border);
            width: 100%;
        }

        .table tr {
            width: 100%;
        }

        .table tr th, .table tr td {
            display: flex;
            padding: 10px 15px;
            align-items: center;
            flex: 1;
            font-size: 12px;
            word-break: break-word;
            border-bottom: 1px solid var(--gray-border);
        }
        .table tr th {
            font-weight: 600;
            background-color: var(--table-head-bg);
            border-bottom: none;
            color: var(--black-color);
        }

        /* Specific column widths */
        .table tr th.number, .table tr td.number {
            max-width: 50px;
            min-width: 20px;
            flex: 0 0 50px;
        }

        .table tr th.medium, .table tr td.medium {
            max-width: 150px;
            flex: 0 0 150px;
        }

        /* Kolom Aksi */
        .table tr td.aksi, .table tr th.aksi {
             max-width: 150px;
             flex: 0 0 150px;
             justify-content: flex-start;
        }

        .table tr td.aksi {
            gap: 4px;
            align-items: center;
        }
        .table tr td.aksi button {
            border: none;
            padding: 6px 10px;
            color: #FFF;
            font-size: 10px;
            text-align: center;
            border-radius: 6px;
        }
        .btn-edit {
            border: 1px solid var(--blue-kss);
            background-color: var(--blue-kss);
        }
        .btn-delete {
            border: 1px solid var(--redcolor) !important;
            background-color: var(--redcolor) !important;
        }
        .table .body td {
            min-height: 54px;
        }

        /* TAB CONTENT VISIBILITY */
        .tab-content {
            display: none;
            width: 100%;
            flex-direction: column;
            gap: 20px;
        }
        .tab-content.active-content {
            display: flex;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- MODAL & FORM STYLING BARU --- */
        .modal-content {
            border-radius: 20px;
            border: 1px solid var(--gray-border);
            background-color: var(--navbar-bg);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .modal-header {
            border-bottom: none;
            padding: 30px 30px 10px 30px;
        }
        .modal-title {
            font-weight: 700;
            font-size: 20px;
            color: var(--black-color);
        }
        .modal-body {
            padding: 10px 30px 30px 30px;
        }

        /* Close Button pada modal (Support Dark Mode) */
        .btn-close {
            filter: none;
            background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3e%3cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat;
        }
        [data-theme="dark"] .btn-close {
            /* Mengubah icon close jadi putih saat dark mode */
            background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23FFF'%3e%3cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat;
        }

        .form-label {
            font-size: 12px;
            color: var(--text-secondary);
            margin-bottom: 6px;
            font-weight: 500;
        }
        .form-control, .form-select {
            background-color: var(--input-bg);
            border: 1px solid var(--gray-border);
            color: var(--black-color);
            border-radius: 8px;
            font-size: 14px;
            padding: 12px 15px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--blue-kss);
            background-color: var(--input-bg);
            color: var(--black-color);
            box-shadow: 0 0 0 3px rgba(0, 119, 194, 0.1);
        }

        /* Tombol Submit di Modal */
        .btn-submit-modal {
            width: 100%;
            display: flex;
            padding: 12px;
            justify-content: center;
            align-items: center;
            border-radius: 50px;
            background: #FFD117;
            color: #111;
            font-size: 14px;
            font-weight: 600;
            border: none;
            margin-top: 10px;
            transition: all 0.3s ease;
        }
        .btn-submit-modal:hover {
            background: #e5bc15;
        }

        /* Tombol Delete Konfirmasi */
        .btn-confirm-delete {
            width: 100%;
            display: flex;
            padding: 12px;
            justify-content: center;
            align-items: center;
            border-radius: 50px;
            background: var(--redcolor);
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            border: none;
            margin-top: 10px;
            transition: all 0.3s ease;
        }
        .btn-confirm-delete:hover {
            background: #b00000;
        }



    </style>
</head>
<!-- Menambahkan class 'preload' untuk mencegah transisi CSS saat loading -->
<body class="preload">

    <!-- Sidebar -->
    @include('admin.layouts.sidebar')

    <!-- Main-Content -->
    <div class="main-content d-flex flex-column align-items-start align-self-stretch" style="gap: 20px;">

        <!-- Header -->
        @include('admin.layouts.header')

        <!-- Content -->
        @yield('content')

    </div>

    @stack('modal')
    <!-- JS Boostrap -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
     xintegrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
     crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Initialize Custom Selects
            const selects = document.querySelectorAll('select');

            selects.forEach(select => {
                // Buat struktur HTML Custom
                const wrapper = document.createElement('div');
                wrapper.classList.add('custom-select-wrapper');

                const customSelect = document.createElement('div');
                customSelect.classList.add('custom-select');

                const trigger = document.createElement('div');
                trigger.classList.add('custom-select__trigger');

                // Ambil teks dari opsi yang terpilih atau placeholder
                const selectedOption = select.options[select.selectedIndex];
                trigger.innerHTML = `<span>${selectedOption ? selectedOption.text : 'Pilih...'}</span>`;

                const optionsDiv = document.createElement('div');
                optionsDiv.classList.add('custom-options');

                // Loop setiap option untuk dibuatkan div pengganti
                for (const option of select.options) {
                    // Skip jika option disabled (seperti placeholder)
                    if (option.disabled) continue;

                    const optionElement = document.createElement('div');
                    optionElement.classList.add('custom-option');
                    optionElement.textContent = option.text;
                    optionElement.setAttribute('data-value', option.value);

                    if (option.selected) {
                        optionElement.classList.add('selected');
                    }

                    // Event saat opsi diklik
                    optionElement.addEventListener('click', function() {
                        // Hapus class selected dari opsi lain di dropdown ini
                        optionsDiv.querySelectorAll('.custom-option').forEach(el => el.classList.remove('selected'));
                        // Tambah class selected ke opsi ini
                        this.classList.add('selected');
                        // Ubah teks trigger
                        trigger.querySelector('span').textContent = this.textContent;
                        // Ubah value select asli
                        select.value = this.getAttribute('data-value');
                        // Trigger change event pada select asli (untuk form submission atau listener lain)
                        select.dispatchEvent(new Event('change'));
                        // Tutup dropdown
                        wrapper.classList.remove('open');
                    });

                    optionsDiv.appendChild(optionElement);
                }

                customSelect.appendChild(trigger);
                customSelect.appendChild(optionsDiv);
                wrapper.appendChild(customSelect);

                // Masukkan wrapper custom setelah select asli
                select.parentNode.insertBefore(wrapper, select.nextSibling);

                // Event klik trigger untuk buka/tutup
                trigger.addEventListener('click', function() {
                    // Tutup dropdown lain jika ada
                    document.querySelectorAll('.custom-select-wrapper').forEach(el => {
                        if (el !== wrapper) el.classList.remove('open');
                    });
                    wrapper.classList.toggle('open');
                });
            });

            // Event klik di luar untuk menutup dropdown
            window.addEventListener('click', function(e) {
                if (!e.target.closest('.custom-select-wrapper')) {
                    document.querySelectorAll('.custom-select-wrapper').forEach(el => el.classList.remove('open'));
                }
            });

            // 2. Sidebar Toggle Logic
            const sidebarToggleBtn = document.querySelector('.btn-close-sidebar');
            if (sidebarToggleBtn) {
                sidebarToggleBtn.addEventListener('click', function() {
                    // Toggle class
                    document.body.classList.toggle('sidebar-collapsed');

                    // Simpan status baru ke localStorage
                    if (document.body.classList.contains('sidebar-collapsed')) {
                        localStorage.setItem('sidebarState', 'collapsed');
                    } else {
                        localStorage.setItem('sidebarState', 'expanded');
                    }
                });
            }

            // 3. Dark Mode Logic
            const toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');
            const currentTheme = localStorage.getItem('theme');

            if (currentTheme) {
                document.documentElement.setAttribute('data-theme', currentTheme);
                if (currentTheme === 'dark') {
                    toggleSwitch.checked = true;
                }
            }

            toggleSwitch.addEventListener('change', function(e) {
                if (e.target.checked) {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.setAttribute('data-theme', 'light');
                    localStorage.setItem('theme', 'light');
                }
            });

            // 4. Status Toggle Logic INSIDE MODAL (Dibiarkan manual di view jika ID spesifik, tapi di sini aman)
            const editStatusToggle = document.getElementById('editStatusToggle');
            const editStatusLabel = document.getElementById('editStatusLabel');

            if(editStatusToggle && editStatusLabel) {
                editStatusToggle.addEventListener('change', function() {
                    if (this.checked) {
                        editStatusLabel.textContent = 'Aktif';
                        editStatusLabel.style.color = '#198754';
                    } else {
                        editStatusLabel.textContent = 'Nonaktif';
                        editStatusLabel.style.color = 'var(--text-secondary)';
                    }
                });
            }

            // 5. GLOBAL PASSWORD TOGGLE LOGIC (GENERIC) - PERBAIKAN DI SINI
            // Menggunakan Event Delegation: Berfungsi untuk SEMUA tombol dengan class .btn-toggle-password
            // di halaman manapun (Add Modal, Edit Modal, dll) tanpa konflik ID.
            document.addEventListener('click', function(e) {
                const target = e.target.closest('.btn-toggle-password');
                if (target) {
                    // Cegah default action (misal submit form)
                    e.preventDefault();

                    // Cari input group parent
                    const inputGroup = target.closest('.input-group');
                    if (inputGroup) {
                        // Cari input di sebelahnya
                        const input = inputGroup.querySelector('input');
                        if (input) {
                            // Toggle type password <-> text
                            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                            input.setAttribute('type', type);

                            // Toggle Icon
                            const icon = target.querySelector('i');
                            if (icon) {
                                icon.classList.toggle('fa-eye');
                                icon.classList.toggle('fa-eye-slash');
                            }
                        }
                    }
                }
            });
        });

    </script>

    @stack('scripts')
</body>
</html>
