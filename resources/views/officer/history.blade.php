@extends('officer.layouts.master')

@section('title', 'Opstrack')

@push('styles')
<style>
    /* --- TOAST NOTIFICATION (Floating Card) --- */
    .toast-container-fixed {
        position: fixed;
        top: 30px;
        right: 30px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 15px;
        pointer-events: none; /* Allow clicking through container */
    }

    .toast-card {
        background-color: var(--bg-card);
        border-radius: 12px;
        padding: 16px 20px;
        min-width: 320px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: flex-start;
        gap: 15px;
        border-left: 6px solid;
        pointer-events: auto;
        /* Animation: Slide In -> Stay -> Fade Out */
        animation: slideInRight 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), fadeOut 0.5s ease 4.5s forwards;
        position: relative;
        overflow: hidden;
    }

    /* Success Theme */
    .toast-card.success { border-left-color: var(--green); }
    .toast-card.success .icon-box { background-color: rgba(25, 135, 84, 0.1); color: var(--green); }

    /* Error Theme */
    .toast-card.error { border-left-color: var(--redcolor); }
    .toast-card.error .icon-box { background-color: rgba(210, 0, 0, 0.1); color: var(--redcolor); }

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

    .toast-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-main);
    }

    .toast-message {
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.4;
    }

    .btn-close-toast {
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        padding: 0;
        font-size: 14px;
        transition: color 0.2s;
    }
    .btn-close-toast:hover { color: var(--text-main); }

    /* Progress Bar Animation */
    .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        width: 100%;
        background-color: rgba(0,0,0,0.05);
    }
    .toast-progress-bar {
        height: 100%;
        width: 100%;
        background-color: currentColor; /* Inherits color from parent (green/red) */
        animation: progress 4.5s linear forwards;
        transform-origin: left;
    }

    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes fadeOut {
        to { transform: translateX(10px); opacity: 0; }
    }
    @keyframes progress {
        to { transform: scaleX(0); }
    }

    /* --- CONTAINER & LAYOUT --- */
    .history-container {
        padding: 10px 60px; /* Padding sisi agar tidak mepet */
        display: flex;
        flex-direction: column;
        gap: 25px;
        width: 100%;
        max-width: 2000px; /* Batas lebar agar tidak terlalu stretch di layar lebar */
        margin: 0 auto;
    }

    @media (max-width: 768px) {
        .history-container { padding: 20px; }
    }

    /* --- HEADER SECTION --- */
    .header-history {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .title-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .title-history {
        font-size: 20px;
        font-weight: 600;
        color: var(--text-main);
        letter-spacing: -0.5px;
    }

    .subtitle-history {
        font-size: 14px;
        color: var(--text-muted);
        font-weight: 400;
    }

    .btn-create-report {
        background-color: var(--blue-kss);
        color: var(--white-color);
        padding: 12px 24px;
        border-radius: 12px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 119, 194, 0.2);
        border: 1px solid transparent;
    }

    .btn-create-report:hover {
        background-color: var(--blue-kss-dark);
        color: var(--white-color);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 119, 194, 0.3);
    }

    /* --- TABLE CARD STYLE --- */
    .table-card {
        background-color: var(--bg-card);
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
        overflow: hidden; /* Clip corners */
    }

    .card-header-custom {
        padding: 20px 25px;
        border-bottom: 1px solid var(--border-color);
        background-color: rgba(0, 119, 194, 0.03); /* Biru sangat muda */
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--blue-kss);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* --- TABLE CUSTOMIZATION --- */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
        white-space: nowrap;
    }

    .custom-table th {
        background-color: var(--bg-body);
        color: var(--text-muted);
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 18px 25px;
        text-align: left;
        border-bottom: 2px solid var(--border-color);
    }

    .custom-table td {
        padding: 18px 25px;
        color: var(--text-main);
        font-size: 14px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
        font-weight: 500;
    }

    .custom-table tbody tr {
        transition: background-color 0.2s;
    }

    .custom-table tbody tr:hover {
        background-color: var(--hover-bg);
    }

    .custom-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Column Specifics */
    .col-no { width: 60px; text-align: center !important; color: var(--text-muted); }
    .col-aksi { width: 120px; text-align: right !important; }

    /* Badges & Text Styles */
    .doc-type {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .doc-title { font-weight: 600; color: var(--text-main); }
    .doc-id { font-size: 11px; color: var(--text-muted); }

    .date-info {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-main);
    }
    .date-info i { color: var(--text-muted); font-size: 12px; }

    .badge-shift {
        display: inline-flex;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-shift.pagi { background-color: rgba(25, 135, 84, 0.1); color: var(--green); }
    .badge-shift.sore { background-color: rgba(243, 156, 18, 0.1); color: var(--orange-kss); }
    .badge-shift.malam { background-color: rgba(44, 44, 44, 0.1); color: var(--text-main); }
    [data-theme="dark"] .badge-shift.malam { background-color: rgba(255, 255, 255, 0.1); color: #fff; }

    /* Action Buttons */
    .action-group {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }

    .btn-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: all 0.2s;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-icon.view { background-color: rgba(243, 156, 18, 0.1); color: var(--orange-kss); }
    .btn-icon.view:hover { background-color: var(--orange-kss); color: white; transform: translateY(-2px); }

    .btn-icon.edit { background-color: rgba(0, 119, 194, 0.1); color: var(--blue-kss); }
    .btn-icon.edit:hover { background-color: var(--blue-kss); color: white; transform: translateY(-2px); }

    /* Empty State */
    .empty-state {
        padding: 50px;
        text-align: center;
        color: var(--text-muted);
    }
    .empty-icon {
        font-size: 40px;
        margin-bottom: 15px;
        opacity: 0.3;
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 20px 25px;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
    }
</style>
@endpush

@section('content')
    <!-- Pastikan include Navbar jika layout master Anda belum meng-include-nya otomatis -->
    @include('officer.layouts.navbar')

    <div class="history-container">

        <!-- HEADER PAGE -->
        <div class="header-history">
            <div class="title-group">
                <span class="title-history">Riwayat Laporan</span>
                <span class="subtitle-history">Daftar laporan harian shift operasional yang telah dibuat</span>
            </div>
            <a href="{{ route('reports.create') }}" class="btn-create-report">
                <i class="fa-solid fa-plus"></i> Buat Laporan Baru
            </a>
        </div>

        <!-- NOTIFIKASI MELAYANG (TOAST) -->
        @if(session('success'))
            <div class="toast-container-fixed">
                <div class="toast-card success">
                    <div class="icon-box">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div class="toast-content">
                        <span class="toast-title">Berhasil Disimpan!</span>
                        <span class="toast-message">{{ session('success') }}</span>
                    </div>
                    <button class="btn-close-toast" onclick="this.parentElement.remove()">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <!-- Progress Bar Timer -->
                    <div class="toast-progress">
                        <div class="toast-progress-bar" style="color: var(--green);"></div>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="toast-container-fixed">
                <div class="toast-card error">
                    <div class="icon-box">
                        <i class="fa-solid fa-exclamation"></i>
                    </div>
                    <div class="toast-content">
                        <span class="toast-title">Gagal Menyimpan!</span>
                        <span class="toast-message">{{ session('error') }}</span>
                    </div>
                    <button class="btn-close-toast" onclick="this.parentElement.remove()">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <div class="toast-progress">
                        <div class="toast-progress-bar" style="color: var(--redcolor);"></div>
                    </div>
                </div>
            </div>
        @endif

        <!-- TABLE CARD -->
        <div class="table-card">
            <div class="card-header-custom">
                <span class="card-title">
                    <i class="fa-solid fa-file-invoice"></i> Dokumen Tersimpan
                </span>
            </div>

            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th>Jenis Dokumen</th>
                            <th>Tanggal Laporan</th>
                            <th>Group / Regu</th>
                            <th>Shift</th>
                            <th>Pengunggah</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $index => $report)
                            <tr>
                                <td class="col-no">{{ $reports->firstItem() + $index }}</td>
                                <td>
                                    <div class="doc-type">
                                        <span class="doc-title">Laporan Harian Shift</span>
                                        <span class="doc-id">ID: #{{ $report->id }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="date-info">
                                        <i class="fa-regular fa-calendar"></i>
                                        {{ \Carbon\Carbon::parse($report->report_date)->translatedFormat('d M Y') }}
                                    </div>
                                </td>
                                <td>Group {{ $report->group_name }}</td>
                                <td>
                                    @php
                                        $shiftClass = match($report->shift) {
                                            'Pagi' => 'pagi',
                                            'Sore' => 'sore',
                                            'Malam' => 'malam',
                                            default => 'pagi'
                                        };
                                    @endphp
                                    <span class="badge-shift {{ $shiftClass }}">{{ $report->shift }}</span>
                                </td>
                                <td>{{ Auth::user()->name ?? 'Petugas' }}</td>
                                <td class="col-aksi">
                                    <div class="action-group">
                                        <a href="{{ route('reports.export_pdf', $report->id) }}" class="btn-icon view" title="Cetak PDF" target="_blank">
                                            <i class="fa-solid fa-print"></i>
                                        </a>
                                        <!-- Tombol Lihat (Opsional, buat route show jika perlu) -->
                                        <a href="#" class="btn-icon view" title="Lihat Detail"><i class="fa-regular fa-eye"></i></a>
                                        <!-- Tombol Edit (Opsional) -->
                                        <!-- <a href="#" class="btn-icon edit" title="Edit Laporan"><i class="fa-solid fa-pencil"></i></a> -->
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">
                                    <i class="fa-regular fa-folder-open empty-icon"></i>
                                    <br>
                                    Belum ada laporan yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush
