<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'KSS - Integrated Daily Report')</title>
    <!-- Icon Placeholder -->
    <link rel="icon" href="https://placehold.co/32x32/0077C2/FFFFFF?text=KSS">

    <!-- CDN Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Google Fonts (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Custom CSS -->
    <style>
        :root{
            /* Warna Brand */
            --blue-kss: #0077C2;
            --blue-kss-dark: #005f9e;
            --orange-kss: #F39C12;
            --redcolor: #D20000;
            --green: #198754;
            --green-dark: #146c43;
            --white-color: #FDFDFD;

            /* Variabel Tema LIGHT */
            --bg-body: #F5F5F7;
            --bg-navbar: #FFFFFF;
            --bg-card: #FDFDFD;
            --bg-input: #FFFFFF;
            --bg-timesheet: #F0F0F0;
            --bg-timeline-content: #F3F3F3;
            --table-head-bg: #E9ECEF;

            --text-main: #171717;
            --text-muted: #666666;
            --text-inverse: #FFFFFF;

            --border-color: rgba(0, 0, 0, 0.20);
            --shadow-color: rgba(0, 0, 0, 0.1);
            --hover-bg: rgba(243, 157, 18, 0.1);
        }

        /* Variabel Tema DARK */
        [data-theme="dark"] {
            --bg-body: #121212;
            --bg-navbar: #1E1E1E;
            --bg-card: #1E1E1E;
            --bg-input: #2C2C2C;
            --bg-timesheet: #252525;
            --bg-timeline-content: #2C2C2C;
            --table-head-bg: #2C2C2C;

            --text-main: #E0E0E0;
            --text-muted: #A0A0A0;
            --text-inverse: #121212;

            --border-color: rgba(255, 255, 255, 0.15);
            --shadow-color: rgba(0, 0, 0, 0.5);
            --hover-bg: rgba(243, 157, 18, 0.2);
        }

        /* Global CSS */
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: start;
            background-color: var(--bg-body);
            overflow-x: hidden;
            gap: 25px;
            color: var(--text-main);
            transition: background-color 0.3s, color 0.3s;
        }

        .navbar {
            padding: 15px 30px;
            background-color: var(--bg-navbar) !important;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.3s;
        }

        .navbar-left { gap: 25px; }
        /* NEW: Navbar Right Style */
        .navbar-right { gap: 20px; }

        /* NEW: Navbar Divider */
        .navbar-divider {
            width: 1px;
            height: 24px;
            background-color: var(--border-color);
            margin: 0 5px;
        }

        .nama { color: var(--text-main); font-size: 14px; font-weight: 600; }
        .title { color: var(--text-muted); font-size: 10px; font-weight: 300; }

        /* NEW: Button Logout Style */
        .btn-logout {
            color: var(--redcolor);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.2s ease-in-out;
            background-color: transparent;
        }

        .btn-logout:hover {
            background-color: rgba(210, 0, 0, 0.1);
            color: var(--redcolor);
            transform: translateX(3px);
        }

        /* --- TOGGLE SWITCH STYLE --- */
        .theme-switch-wrapper { display: flex; align-items: center; }
        .theme-switch { display: inline-block; height: 34px; position: relative; width: 60px; }
        .theme-switch input { display: none; }
        .slider { background-color: #ccc; bottom: 0; cursor: pointer; left: 0; position: absolute; right: 0; top: 0; transition: .4s; display: flex; align-items: center; justify-content: space-between; padding: 0 8px; }
        .slider:before { background-color: #fff; bottom: 4px; content: ""; height: 26px; left: 4px; position: absolute; transition: .4s; width: 26px; z-index: 2; }
        .slider.round { border-radius: 34px; }
        .slider.round:before { border-radius: 50%; }
        .slider .fa-sun { color: #f39c12; font-size: 14px; z-index: 1; }
        .slider .fa-moon { color: #f1c40f; font-size: 14px; z-index: 1; }
        input:checked + .slider { background-color: var(--bg-input); border: 1px solid var(--border-color); }
        input:checked + .slider:before { transform: translateX(26px); }

        /* CONTENT HEADER STYLE */
        .tab {
            border-radius: 10px;
            outline: 1px solid var(--border-color);
            background: var(--bg-card);
            padding: 10px 12px;
            color: var(--text-main);
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: .2s ease-in-out;
        }

        .tab:hover {
            background-color: var(--hover-bg);
            color: var(--text-main);
            text-decoration: none;
            transform: translateY(-2px);
        }

        .tab.active {
            background-color: var(--orange-kss);
            border: none;
            color: var(--white-color);
            outline: none;
        }

        /* STYLE CONTAINER SECTION (Sembunyikan/Tampilkan) */
        .form-section {
            display: none; /* Default hidden */
            width: 100%;
            flex-direction: column;
            gap: 20px;
        }

        .form-section.active {
            display: flex; /* Show active */
            animation: fadeInSection 0.3s ease-in-out;
        }

        @keyframes fadeInSection {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ACTIVITY CONTENT PANES & ANIMATIONS */
        .activity-pane {
            display: none; width: 100%; flex-direction: column; gap: 20px;
        }
        .activity-pane.active {
            display: flex; animation: slideFadeIn 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        @keyframes slideFadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* BOX FORM STYLES */
        .box-form-shift, .box-info-umum {
            padding: 20px;
            border-radius: 20px;
            background-color: var(--bg-card);
            gap: 10px;
            box-shadow: 0 0 4px 0 var(--shadow-color);
            color: var(--text-main);
        }

        .box-info-umum { gap: 20px; border-radius: 10px; }

        .title-form {
            display: flex;
            padding: 10px 0 20px 0;
            align-items: start;
            gap: 10px;
            align-self: stretch;
            border-bottom: 1px solid var(--border-color);
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-main);
        }

        /* Style Activities Tab */
        .activities-tab{
            display: flex; width: 110px; padding: 10px 15px; justify-content: center; align-items: center; gap: 10px; text-decoration: none; color: var(--text-muted); font-size: 14px; font-weight: 500; transition: all 0.3s ease-in-out; cursor: pointer; border-radius: 15px 15px 0 0; position: relative;
        }
        .activities-tab:hover {
            color: var(--text-main); text-decoration: none; transform: translateY(-2px); font-weight: 600; background-color: var(--hover-bg); border-radius: 10px;
        }
        .activities-tab.active {
            background-color: var(--blue-kss); border-radius: 10px; color: var(--white-color); font-weight: 600; border-radius: 15px 15px 0 0;
        }
        .activities-tab.active:hover { background-color: var(--blue-kss); border-radius: 15px; }

        /* INPUT STYLES - SENIOR FRIENDLY UPDATE */
        .box-input { min-width: 120px; gap: 10px; flex: 1 0 0; color: var(--text-main); font-size: 14px; }
        .box-input label { align-self: stretch; font-weight: 500; color: var(--text-muted); }

        /* Font input diperbesar menjadi 16px */
        .box-input input, .loading-info .input-loading input, .input-item input, .input-laporan-harian .input-laporan, .input-material-info select, .input-deliv-info input, .table input.form-control {
            width: 100%; padding: 12px 20px; border: 1px solid var(--border-color); background-color: var(--bg-input); color: var(--text-main); border-radius: 8px; font-size: 16px; font-weight: 400;
        }

        .input-laporan-harian .input-laporan {
            width: auto !important;
        }

        /* --- ALIGNMENT LABEL & INPUT (INFO UMUM) --- */
        .loading-info { display: flex; min-width: 300px; padding: 5px 10px; flex-direction: column; align-items: flex-start; gap: 12px; flex: 1 0 0; }
        .loading-info .input-loading { display: flex; align-items: center; align-self: stretch; gap: 10px; }
        .loading-info .input-loading label { width: 140px; font-size: 12px; font-weight: 500; color: var(--text-muted); margin-bottom: 0 !important; line-height: 1; padding-top: 2px; }
        .loading-info .input-loading input, .loading-info .input-loading .custom-select-trigger { padding: 10px 15px; border-radius: 6px; font-size: 12px; font-weight: 400; height: 45px; }

        /* --- BULK LOADING STYLE (Muat Urea & Bongkar Header) --- */
        .input-bulk-loading { display: flex; min-width: 300px; align-items: flex-start; align-content: flex-start; gap: 20px; align-self: stretch; flex-wrap: wrap; font-size: 12px; font-weight: 500; }
        .input-item { display: flex; min-width: 200px; flex-direction: column; justify-content: center; align-items: flex-start; gap: 5px; flex: 1 0 0; }

        /* FIX: Override font size khusus untuk input header Bongkar (dan muat urea) menjadi 12px */
        .input-bulk-loading .input-item input { font-size: 12px !important; padding: 10px 12px !important; }

        /* Update Flatpickr Input */
        .flatpickr-input { font-size: 16px !important; font-weight: 500 !important; background-color: var(--bg-input) !important; cursor: pointer; }

        /* --- BONGKAR SECTION STYLES --- */
        .unload-tab {
            display: flex; justify-content: center; align-items: center; flex: 1 0 0; padding: 12px 20px; min-width: 250px; align-self: stretch;
            text-decoration: none; text-transform: uppercase; font-size: 14px; font-weight: 700; color: #fdfdfd; border-radius: 10px; transition: .2s ease-in-out;
            opacity: 0.6;
        }
        .unload-tab:hover { opacity: 0.9; transform: translateY(-2px); cursor: pointer; color: white; }
        .unload-tab.active { opacity: 1; box-shadow: 0 2px 8px var(--shadow-color); }

        .box-material-blue {
            gap: 10px;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid rgba(0, 119, 194, 0.2) !important;
            background-color: rgba(0, 119, 194, 0.05) !important; /* Biru Transparan */
        }

        .input-material-info label { font-size: 10px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); }
        .input-material-info input, .input-material-info select { width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 6px; background-color: var(--bg-input); color: var(--text-main); font-size: 12px; }

        /* NEW: Button Tambah Jenis */
        .btn-add-type {
            background-color: rgba(0, 119, 194, 0.1);
            color: var(--blue-kss);
            border: none;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .btn-add-type:hover {
            background-color: var(--blue-kss);
            color: white;
        }

        /* --- GUDANG TURBA STYLES --- */
        .input-deliv-info label { font-size: 10px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; color: var(--text-muted); }
        .input-deliv-info input { font-size: 12px; padding: 10px 12px; }

        .btn-add-jenis {
            background-color: var(--green); color: white; border: none; padding: 6px 12px; border-radius: 15px; font-size: 12px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: .2s; cursor: pointer;
        }
        .btn-add-jenis:hover { background-color: var(--green-dark); color: white; transform: translateY(-2px); }

        /* --- TABLE STYLES --- */
        .table-responsive { margin-top: 15px; border: 1px solid var(--border-color); border-radius: 10px; overflow: visible; }
        .table { margin-bottom: 0; color: var(--text-main); font-size: 12px; }
        .table th { background-color: var(--blue-kss); color: white; font-weight: 600; text-align: center; vertical-align: middle; border-bottom: none; padding: 10px; font-size: 11px; text-transform: uppercase; }
        .table-bordered th, .table-bordered td { border: 1px solid var(--border-color); }
        .table td { vertical-align: middle; background-color: var(--bg-card); color: var(--text-main); padding: 8px; }
        .table input.form-control, .table select.form-select { background-color: var(--bg-input); color: var(--text-main); border: 1px solid var(--border-color); font-size: 14px; padding: 10px; min-width: 80px; }
        .table textarea.form-control { background-color: var(--bg-input); color: var(--text-main); border: 1px solid var(--border-color); font-size: 13px; padding: 8px 12px; min-height: 40px; resize: vertical; }

        /* Helper for Turba Table */
        .table-turba th { background-color: var(--blue-kss-dark); }
        .btn-delete-row { color: var(--redcolor); cursor: pointer; transition: 0.2s; background: none; border: none; padding: 5px; font-size: 16px; }
        .btn-delete-row:hover { transform: scale(1.2); }

        /* Sub Tabs */
        .nav-pills .nav-link { color: var(--text-main); font-weight: 600; font-size: 14px; margin-right: 10px; border-radius: 8px; padding: 8px 20px; border: 1px solid var(--border-color); cursor: pointer; }
        .nav-pills .nav-link.active { background-color: var(--blue-kss); color: white; border-color: var(--blue-kss); }

        /* Button Set All */
        .btn-set-all { background-color: var(--green); color: white; border: none; font-size: 12px; font-weight: 600; padding: 8px 15px; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; transition: .2s; }
        .btn-set-all:hover { background-color: var(--green-dark); color: white; transform: translateY(-2px); }
        .category-row td { background-color: var(--table-head-bg); font-weight: 700; text-transform: uppercase; }

        /* --- TIMELINE / LAPORAN HARIAN STYLES --- */
        .laporan-harian { display: flex; flex-direction: column; align-items: flex-start; flex: 1 0 0; align-self: stretch; border-radius: 10px; border: 1px solid var(--border-color); background-color: var(--bg-card); }
        .header-laporan-harian { display: flex; padding: 10px 12px; flex-direction: column; align-items: flex-start; gap: 6px; align-self: stretch; border-radius: 10px 10px 0 0; background-color: var(--blue-kss); font-size: 14px; font-weight: 600; color: #FDFDFD; }
        .btn-add-laporan{ cursor: pointer; border: none; width: 35px; height: 35px; border-radius: 50%; transition: 0.2s; display: flex; align-items: center; justify-content: center; background-color: var(--blue-kss); flex-shrink: 0; }
        .btn-add-laporan:hover { background-color: var(--blue-kss-dark); transform: scale(1.05); }

        .list-laporan { width: 100%; padding: 20px; display: flex; flex-direction: column; gap: 15px; }
        .timeline-item { width: 100%; display: flex; flex-direction: column; gap: 6px; animation: fadeIn 0.3s ease-in-out; }
        .timeline-header { display: flex; align-items: center; gap: 10px; padding-left: 5px; }
        .timeline-dot { width: 8px; height: 8px; background-color: var(--blue-kss); border-radius: 50%; flex-shrink: 0; }
        .timeline-time { font-size: 13px; font-weight: 700; color: var(--text-main); }
        .timeline-content { margin-left: 22px; background-color: var(--bg-timeline-content); border-radius: 6px; padding: 10px 15px; display: flex; align-items: center; gap: 15px; font-size: 13px; color: var(--text-main); }
        .timeline-content .label-cob { font-weight: 700; }

        /* --- MODERN CUSTOM SELECT CSS --- */
        select { display: none !important; }
        .custom-select-container { position: relative; width: 100%; font-family: 'Inter', sans-serif; min-width: 100px; }
        .custom-select-trigger { position: relative; display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 12px 20px; font-size: 14px; font-weight: 400; color: var(--text-main); background-color: var(--bg-input); border: 1px solid var(--border-color); border-radius: 8px; cursor: pointer; transition: all 0.3s; user-select: none; height: 100%; min-height: 42px; }
        .custom-select-trigger:after { content: '\f078'; font-family: 'Font Awesome 6 Free'; font-weight: 900; font-size: 12px; color: var(--text-muted); transition: transform 0.3s; margin-left: 8px; }
        .custom-select-container.open .custom-select-trigger { border-color: var(--blue-kss); box-shadow: 0 0 0 3px rgba(0, 119, 194, 0.1); }
        .custom-select-container.open .custom-select-trigger:after { transform: rotate(180deg); color: var(--blue-kss); }
        .custom-select-options { position: absolute; top: calc(100% + 5px); left: 0; right: 0; z-index: 999; background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; box-shadow: 0 4px 12px var(--shadow-color); opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.2s ease; max-height: 250px; overflow-y: auto; }
        .custom-select-container.open .custom-select-options { opacity: 1; visibility: visible; transform: translateY(0); }
        .custom-option { padding: 10px 20px; font-size: 14px; color: var(--text-main); cursor: pointer; transition: background 0.2s; border-bottom: 1px solid transparent; }
        .custom-option.selected { background-color: var(--hover-bg); color: var(--orange-kss); }
        .custom-option:hover { background-color: var(--orange-kss); color: var(--white-color); }

        /* Quantity Count Styles */
        .quantity-count { display: flex; min-width: 300px; padding: 20px; flex-direction: column; align-items: flex-start; gap: 15px; flex: 1 0 0; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-card); }
        .title-icon { display: flex; width: 40px; height: 40px; padding: 10px; justify-content: center; align-items: center; border-radius: 5px; }
        .title-quantity .title { font-size: 14px; font-weight: 600; color: var(--text-main); }
        .minitext { display: flex; padding: 1px 6px; justify-content: center; align-items: center; gap: 10px; border-radius: 10px; background: rgba(0, 119, 194, 0.20); font-size: 10px; font-weight: 300; color: var(--blue-kss); }
        .input-quantity { padding-bottom: 15px; gap: 10px; border-bottom: 1px dashed var(--border-color); }
        .input-qty { display: flex; flex-direction: column; align-items: flex-start; gap: 6px; flex: 1 0 0; }
        .input-qty label { font-size: 10px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); }
        .input-qty input { width: 100%; padding: 12px 10px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-input); color: var(--text-main); text-align: center; font-weight: 700; }

        /* Timesheet Styles */
        .log-box { display: flex; min-width: 300px; flex-direction: column; align-items: center; flex: 1 0 0; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-card); }
        .log-title { border-radius: 10px 10px 0 0; padding: 10px 12px; display: flex; justify-content: space-between; align-items: center; align-self: stretch; }
        .log-title.deliv, .log-title.blue-header { background-color: var(--blue-kss); }
        .log-title.load { background-color: var(--green); }
        .title-log { font-size: 14px; font-weight: 600; color: var(--white-color); }
        .badge-log { font-size: 10px; padding: 4px 8px; border-radius: 4px; background: rgba(255, 255, 255, 0.2); color: white; font-weight: 500; }
        .input-timesheet { display: flex; padding: 15px; align-items: center; gap: 6px; align-self: stretch; border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); background: var(--bg-timesheet); }
        .time-input-wrapper { display: flex; align-items: center; position: relative; width: 100%; font-size: 12px; max-width: 140px; }

        /* Time Input lebih besar */
        .input-timesheet input.time-input { padding: 12px 15px; padding-right: 40px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-input); color: var(--text-main); font-size: 18px; font-weight: 700; width: 100%; text-align: center; height: 50px; }

        /* Tombol Set Now lebih mudah ditekan */
        .btn-set-now { position: absolute; right: 5px; color: var(--blue-kss); cursor: pointer; font-size: 20px; padding: 10px; transition: .2s; background: none; border: none; z-index: 5; }
        .btn-set-now:hover { transform: scale(1.1); color: var(--orange-kss); }

        .input-timesheet input.activity-input { padding: 10px 15px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-input); color: var(--text-main); font-size: 14px; font-weight: 600; flex: 1 0 0; height: 50px; }
        .btn-add { cursor: pointer; border: none; width: 40px; height: 40px; border-radius: 20px; transition: 0.2s; display: flex; align-items: center; justify-content: center; color: var(--white-color); }
        .btn-add.add-delivery { background-color: var(--blue-kss); }
        .btn-add.add-delivery:hover { background-color: var(--blue-kss-dark); transform: scale(1.05); }
        .btn-add.add-loading { background-color: var(--green); }
        .btn-add.add-loading:hover { background-color: var(--green-dark); transform: scale(1.05); }

        .list-timesheet { display: flex; min-height: 150px; padding: 0; flex-direction: column; align-items: flex-start; align-self: stretch; }
        .timesheet-item { display: flex; padding: 12px 15px; align-items: flex-start; gap: 15px; align-self: stretch; border-bottom: 1px solid var(--border-color); transition: 0.2s; animation: fadeIn 0.3s ease-in; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
        .timesheet-item:last-child { border-bottom: none; }
        .ts-time-badge { display: flex; padding: 2px 0; color: var(--blue-kss); font-size: 14px; font-weight: 700; min-width: 50px; }
        .ts-dot { width: 6px; height: 6px; background-color: var(--blue-kss); border-radius: 50%; margin-top: 7px; margin-right: 10px; }
        .ts-content { display: flex; padding: 8px 12px; flex-direction: column; justify-content: center; align-items: flex-start; gap: 10px; flex: 1 0 0; border-radius: 6px; background: var(--bg-timesheet); color: var(--text-main); font-size: 12px; font-weight: 500; }
        .ts-delete { color: var(--text-muted); cursor: pointer; padding: 5px; font-size: 12px; transition: 0.2s; }
        .ts-delete:hover { color: var(--redcolor); }

        /* SECTION PETUGAS (Tally, Driver, Truck) */
        .petugas-section { display: flex; padding: 20px; flex-direction: column; gap: 15px; align-self: stretch; border-top: 1px solid var(--border-color); }
        .petugas-row { display: flex; gap: 15px; width: 100%; }
        .petugas-item { display: flex; flex-direction: column; gap: 5px; flex: 1; }
        .petugas-label { color: var(--text-muted); font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .input-with-icon { position: relative; display: flex; align-items: center; }
        .input-with-icon i { position: absolute; left: 15px; color: var(--text-muted); font-size: 14px; }
        .input-with-icon input { width: 100%; padding: 12px 15px 12px 40px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-input); color: var(--text-main); font-size: 12px; font-weight: 500; }
        .input-with-icon input:focus { outline: 2px solid rgba(0, 119, 194, 0.20); border-color: var(--blue-kss); }

        /* Button Form */
        .btn { display: flex; width: 150px; padding: 15px 0; justify-content: center; align-items: center; gap: 20px; border-radius: 15px; color: var(--white-color); font-size: 14px; font-weight: 600; transition: .2s ease-in-out; }
        .btn:hover { transform: translateY(-3px); cursor: pointer; color: white; font-weight: 700; }
        .btn.cancel { background-color: var(--redcolor); }
        .btn.next { background-color: var(--blue-kss); }
        .btn.previous { background-color: var(--orange-kss); }
        .btn.save { background-color: var(--green); }

        /* --- SENIOR FRIENDLY FLATPICKR STYLING --- */
        .flatpickr-calendar {
            background: var(--bg-card) !important;
            border: 1px solid var(--border-color) !important;
            box-shadow: 0 4px 15px var(--shadow-color) !important;
            font-family: 'Inter', sans-serif !important;
            border-radius: 12px !important;
            padding: 10px !important;

            /* SCALING FOR SENIORS ON DESKTOP */
            font-size: 1.1rem !important;
            width: 340px !important;
        }

        .flatpickr-calendar.hasTime .flatpickr-time {
            height: 60px !important;
            line-height: 60px !important;
            border-top: 1px solid var(--border-color) !important;
        }

        .flatpickr-time input { font-size: 20px !important; font-weight: bold !important; }
        .flatpickr-time .flatpickr-am-pm { font-size: 18px !important; }

        .flatpickr-calendar .flatpickr-month { background: transparent !important; color: var(--text-main) !important; fill: var(--text-main) !important; margin-bottom: 10px !important; height: 50px !important; }
        .flatpickr-calendar .flatpickr-current-month { font-size: 18px !important; font-weight: 600 !important; padding-top: 10px !important; }

        .flatpickr-calendar .flatpickr-day {
            color: var(--text-main) !important;
            border-radius: 8px !important;
            font-weight: 500 !important;
            border: none !important;
            margin-top: 2px !important;
            height: 42px !important;
            line-height: 42px !important;
            font-size: 16px !important;
        }

        .flatpickr-calendar .flatpickr-day:hover { background-color: var(--hover-bg) !important; color: var(--blue-kss) !important; }
        .flatpickr-calendar .flatpickr-day.selected { background: var(--blue-kss) !important; border-color: var(--blue-kss) !important; color: #fff !important; }

        /* UTILITY CLASSES */
        .d-none { display: none !important; }
    </style>
    @stack('styles')
</head>
<body>
    @yield('content')

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    @stack('scripts')

    <script>
    // --- DARK MODE TOGGLE ---
    const toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme) {
        document.documentElement.setAttribute('data-theme', currentTheme);
        if (currentTheme === 'dark') toggleSwitch.checked = true;
    }
    function switchTheme(e) {
        if (e.target.checked) {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
        }
    }
    toggleSwitch.addEventListener('change', switchTheme, false);
    </script>
</body>
</html>
