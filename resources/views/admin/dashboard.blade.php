@extends('admin.layouts.master')

@section('title', 'Dashboard Operasional')

@push('styles')
<style>
    /* --- CUSTOM BUTTON STYLES --- */
    .btn-action-group {
        display: flex;
        align-items: center;
        gap: 12px;
        justify-content: flex-end;
        width: 100%;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid var(--gray-border, #e2e8f0);
    }

    /* 1. Tombol Review (Adaptive) */
    .btn-review-custom {
        background-color: var(--input-bg, #f8f9fa); /* Adaptive BG with fallback */
        color: var(--text-secondary, #64748b); /* Adaptive Text with fallback */
        border: 1px solid var(--gray-border, #e2e8f0);
        padding: 9px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer; /* Tambahkan cursor pointer */
    }

    .btn-review-custom:hover {
        background-color: var(--hover-menu-bg, #ffffff);
        color: var(--blue-kss, #0077C2);
        border-color: var(--blue-kss, #0077C2);
        box-shadow: 0 4px 6px -1px var(--shadow-color, rgba(0, 0, 0, 0.05));
        transform: translateY(-2px);
    }

    .btn-review-custom i {
        color: var(--text-secondary, #64748b);
        transition: color 0.2s;
    }
    .btn-review-custom:hover i {
        color: var(--blue-kss, #0077C2);
    }

    /* 2. Tombol Tanda Tangan (Gradient Green) */
    .btn-sign-custom {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 9px 24px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.25);
    }

    .btn-sign-custom:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);
        transform: translateY(-2px);
        color: white;
    }

    /* --- CARD STYLING --- */
    .notif-item {
        background-color: var(--white-color, #ffffff);
        border: 1px solid var(--gray-border, #f1f5f9);
        color: var(--black-color, #1e293b);
        padding: 24px;
        border-radius: 16px;
        width: 100%;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.3s;
    }

    .notif-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px var(--shadow-color, rgba(0,0,0,0.08)) !important;
        border-color: var(--blue-kss, #0077C2);
    }

    /* Badge & Text Utilities */
    .badge-shift {
        font-size: 10px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .badge-shift.pagi { background-color: #d1fae5; color: #065f46; }
    .badge-shift.sore { background-color: #ffedd5; color: #9a3412; }
    .badge-shift.malam { background-color: #e0f2fe; color: #075985; }

    /* Override Badge Shift Dark Mode agar tidak terlalu silau */
    [data-theme="dark"] .badge-shift.pagi { background-color: #064e3b; color: #a7f3d0; }
    [data-theme="dark"] .badge-shift.sore { background-color: #7c2d12; color: #fdba74; }
    [data-theme="dark"] .badge-shift.malam { background-color: #0c4a6e; color: #bae6fd; }

    /* --- TOAST NOTIFICATION STYLE --- */
    .toast-container-fixed {
        position: fixed;
        top: 30px;
        right: 30px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 15px;
        pointer-events: none;
    }

    .toast-card {
        background-color: var(--white-color, #ffffff);
        color: var(--black-color, #1e293b);
        border-radius: 12px;
        padding: 16px 20px;
        min-width: 320px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: flex-start;
        gap: 15px;
        border-left: 5px solid;
        pointer-events: auto;
        animation: slideInRight 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), fadeOut 0.5s ease 4.5s forwards;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--gray-border, #f1f5f9);
    }

    .toast-card.success { border-left-color: #10b981; }
    .toast-card.success .icon-box { background-color: rgba(16, 185, 129, 0.1); color: #10b981; }

    .toast-card.error { border-left-color: #ef4444; }
    .toast-card.error .icon-box { background-color: rgba(239, 68, 68, 0.1); color: #ef4444; }

    .toast-card .icon-box {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .toast-content {
        display: flex;
        flex-direction: column;
        gap: 4px;
        flex: 1;
    }

    .toast-title { font-size: 14px; font-weight: 700; color: var(--black-color, #1e293b); }
    .toast-message { font-size: 13px; color: var(--text-secondary, #64748b); line-height: 1.4; }

    .btn-close-toast {
        background: none;
        border: none;
        color: var(--text-secondary, #94a3b8);
        cursor: pointer;
        padding: 0;
        font-size: 14px;
        transition: color 0.2s;
    }
    .btn-close-toast:hover { color: var(--black-color, #1e293b); }

    .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        width: 100%;
        background-color: var(--gray-border, #f1f5f9);
    }

    .toast-progress-bar {
        height: 100%;
        width: 100%;
        animation: progress 4.5s linear forwards;
        transform-origin: left;
    }

    .toast-card.success .toast-progress-bar { background-color: #10b981; }
    .toast-card.error .toast-progress-bar { background-color: #ef4444; }

    /* --- MODAL APPROVAL CUSTOM STYLE --- */
    .modal-confirm .modal-content {
        border-radius: 20px;
        border: 1px solid var(--gray-border, #f1f5f9);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        overflow: hidden;
        background-color: var(--white-color, #ffffff);
    }
    .modal-confirm .modal-header {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border-bottom: none;
        padding: 25px 25px 10px;
        justify-content: center;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    [data-theme="dark"] .modal-confirm .modal-header {
        background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
        border-bottom: 1px solid var(--gray-border);
    }

    .modal-confirm .icon-box {
        width: 80px;
        height: 80px;
        margin: 0 auto 15px;
        border-radius: 50%;
        background-color: var(--white-color, #ffffff);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 20px rgba(22, 163, 74, 0.15);
        animation: pulse-green 2s infinite;
    }

    [data-theme="dark"] .modal-confirm .icon-box {
        background-color: #064e3b;
        box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    }

    .modal-confirm .icon-box i {
        font-size: 40px;
        color: #16a34a;
    }
    [data-theme="dark"] .modal-confirm .icon-box i {
        color: #4ade80;
    }

    .modal-confirm .modal-title {
        color: #166534;
        font-weight: 700;
        font-size: 20px;
    }
    [data-theme="dark"] .modal-confirm .modal-title {
        color: #a7f3d0;
    }

    .modal-confirm .modal-body {
        padding: 20px 30px;
        text-align: center;
        background-color: var(--white-color, #ffffff);
        color: var(--black-color, #1e293b);
    }
    .doc-preview-box {
        background-color: var(--input-bg, #f8fafc);
        border: 1px dashed var(--gray-border, #cbd5e1);
        border-radius: 12px;
        padding: 15px;
        margin: 15px 0;
        text-align: left;
    }
    .doc-preview-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 13px;
    }
    .doc-preview-label { color: var(--text-secondary, #64748b); }
    .doc-preview-value { font-weight: 600; color: var(--black-color, #334155); }

    .modal-confirm .modal-footer {
        border: none;
        padding: 0 30px 30px;
        justify-content: center;
        gap: 10px;
        background-color: var(--white-color, #ffffff);
    }
    .btn-modal-cancel {
        background-color: var(--white-color, #ffffff);
        border: 1px solid var(--gray-border, #cbd5e1);
        color: var(--text-secondary, #64748b);
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        width: 45%;
        transition: all 0.2s;
    }
    .btn-modal-cancel:hover {
        background-color: var(--input-bg, #f1f5f9);
        color: var(--black-color, #1e293b);
    }

    .btn-modal-approve {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        border: none;
        color: #fff;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        width: 55%;
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
        transition: all 0.3s ease;
    }

    /* Style saat tombol disabled/loading */
    .btn-modal-approve:disabled {
        background: #94a3b8; /* Warna abu-abu saat disabled */
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }

    .empty-state {
        background-color: var(--input-bg, #f8f9fa) !important;
        border: 2px dashed var(--gray-border, #e2e8f0) !important;
    }
    .empty-state h5 { color: var(--black-color, #334155) !important; }
    .empty-state p { color: var(--text-secondary, #64748b) !important; }
    .empty-state .icon-wrapper {
        background-color: rgba(22, 163, 74, 0.1) !important;
    }

    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes fadeOut {
        to { transform: translateX(10px); opacity: 0; }
    }
    @keyframes progress {
        from { transform: scaleX(1); }
        to { transform: scaleX(0); }
    }
    @keyframes pulse-green {
        0% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.4); }
        70% { box-shadow: 0 0 0 15px rgba(22, 163, 74, 0); }
        100% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
    }

    /* ========================================= */
    /* ===  ANIMASI SIGNATURE SUCCESS OVERLAY === */
    /* ========================================= */
    #success-animation-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.85); /* Slightly darker */
        backdrop-filter: blur(8px);
        z-index: 99999;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.4s ease;
    }

    #success-animation-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .success-anim-container {
        position: relative;
        text-align: center;
        transform: scale(0.8);
        transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1); /* Bounce effect */
    }
    #success-animation-overlay.active .success-anim-container {
        transform: scale(1);
    }

    /* Document Icon CSS Shape */
    .doc-anim-icon {
        width: 140px;
        height: 190px;
        background-color: #fff;
        border-radius: 12px;
        position: relative;
        margin: 0 auto 30px;
        box-shadow: 0 15px 50px rgba(0,0,0,0.4);
        display: flex;
        flex-direction: column;
        padding: 25px;
        gap: 15px;
        overflow: hidden;
        transition: box-shadow 0.3s ease;
    }

    /* Glow effect on document when stamped */
    .doc-anim-icon.glowing {
        box-shadow: 0 0 30px rgba(22, 163, 74, 0.6), 0 20px 50px rgba(0,0,0,0.4);
        border: 2px solid rgba(22, 163, 74, 0.3);
    }

    /* Lines inside document */
    .doc-line { height: 5px; background: #e2e8f0; border-radius: 3px; width: 100%; }
    .doc-line.short { width: 60%; }
    .doc-line.header { height: 10px; width: 40%; background: #94a3b8; margin-bottom: 10px; }

    /* Signature SVG */
    .signature-svg {
        position: absolute;
        bottom: 40px;
        left: 50%;
        transform: translateX(-50%);
        width: 130px;
        height: 90px;
        z-index: 10;
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.2));
    }

    .signature-path {
        fill: none;
        stroke: #16a34a; /* Green Signature */
        stroke-width: 5;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-dasharray: 500; /* Panjang garis estimasi */
        stroke-dashoffset: 500; /* Mulai dalam keadaan tersembunyi */
    }

    /* Animasi Drawing Signature */
    .signature-svg.animate .signature-path {
        animation: signDoc 1.2s ease-in-out forwards 0.2s;
    }

    @keyframes signDoc {
        to { stroke-dashoffset: 0; }
    }

    /* --- STAMP EFFECT --- */
    .stamp-box {
        position: absolute;
        top: 50%;
        left: 50%;
        /* Start state: Big and invisible */
        transform: translate(-50%, -50%) rotate(-15deg) scale(3);
        border: 4px solid #16a34a; /* Green Border */
        color: #16a34a;
        padding: 8px 15px;
        font-weight: 800;
        font-size: 24px;
        text-transform: uppercase;
        letter-spacing: 2px;
        opacity: 0;
        border-radius: 8px;
        z-index: 20;
        pointer-events: none;
        background-color: rgba(255, 255, 255, 0.8); /* Semi transparent bg */
        box-shadow: 0 4px 10px rgba(22, 163, 74, 0.2);
    }

    .stamp-box.stamped {
        animation: stamp-slam 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    @keyframes stamp-slam {
        0% { opacity: 0; transform: translate(-50%, -50%) rotate(-15deg) scale(3); }
        100% { opacity: 1; transform: translate(-50%, -50%) rotate(-15deg) scale(1); }
    }

    /* --- CONFETTI / PARTICLES --- */
    .confetti-container {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        pointer-events: none;
        overflow: hidden;
        z-index: 15;
    }
    .particle {
        position: absolute;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        opacity: 0;
    }
    /* Keyframes for particles defined in JS mostly, but simple float here */
    @keyframes floatUp {
        0% { transform: translateY(0) scale(1); opacity: 1; }
        100% { transform: translateY(-100px) scale(0); opacity: 0; }
    }

    .success-text-anim {
        color: white;
        font-size: 26px;
        font-weight: 700;
        margin-top: 25px;
        opacity: 0;
        transform: translateY(20px);
        text-shadow: 0 4px 10px rgba(0,0,0,0.5);
    }
    #success-animation-overlay.active .success-text-anim {
        animation: fadeInUp 0.5s ease forwards 1.2s;
    }

    @keyframes fadeInUp {
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
    <div class="content-page d-flex flex-column align-items-center justify-content-center align-self-stretch" style="padding: 0px 25px 25px 25px; gap: 10px;">

        <h1 class="title-page align-self-stretch">Dashboard Dokumen Masuk</h1>

        <!-- ALERT SUCCESS/ERROR (TOAST) -->
        @if(session('success'))
            <div class="toast-container-fixed">
                <div class="toast-card success">
                    <div class="icon-box"><i class="fas fa-check"></i></div>
                    <div class="toast-content">
                        <span class="toast-title">Berhasil!</span>
                        <span class="toast-message">{{ session('success') }}</span>
                    </div>
                    <button class="btn-close-toast" onclick="this.parentElement.parentElement.remove()"><i class="fas fa-times"></i></button>
                    <div class="toast-progress"><div class="toast-progress-bar"></div></div>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="toast-container-fixed">
                <div class="toast-card error">
                    <div class="icon-box"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="toast-content">
                        <span class="toast-title">Gagal!</span>
                        <span class="toast-message">{{ session('error') }}</span>
                    </div>
                    <button class="btn-close-toast" onclick="this.parentElement.parentElement.remove()"><i class="fas fa-times"></i></button>
                    <div class="toast-progress"><div class="toast-progress-bar"></div></div>
                </div>
            </div>
        @endif

        <div class="dashboard d-flex flex-column align-items-start align-self-stretch" style="gap: 30px;">

            <!-- BAGIAN KARTU STATISTIK -->
            <div class="dashboard-card d-flex align-items-center align-self-stretch align-content-center flex-wrap" style="gap: 20px;">
                <!-- Kartu 1: Total Dokumen -->
                <div class="card">
                    <div class="card-info">
                        <span class="number">{{ $totalDocs }}</span>
                        <span class="card-title">Total Dokumen</span>
                    </div>
                    <div class="card-icon color-blue">
                        <i class="fas fa-file-alt" style="font-size: 18px; color: #0077C2;"></i>
                    </div>
                </div>

                <!-- Kartu 2: Dokumen Hari Ini -->
                <div class="card">
                    <div class="card-info">
                        <span class="number">{{ $todayDocs }}</span>
                        <span class="card-title">Dokumen Hari Ini</span>
                    </div>
                    <div class="card-icon color-green">
                        <i class="fas fa-calendar-day" style="font-size: 18px; color: #198754;"></i>
                    </div>
                </div>

                <!-- Kartu 3: Dokumen Bulan Ini -->
                <div class="card">
                    <div class="card-info">
                        <span class="number">{{ $monthlyDocs }}</span>
                        <span class="card-title">Dokumen Bulan Ini</span>
                    </div>
                    <div class="card-icon color-red">
                        <i class="fas fa-calendar-alt" style="font-size: 18px; color: #D20000;"></i>
                    </div>
                </div>

                <!-- Kartu 4: Petugas Lapangan -->
                <div class="card">
                    <div class="card-info">
                        <span class="number">{{ $totalPetugas }}</span>
                        <span class="card-title">Petugas Lapangan</span>
                    </div>
                    <div class="card-icon color-orange">
                        <i class="fas fa-users" style="font-size: 18px; color: #F39C12;"></i>
                    </div>
                </div>
            </div>

            <!-- BAGIAN NOTIFIKASI / MENUNGGU TANDA TANGAN -->
            <div class="dashboard-notif d-flex flex-column align-items-start align-self-stretch">

                <div class="d-flex justify-content-between align-items-center w-100 mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 4px; height: 24px; background-color: #F39C12; border-radius: 2px;"></div>
                        <span class="notif-title" style="font-size: 18px; font-weight: 700; color: var(--black-color, #1e293b);">
                            Menunggu Persetujuan
                        </span>
                        <span class="badge bg-warning text-dark rounded-pill">{{ $recentReports->count() }}</span>
                    </div>
                </div>

                <p style="font-size: 13px; color: var(--text-secondary, #64748b); margin-bottom: 5px; margin-left: 12px;">
                    Dokumen berikut telah diserahkan oleh petugas dan menunggu tanda tangan digital Anda untuk diarsipkan.
                </p>

                <div class="notif-list d-flex flex-column align-items-start align-self-stretch" style="gap: 20px;">

                    @forelse($recentReports as $report)
                        <!-- CARD NOTIFIKASI ITEM -->
                        <div class="notif-item">
                            <div class="detail-notif d-flex flex-column align-items-start" style="gap: 10px; width: 100%;">

                                <!-- Header Card: Group Info & Time -->
                                <div class="d-flex justify-content-between align-items-start w-100 flex-wrap gap-2">
                                    <div class="info-notif d-flex flex-column align-items-start" style="gap: 6px;">

                                        <!-- Baris Judul & Shift -->
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="nama fw-bold" style="font-size: 16px; color: var(--black-color, #1e293b);">Group {{ $report->group_name }}</span>
                                            @php
                                                $shiftClass = match(strtolower($report->shift)) {
                                                    'pagi' => 'pagi', 'sore' => 'sore', 'malam' => 'malam', default => 'pagi'
                                                };
                                            @endphp
                                            <span class="badge-shift {{ $shiftClass }}">Shift {{ $report->shift }}</span>
                                        </div>

                                        <!-- Detail Laporan -->
                                        <div class="d-flex align-items-center gap-2" style="font-size: 13px; color: var(--text-secondary, #64748b);">
                                            <i class="fas fa-file-lines text-primary"></i>
                                            <span class="fw-medium" style="color: var(--black-color, #1e293b);">Laporan Operasional #{{ $report->id }}</span>
                                            <span style="color: var(--gray-border, #e2e8f0);">|</span>
                                            <span>{{ \Carbon\Carbon::parse($report->report_date)->translatedFormat('l, d F Y') }}</span>
                                        </div>

                                        <!-- Info User Pembuat -->
                                        <div class="d-flex align-items-center gap-2 mt-1" style="font-size: 12px; color: var(--text-secondary, #64748b);">
                                            <i class="fas fa-user-circle"></i>
                                            <span>Dibuat oleh: <strong>{{ $report->creator->name ?? 'Unknown' }}</strong></span>
                                        </div>
                                    </div>

                                    <!-- Waktu Penyerahan -->
                                    <div class="text-end">
                                        <div style="background-color: var(--input-bg, #f8fafc); padding: 6px 12px; border-radius: 8px; border: 1px solid var(--gray-border, #e2e8f0);">
                                            <span class="fw-bold" style="font-size: 12px; color: var(--text-secondary, #64748b); display: block;">Diserahkan</span>
                                            <span style="font-size: 12px; color: var(--text-secondary, #64748b);">
                                                <i class="far fa-clock me-1"></i> {{ \Carbon\Carbon::parse($report->received_at)->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- CUSTOM ACTION BUTTONS -->
                                <div class="btn-action-group">

                                    <!-- 1. Tombol Review & Print -->
                                    <!-- Menambahkan onclick untuk memanggil fungsi printPdf() -->
                                    <a href="{{ route('admin.report.show', $report->id) }}"
                                       target="_blank"
                                       class="btn-review-custom"
                                       title="Lihat & Cetak Dokumen">
                                        <i class="fas fa-print"></i> Pratinjau Laporan
                                    </a>

                                    <!-- 2. Tombol Tanda Tangan (Trigger Modal) -->
                                    <button type="button"
                                            class="btn-sign-custom trigger-approve-modal"
                                            data-id="{{ $report->id }}"
                                            data-group="{{ $report->group_name }}"
                                            data-shift="{{ $report->shift }}"
                                            data-date="{{ \Carbon\Carbon::parse($report->report_date)->translatedFormat('d F Y') }}"
                                            data-url="{{ route('admin.report.approve', $report->id) }}">
                                        <i class="fas fa-file-signature"></i> Setujui & Tanda Tangan
                                    </button>

                                </div>

                            </div>
                        </div>
                    @empty
                        <!-- Empty State -->
                        <div class="empty-state text-center w-100 py-5" style="border-radius: 16px;">
                            <div class="icon-wrapper" style="width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto;">
                                <i class="fas fa-clipboard-check" style="font-size: 32px; color: #16a34a;"></i>
                            </div>
                            <h5 style="font-weight: 700; margin-bottom: 8px;">Semua Bersih!</h5>
                            <p style="font-size: 14px; max-width: 300px; margin: 0 auto;">
                                Tidak ada dokumen yang menunggu persetujuan Anda saat ini. Kerja bagus!
                            </p>
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>

    <!-- HTML UNTUK ANIMASI OVERLAY (HIDDEN DEFAULT) -->
    <div id="success-animation-overlay">
        <div class="success-anim-container">
            <!-- Confetti Container -->
            <div class="confetti-container"></div>

            <div class="doc-anim-icon">
                <div class="doc-line header"></div>
                <div class="doc-line"></div>
                <div class="doc-line"></div>
                <div class="doc-line short"></div>
                <div class="doc-line"></div>

                <!-- STAMP ELEMENT -->
                <div class="stamp-box">SIGNED</div>
            </div>

            <!-- SVG Signature Path -->
            <svg class="signature-svg" viewBox="0 0 150 80">
                <!-- Jalur SVG simulasi tanda tangan -->
                <path class="signature-path" d="M10,50 C30,40 40,60 50,45 C60,30 55,60 70,50 C80,40 90,60 100,40 C110,20 120,70 140,40" />
            </svg>

            <div class="success-text-anim">Laporan Berhasil Ditandatangani!</div>
        </div>
    </div>

@endsection

@push('modal')

    <!-- MODAL POP UP APPROVAL -->
    <div class="modal fade modal-confirm" id="approveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="icon-box">
                        <i class="fas fa-signature"></i>
                    </div>
                    <h5 class="modal-title">Konfirmasi Persetujuan</h5>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">
                        Apakah Anda yakin ingin menyetujui dan menandatangani dokumen ini secara digital?
                        <br><small class="text-danger">*Tindakan ini akan mengarsipkan dokumen dan tidak dapat dibatalkan.</small>
                    </p>

                    <div class="doc-preview-box">
                        <div class="doc-preview-item">
                            <span class="doc-preview-label">ID Laporan:</span>
                            <span class="doc-preview-value" id="modal-doc-id">-</span>
                        </div>
                        <div class="doc-preview-item">
                            <span class="doc-preview-label">Regu / Group:</span>
                            <span class="doc-preview-value" id="modal-doc-group">-</span>
                        </div>
                        <div class="doc-preview-item">
                            <span class="doc-preview-label">Shift:</span>
                            <span class="doc-preview-value" id="modal-doc-shift">-</span>
                        </div>
                        <div class="doc-preview-item">
                            <span class="doc-preview-label">Tanggal Laporan:</span>
                            <span class="doc-preview-value" id="modal-doc-date">-</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Batal</button>

                    <form id="approveForm" method="POST" style="margin: 0; width: 55%;">
                        @csrf
                        <button type="submit" class="btn btn-modal-approve w-100">
                            <i class="fas fa-pen-nib me-2"></i>Ya, Tanda Tangani
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
<script>

    document.addEventListener('DOMContentLoaded', function() {
        const triggers = document.querySelectorAll('.trigger-approve-modal');
        const modalEl = document.getElementById('approveModal');
        const overlay = document.getElementById('success-animation-overlay');
        const signatureSvg = document.querySelector('.signature-svg');
        const stampElement = document.querySelector('.stamp-box');
        const docIcon = document.querySelector('.doc-anim-icon');
        const confettiContainer = document.querySelector('.confetti-container');

        // --- Helper Function: Buat Confetti ---
        function createConfetti() {
            const colors = ['#f43f5e', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6'];

            // Buat 30 partikel
            for(let i=0; i<30; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');

                // Random properties
                const bgColor = colors[Math.floor(Math.random() * colors.length)];
                const left = Math.random() * 100 + '%';
                const animDuration = Math.random() * 1 + 0.5 + 's'; // 0.5s - 1.5s
                const animDelay = Math.random() * 0.2 + 's';

                particle.style.background = bgColor;
                particle.style.left = left;
                particle.style.top = '60%'; // Start from center-ish
                particle.style.animation = `floatUp ${animDuration} ease-out ${animDelay} forwards`;

                confettiContainer.appendChild(particle);
            }
        }

        if(modalEl) {
            const approveModal = new bootstrap.Modal(modalEl);
            const form = document.getElementById('approveForm');
            const idEl = document.getElementById('modal-doc-id');
            const groupEl = document.getElementById('modal-doc-group');
            const shiftEl = document.getElementById('modal-doc-shift');
            const dateEl = document.getElementById('modal-doc-date');

            // --- FUNGSI UPDATE DATA MODAL ---
            triggers.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const group = this.getAttribute('data-group');
                    const shift = this.getAttribute('data-shift');
                    const date = this.getAttribute('data-date');
                    const url = this.getAttribute('data-url');

                    idEl.textContent = '#' + id;
                    groupEl.textContent = 'Group ' + group;
                    shiftEl.textContent = shift;
                    dateEl.textContent = date;
                    form.action = url; // Set action url untuk AJAX fetch

                    approveModal.show();
                });
            });

            // --- ANIMASI TOMBOL LOADING & AJAX SUBMIT ---
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Mencegah reload halaman langsung

                const btn = this.querySelector('button[type="submit"]');
                const cancelBtn = document.querySelector('.btn-modal-cancel');
                const originalText = btn.innerHTML;
                const actionUrl = this.action;
                const formData = new FormData(this);

                // 1. UI Loading State
                btn.innerHTML = '<i class="fas fa-circle-notch fa-spin me-2"></i> Memproses...';
                btn.style.opacity = '0.8';
                btn.style.cursor = 'wait';
                btn.disabled = true;
                if(cancelBtn) cancelBtn.disabled = true;

                // 2. Kirim Data via AJAX (Fetch)
                fetch(actionUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        // JIKA SUKSES:
                        // A. Tutup Modal Approval
                        approveModal.hide();

                        // B. Munculkan Overlay & Reset State Animasi
                        overlay.classList.add('active');
                        stampElement.classList.remove('stamped');
                        signatureSvg.classList.remove('animate');
                        docIcon.classList.remove('glowing');
                        confettiContainer.innerHTML = ''; // Clear old confetti

                        // C. SEQUENCE ANIMATION

                        // 1. Mulai Tanda Tangan (Delay 100ms)
                        setTimeout(() => {
                            signatureSvg.classList.add('animate');
                        }, 100);

                        // 2. Stempel "SIGNED" & Confetti (Delay 1.2s - setelah tanda tangan selesai)
                        setTimeout(() => {
                            stampElement.classList.add('stamped');
                            docIcon.classList.add('glowing');
                            createConfetti(); // Trigger partikel
                        }, 1200);

                        // D. Reload halaman (Delay Total 3.5s)
                        setTimeout(() => {
                            // Fade out overlay manual (opsional, krn reload)
                            overlay.classList.remove('active');

                            // Reload halaman
                            window.location.reload();
                        }, 3500);

                    } else {
                        // Jika Gagal (Server Error)
                        alert('Terjadi kesalahan saat memproses dokumen. Silakan coba lagi.');
                        // Reset tombol
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                        if(cancelBtn) cancelBtn.disabled = false;
                        btn.style.opacity = '1';
                        btn.style.cursor = 'pointer';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal menghubungi server.');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    if(cancelBtn) cancelBtn.disabled = false;
                    btn.style.opacity = '1';
                    btn.style.cursor = 'pointer';
                });
            });
        }
    });
</script>
@endpush
