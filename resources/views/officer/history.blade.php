@extends('officer.layouts.master')

@section('title', 'Opstrack - Riwayat & Tanda Tangan')

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
        pointer-events: none;
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
        animation: slideInRight 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), fadeOut 0.5s ease 4.5s forwards;
        position: relative;
        overflow: hidden;
    }

    .toast-card.success { border-left-color: var(--green); }
    .toast-card.success .icon-box { background-color: rgba(25, 135, 84, 0.1); color: var(--green); }
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

    .toast-title { font-size: 14px; font-weight: 700; color: var(--text-main); }
    .toast-message { font-size: 12px; color: var(--text-muted); line-height: 1.4; }

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
        background-color: currentColor;
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
        padding: 10px 60px;
        display: flex;
        flex-direction: column;
        gap: 25px;
        width: 100%;
        max-width: 2000px;
        margin: 0 auto;
        padding-bottom: 50px;
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

    /* --- TAB NAVIGATION STYLE --- */
    .tabs-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .tab-nav {
        display: flex;
        gap: 30px;
        border-bottom: 2px solid var(--border-color);
        padding-bottom: 0;
        margin-bottom: 10px;
    }

    .tab-item {
        padding: 10px 5px 15px 5px;
        font-size: 15px;
        font-weight: 500;
        color: var(--text-muted);
        cursor: pointer;
        position: relative;
        transition: all 0.2s;
        background: none;
        border: none;
    }

    .tab-item:hover {
        color: var(--blue-kss);
    }

    .tab-item.active {
        color: var(--blue-kss);
        font-weight: 600;
    }

    .tab-item.active::after {
        content: '';
        position: absolute;
        bottom: -2px; /* Overlap border bottom parent */
        left: 0;
        width: 100%;
        height: 2px;
        background-color: var(--blue-kss);
        border-radius: 2px 2px 0 0;
    }

    /* Badge Counter di Tab */
    .tab-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: var(--redcolor);
        color: white;
        font-size: 10px;
        font-weight: 700;
        min-width: 18px;
        height: 18px;
        border-radius: 9px;
        padding: 0 5px;
        margin-left: 6px;
        vertical-align: middle;
    }

    /* Tab Content Animation */
    .tab-pane {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .tab-pane.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- TABLE CARD STYLE --- */
    .table-card {
        background-color: var(--bg-card);
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        margin-bottom: 20px;
    }

    /* Header Tabel Pending (Warna Beda) */
    .table-card.pending-section {
        border-color: rgba(243, 156, 18, 0.3);
    }
    .table-card.pending-section .card-header-custom {
        background-color: rgba(243, 156, 18, 0.05); /* Orange muda */
    }
    .table-card.pending-section .card-title {
        color: var(--orange-kss);
    }

    .card-header-custom {
        padding: 20px 25px;
        border-bottom: 1px solid var(--border-color);
        background-color: rgba(0, 119, 194, 0.03);
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
    .table-responsive { width: 100%; overflow-x: auto; }
    .custom-table { width: 100%; border-collapse: collapse; white-space: nowrap; }
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
    .custom-table tbody tr { transition: background-color 0.2s; }
    .custom-table tbody tr:hover { background-color: var(--hover-bg); }
    .custom-table tbody tr:last-child td { border-bottom: none; }

    /* Column Specifics */
    .col-no { width: 60px; text-align: center !important; color: var(--text-muted); }
    .col-aksi { width: 140px; text-align: right !important; }

    /* Action Buttons */
    .action-group { display: flex; gap: 8px; justify-content: flex-end; }
    .btn-icon {
        width: 34px; height: 34px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        border: none; transition: all 0.2s; cursor: pointer; text-decoration: none;
    }
    .btn-icon.view { background-color: rgba(243, 156, 18, 0.1); color: var(--orange-kss); }
    .btn-icon.view:hover { background-color: var(--orange-kss); color: white; transform: translateY(-2px); }
    .btn-icon.edit { background-color: rgba(0, 119, 194, 0.1); color: var(--blue-kss); }
    .btn-icon.edit:hover { background-color: var(--blue-kss); color: white; transform: translateY(-2px); }

    /* Tombol Sign Khusus */
    .btn-sign-action {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        background-color: rgba(25, 135, 84, 0.1);
        color: var(--green);
        border: 1px solid transparent;
    }
    .btn-sign-action:hover {
        background-color: var(--green);
        color: white;
        transform: translateY(-2px);
    }

    /* Badges */
    .doc-type { display: flex; flex-direction: column; gap: 2px; }
    .doc-title { font-weight: 600; color: var(--text-main); }
    .doc-id { font-size: 11px; color: var(--text-muted); }
    .date-info { display: flex; align-items: center; gap: 8px; color: var(--text-main); }
    .date-info i { color: var(--text-muted); font-size: 12px; }
    .badge-shift { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .badge-shift.pagi { background-color: rgba(25, 135, 84, 0.1); color: var(--green); }
    .badge-shift.sore { background-color: rgba(243, 156, 18, 0.1); color: var(--orange-kss); }
    .badge-shift.malam { background-color: rgba(44, 44, 44, 0.1); color: var(--text-main); }

    .empty-state { padding: 50px; text-align: center; color: var(--text-muted); }
    .empty-icon { font-size: 40px; margin-bottom: 15px; opacity: 0.3; }

    /* --- CUSTOM PAGINATION STYLE (NEW) --- */
    .pagination-wrapper {
        padding: 20px 25px;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
    }

    /* Override Bootstrap Pagination for Custom Look */
    .pagination-wrapper .pagination {
        margin-bottom: 0;
        gap: 5px;
    }

    .pagination-wrapper .page-item .page-link {
        color: var(--text-muted);
        border: 1px solid var(--border-color);
        border-radius: 8px; /* Rounded corners */
        padding: 8px 14px;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s ease;
        background-color: transparent;
    }

    /* Active State */
    .pagination-wrapper .page-item.active .page-link {
        background-color: var(--blue-kss);
        border-color: var(--blue-kss);
        color: #fff;
        box-shadow: 0 4px 10px rgba(0, 119, 194, 0.2);
    }

    /* Hover State */
    .pagination-wrapper .page-item:not(.active):not(.disabled) .page-link:hover {
        background-color: var(--hover-bg);
        color: var(--blue-kss);
        border-color: var(--blue-kss);
        transform: translateY(-1px);
    }

    /* Disabled State */
    .pagination-wrapper .page-item.disabled .page-link {
        background-color: var(--bg-body);
        color: #d1d5db;
        border-color: var(--border-color);
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
    @include('officer.layouts.navbar')

    <div class="history-container">

        <!-- HEADER PAGE -->
        <div class="header-history">
            <div class="title-group">
                <span class="title-history">Riwayat Laporan</span>
                <span class="subtitle-history">Kelola laporan masuk dan riwayat laporan group Anda</span>
            </div>
            <a href="{{ route('reports.create') }}" class="btn-create-report">
                <i class="fa-solid fa-plus"></i> Buat Laporan Baru
            </a>
        </div>

        <!-- NOTIFIKASI TOAST (Success/Error) -->
        @if(session('success'))
            <div class="toast-container-fixed">
                <div class="toast-card success">
                    <div class="icon-box"><i class="fa-solid fa-check"></i></div>
                    <div class="toast-content">
                        <span class="toast-title">Berhasil!</span>
                        <span class="toast-message">{{ session('success') }}</span>
                    </div>
                    <button class="btn-close-toast" onclick="this.parentElement.remove()"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>
        @endif

        <!-- TAB NAVIGATION SYSTEM -->
        <div class="tabs-wrapper">
            @php
                // LOGIKA PERBAIKAN: Deteksi parameter 'page' di URL
                // Jika ada ?page=X, otomatis set tab aktif ke 'History' (Riwayat Group)
                $isHistoryTabActive = request()->has('page');
            @endphp

            <!-- Buttons Tab -->
            <div class="tab-nav">
                <!-- Tambahkan logika kondisional class active -->
                <button class="tab-item {{ !$isHistoryTabActive ? 'active' : '' }}" data-target="tab-pending">
                    Laporan Masuk
                    @if(isset($pendingReports) && $pendingReports->count() > 0)
                        <span class="tab-badge">{{ $pendingReports->count() }}</span>
                    @endif
                </button>
                <button class="tab-item {{ $isHistoryTabActive ? 'active' : '' }}" data-target="tab-history">
                    Riwayat Group
                </button>
            </div>

            <!-- Tab Contents -->
            <div class="tab-content-container">

                <!-- ============================================
                     TAB 1: LAPORAN MASUK (PERLU TANDA TANGAN)
                     ============================================ -->
                <!-- Tambahkan logika kondisional class active -->
                <div id="tab-pending" class="tab-pane {{ !$isHistoryTabActive ? 'active' : '' }}">
                    <div class="table-card pending-section">
                        <div class="card-header-custom">
                            <span class="card-title">
                                <i class="fa-solid fa-file-signature"></i> Laporan Masuk (Perlu Tanda Tangan)
                            </span>
                        </div>

                        <div class="table-responsive">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th class="col-no">No</th>
                                        <th>Info Dokumen</th>
                                        <th>Tanggal Laporan</th>
                                        <th>Group</th> <!-- DIPISAH -->
                                        <th>Shift</th> <!-- DIPISAH -->
                                        <th>Pengunggah</th>
                                        <th class="col-aksi">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingReports ?? [] as $index => $item)
                                        <tr>
                                            <td class="col-no">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="doc-type">
                                                    <span class="doc-title">Laporan Operasional</span>
                                                    <span class="doc-id">#{{ $item->id }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="date-info">
                                                    <i class="fa-regular fa-calendar"></i>
                                                    {{ \Carbon\Carbon::parse($item->report_date)->translatedFormat('d M Y') }}
                                                </div>
                                            </td>
                                            <!-- KOLOM GROUP -->
                                            <td>
                                                <div style="font-weight: 600;">Group {{ $item->group_name }}</div>
                                            </td>
                                            <!-- KOLOM SHIFT -->
                                            <td>
                                                <span class="badge-shift {{ strtolower($item->shift) }}">{{ $item->shift }}</span>
                                            </td>
                                            <!-- KOLOM PENGUNGGAH: Menampilkan Nama User dari Controller -->
                                            <td>
                                                <div style="font-weight: 600; color: var(--text-main);">{{ $item->user_name }}</div>
                                                <div style="font-size: 11px; color: var(--text-muted);">Pembuat Laporan</div>
                                            </td>
                                            <td class="col-aksi">
                                                <div class="action-group">
                                                    {{-- Tombol Lihat --}}
                                                    <a href="{{ route('reports.show', $item->id) }}" target="_blank" class="btn-icon view" title="Lihat Detail">
                                                        <i class="fa-regular fa-eye"></i>
                                                    </a>
                                                    {{-- Tombol Tanda Tangan --}}
                                                    <a href="{{ route('reports.sign', $item->id) }}" class="btn-sign-action">
                                                        <i class="fa-solid fa-pen-nib"></i> TTD
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="empty-state">
                                                <i class="fa-solid fa-check-double empty-icon" style="color: var(--green);"></i>
                                                <br>Tidak ada laporan yang menunggu tanda tangan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ============================================
                     TAB 2: RIWAYAT LAPORAN GROUP USER
                     ============================================ -->
                <!-- Tambahkan logika kondisional class active -->
                <div id="tab-history" class="tab-pane {{ $isHistoryTabActive ? 'active' : '' }}">
                    <div class="table-card">
                        <div class="card-header-custom">
                            <span class="card-title">
                                <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Laporan Group Anda
                            </span>
                        </div>

                        <div class="table-responsive">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th class="col-no">No</th>
                                        <th>Jenis Dokumen</th>
                                        <th>Tanggal Laporan</th>
                                        <th>Shift</th>
                                        <th>Pengunggah</th>
                                        <th class="col-aksi">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($groupReports ?? [] as $index => $report)
                                        <tr>
                                            <td class="col-no">{{ $groupReports instanceof \Illuminate\Pagination\LengthAwarePaginator ? $groupReports->firstItem() + $index : $index + 1 }}</td>
                                            <td>
                                                <div class="doc-type">
                                                    <span class="doc-title">Laporan Shift</span>
                                                    <span class="doc-id">ID: #{{ $report->id }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="date-info">
                                                    <i class="fa-regular fa-calendar"></i>
                                                    {{ \Carbon\Carbon::parse($report->report_date)->translatedFormat('d M Y') }}
                                                </div>
                                            </td>
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
                                            <!-- KOLOM PENGUNGGAH: Menampilkan Nama User dari Controller -->
                                            <td>
                                                <div style="font-weight: 600; color: var(--text-main);">{{ $report->user_name ?? Auth::user()->name }}</div>
                                            </td>
                                            <td class="col-aksi">
                                                <div class="action-group">
                                                    {{-- Tombol Lihat --}}
                                                    <a href="{{ route('reports.show', $report->id) }}" target="_blank" class="btn-icon view" title="Lihat Detail">
                                                        <i class="fa-regular fa-eye"></i>
                                                    </a>

                                                    {{-- TOMBOL EDIT BARU --}}
                                                    @if($report->status === 'submitted')
                                                    <a href="{{ route('reports.edit', $report->id) }}" class="btn-icon edit" title="Edit Laporan">
                                                        <i class="fa-solid fa-pencil"></i>
                                                    </a>
                                                    @endif

                                                    {{-- Tombol Tanda Tangan (Hanya di Tab Pending, bukan di History Group ini) --}}
                                                    {{-- Logika ini disesuaikan dengan tab mana user berada --}}
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="empty-state">
                                                <i class="fa-regular fa-folder-open empty-icon"></i>
                                                <br>Belum ada riwayat laporan dari Group Anda.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination untuk Tabel Riwayat -->
                        <!-- Menggunakan 'pagination::bootstrap-4' untuk memastikan struktur HTML sesuai dengan CSS Custom -->
                        @if(isset($groupReports) && $groupReports instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="pagination-wrapper">
                                {{ $groupReports->onEachSide(1)->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>

            </div> <!-- End Tab Content Container -->
        </div> <!-- End Tabs Wrapper -->

    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.tab-item');
        const panes = document.querySelectorAll('.tab-pane');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // 1. Remove active class from all tabs and panes
                tabs.forEach(t => t.classList.remove('active'));
                panes.forEach(p => p.classList.remove('active'));

                // 2. Add active class to clicked tab
                tab.classList.add('active');

                // 3. Show target pane
                const targetId = tab.getAttribute('data-target');
                const targetPane = document.getElementById(targetId);
                if (targetPane) {
                    targetPane.classList.add('active');
                }
            });
        });
    });
</script>
@endpush
