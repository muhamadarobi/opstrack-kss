@extends('officer.layouts.master')

@section('title', 'Opstrack - Riwayat & Tanda Tangan')

@push('styles')
<style>
    /* --- TOAST NOTIFICATION --- */
    .toast-container-fixed { position: fixed; top: 30px; right: 30px; z-index: 9999; display: flex; flex-direction: column; gap: 15px; pointer-events: none; }
    .toast-card { background-color: var(--bg-card); border-radius: 12px; padding: 16px 20px; min-width: 320px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15); display: flex; align-items: flex-start; gap: 15px; border-left: 6px solid; pointer-events: auto; position: relative; overflow: hidden; animation: slideInRight 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), fadeOut 0.5s ease 4.5s forwards; }
    .toast-card.success { border-left-color: var(--green); }
    .toast-card.success .icon-box { background-color: rgba(25, 135, 84, 0.1); color: var(--green); }
    .toast-card.error { border-left-color: var(--redcolor); }
    .toast-card.error .icon-box { background-color: rgba(210, 0, 0, 0.1); color: var(--redcolor); }
    .toast-card .icon-box { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .toast-content { display: flex; flex-direction: column; gap: 4px; flex: 1; }
    .toast-title { font-size: 14px; font-weight: 700; color: var(--text-main); }
    .toast-message { font-size: 12px; color: var(--text-muted); line-height: 1.4; }
    .btn-close-toast { background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 0; font-size: 14px; transition: color 0.2s; }
    .btn-close-toast:hover { color: var(--text-main); }
    .toast-progress { position: absolute; bottom: 0; left: 0; height: 3px; width: 100%; background-color: rgba(0,0,0,0.05); }
    .toast-progress-bar { height: 100%; width: 100%; background-color: currentColor; animation: progress 4.5s linear forwards; transform-origin: left; }
    @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @keyframes fadeOut { to { transform: translateX(10px); opacity: 0; } }
    @keyframes progress { to { transform: scaleX(0); } }

    /* --- LAYOUT & TABLE STYLES --- */
    .history-container { padding: 10px 60px; display: flex; flex-direction: column; gap: 25px; width: 100%; max-width: 2000px; margin: 0 auto; padding-bottom: 50px; }
    @media (max-width: 768px) { .history-container { padding: 20px; } }
    .header-history { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
    .title-history { font-size: 20px; font-weight: 600; color: var(--text-main); letter-spacing: -0.5px; }
    .subtitle-history { font-size: 14px; color: var(--text-muted); font-weight: 400; }
    .btn-create-report { background-color: var(--blue-kss); color: var(--white-color); padding: 12px 24px; border-radius: 12px; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 10px; transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(0, 119, 194, 0.2); border: 1px solid transparent; }
    .btn-create-report:hover { background-color: var(--blue-kss-dark); color: var(--white-color); transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0, 119, 194, 0.3); }

    /* Tab Nav */
    .tabs-wrapper { display: flex; flex-direction: column; gap: 20px; }
    .tab-nav { display: flex; gap: 30px; border-bottom: 2px solid var(--border-color); padding-bottom: 0; margin-bottom: 10px; }
    .tab-item { padding: 10px 5px 15px 5px; font-size: 15px; font-weight: 500; color: var(--text-muted); cursor: pointer; position: relative; transition: all 0.2s; background: none; border: none; }
    .tab-item:hover { color: var(--blue-kss); }
    .tab-item.active { color: var(--blue-kss); font-weight: 600; }
    .tab-item.active::after { content: ''; position: absolute; bottom: -2px; left: 0; width: 100%; height: 2px; background-color: var(--blue-kss); border-radius: 2px 2px 0 0; }
    .tab-badge { display: inline-flex; align-items: center; justify-content: center; background-color: var(--redcolor); color: white; font-size: 10px; font-weight: 700; min-width: 18px; height: 18px; border-radius: 9px; padding: 0 5px; margin-left: 6px; vertical-align: middle; }
    .tab-pane { display: none; animation: fadeIn 0.3s ease; }
    .tab-pane.active { display: block; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

    /* Tables */
    .table-card { background-color: var(--bg-card); border-radius: 16px; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08); border: 1px solid var(--border-color); display: flex; flex-direction: column; overflow: hidden; margin-bottom: 20px; }
    .table-card.pending-section { border-color: rgba(243, 156, 18, 0.3); }
    .table-card.pending-section .card-header-custom { background-color: rgba(243, 156, 18, 0.05); }
    .table-card.pending-section .card-title { color: var(--orange-kss); }
    .card-header-custom { padding: 20px 25px; border-bottom: 1px solid var(--border-color); background-color: rgba(0, 119, 194, 0.03); display: flex; align-items: center; justify-content: space-between; }
    .card-title { font-size: 16px; font-weight: 600; color: var(--blue-kss); display: flex; align-items: center; gap: 10px; }
    .table-responsive { width: 100%; overflow-x: auto; }
    .custom-table { width: 100%; border-collapse: collapse; white-space: nowrap; }
    .custom-table th { background-color: var(--bg-body); color: var(--text-muted); font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 18px 25px; text-align: left; border-bottom: 2px solid var(--border-color); }
    .custom-table td { padding: 18px 25px; color: var(--text-main); font-size: 14px; border-bottom: 1px solid var(--border-color); vertical-align: middle; font-weight: 500; }
    .custom-table tbody tr:hover { background-color: var(--hover-bg); }
    .custom-table tbody tr:last-child td { border-bottom: none; }
    .btn-icon { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: none; transition: all 0.2s; cursor: pointer; text-decoration: none; }
    .btn-icon.view { background-color: rgba(243, 156, 18, 0.1); color: var(--orange-kss); }
    .btn-icon.view:hover { background-color: var(--orange-kss); color: white; transform: translateY(-2px); }
    .btn-icon.edit { background-color: rgba(0, 119, 194, 0.1); color: var(--blue-kss); }
    .btn-icon.edit:hover { background-color: var(--blue-kss); color: white; transform: translateY(-2px); }
    .btn-sign-action { padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; background-color: rgba(25, 135, 84, 0.1); color: var(--green); border: 1px solid transparent; cursor: pointer; }
    .btn-sign-action:hover { background-color: var(--green); color: white; transform: translateY(-2px); }
    .badge-shift { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .badge-shift.pagi { background-color: rgba(25, 135, 84, 0.1); color: var(--green); }
    .badge-shift.sore { background-color: rgba(243, 156, 18, 0.1); color: var(--orange-kss); }
    .badge-shift.malam { background-color: rgba(44, 44, 44, 0.1); color: var(--text-main); }
    .empty-state { padding: 50px; text-align: center; color: var(--text-muted); }
    .empty-icon { font-size: 40px; margin-bottom: 15px; opacity: 0.3; }
    .pagination-wrapper { padding: 20px 25px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; }
    .pagination-wrapper .pagination { margin-bottom: 0; gap: 5px; }
    .pagination-wrapper .page-item .page-link { color: var(--text-muted); border: 1px solid var(--border-color); border-radius: 8px; padding: 8px 14px; font-size: 13px; font-weight: 500; transition: all 0.2s ease; background-color: transparent; }
    .pagination-wrapper .page-item.active .page-link { background-color: var(--blue-kss); border-color: var(--blue-kss); color: #fff; box-shadow: 0 4px 10px rgba(0, 119, 194, 0.2); }

    /* ============================================== */
    /* === GLOBAL ANIMATION OVERLAY (SHARED) === */
    /* ============================================== */
    #anim-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background-color: rgba(0, 0, 0, 0.85); backdrop-filter: blur(8px);
        z-index: 10000; display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        opacity: 0; visibility: hidden; transition: all 0.4s ease;
    }
    #anim-overlay.show { opacity: 1; visibility: visible; }
    .anim-content { display: none; flex-direction: column; align-items: center; justify-content: center; width: 100%; height: 100%; }
    .anim-content.active { display: flex; }

    .anim-text {
        color: white; font-size: 22px; font-weight: 700;
        opacity: 0; transform: translateY(20px); margin-top: 30px;
        text-shadow: 0 4px 10px rgba(0,0,0,0.5); text-align: center;
    }
    #anim-overlay.show .anim-text { animation: fadeInUp 0.5s ease forwards 1s; }
    @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }

    /* --- 1. ANIMASI TANDA TANGAN (PESAWAT KERTAS) --- */
    .paper-plane-container { position: relative; width: 100px; height: 100px; }
    .doc-icon-anim {
        width: 80px; height: 110px; background: #fff; border-radius: 8px;
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        display: flex; flex-direction: column; padding: 15px; gap: 10px;
        box-shadow: 0 0 40px rgba(255,255,255,0.2);
        transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275); z-index: 2;
    }
    .doc-icon-anim .line { height: 4px; background: #e5e7eb; width: 100%; border-radius: 2px; }
    .doc-icon-anim .line.short { width: 60%; }
    .doc-icon-anim.fold { transform: translate(-50%, -50%) scale(0) rotate(45deg); opacity: 0; }
    .plane-icon-anim {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(0);
        font-size: 80px; color: #fff; opacity: 0; z-index: 3;
        transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .plane-icon-anim.appear { transform: translate(-50%, -50%) scale(1) rotate(0deg); opacity: 1; }
    .plane-icon-anim.fly { animation: flyAway 1.2s ease-in-out forwards; }
    @keyframes flyAway {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        20% { transform: translate(-80%, -20%) rotate(-15deg); }
        100% { transform: translate(500px, -500px) rotate(45deg); opacity: 0; }
    }

    /* --- 2. ANIMASI SIMPAN SUKSES (DOKUMEN CENTANG) --- */
    .doc-success {
        width: 120px; height: 160px; background: #fff; border-radius: 12px;
        box-shadow: 0 0 40px rgba(255,255,255,0.2);
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        transform: scale(0); transition: 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .doc-success.animate { transform: scale(1); }
    .checkmark-svg { width: 70px; height: 70px; z-index: 5; }
    .checkmark-path {
        fill: none; stroke: #198754; stroke-width: 6; stroke-linecap: round; stroke-linejoin: round;
        stroke-dasharray: 100; stroke-dashoffset: 100;
    }
    .doc-success.animate .checkmark-path { animation: drawCheck 0.6s ease-in-out 0.4s forwards; }
    @keyframes drawCheck { to { stroke-dashoffset: 0; } }

    /* --- MODAL CONFIRM (ADMIN STYLE) --- */
    .modal-confirm .modal-content { border-radius: 20px; border: 1px solid var(--border-color); box-shadow: 0 20px 40px rgba(0,0,0,0.15); overflow: hidden; background-color: var(--bg-card); }
    .modal-confirm .modal-header { background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-bottom: none; padding: 25px 25px 10px; justify-content: center; display: flex; flex-direction: column; align-items: center; }
    [data-theme="dark"] .modal-confirm .modal-header { background: linear-gradient(135deg, #064e3b 0%, #065f46 100%); }
    .modal-confirm .icon-box-modal { width: 80px; height: 80px; margin: 0 auto 15px; border-radius: 50%; background-color: var(--bg-card); display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 20px rgba(22, 163, 74, 0.15); animation: pulse-green 2s infinite; color: var(--green); font-size: 40px; }
    @keyframes pulse-green {
        0% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.4); }
        70% { box-shadow: 0 0 0 15px rgba(22, 163, 74, 0); }
        100% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
    }
    .modal-confirm .modal-title { color: var(--green-dark); font-weight: 700; font-size: 20px; }
    [data-theme="dark"] .modal-confirm .modal-title { color: #a7f3d0; }
    .modal-confirm .modal-body { padding: 20px 30px; text-align: center; color: var(--text-main); }
    .modal-confirm .modal-footer { border: none; padding: 0 30px 30px; justify-content: center; gap: 10px; background-color: var(--bg-card); }
    .btn-modal-cancel { background-color: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-muted); padding: 12px 24px; border-radius: 10px; font-weight: 600; width: 45%; transition: all 0.2s; }
    .btn-modal-cancel:hover { background-color: var(--bg-body); color: var(--text-main); }
    .btn-modal-confirm { background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); border: none; color: #fff; padding: 12px 24px; border-radius: 10px; font-weight: 600; width: 55%; box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3); transition: all 0.3s ease; }
    .btn-modal-confirm:hover { background: linear-gradient(135deg, #15803d 0%, #166534 100%); transform: translateY(-2px); }
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
        {{-- Kita sembunyikan toast default jika animasi overlay aktif --}}
        @if(session('success'))
            <div class="toast-container-fixed" style="z-index: 999;">
                {{-- Toast ini akan tampil sebentar sebelum tertutup overlay atau sebagai backup --}}
                {{-- Bisa dikosongkan jika ingin full animasi overlay --}}
            </div>
        @endif
        @if(session('error'))
            <div class="toast-container-fixed">
                <div class="toast-card error">
                    <div class="icon-box"><i class="fa-solid fa-xmark"></i></div>
                    <div class="toast-content">
                        <span class="toast-title">Gagal!</span>
                        <span class="toast-message">{{ session('error') }}</span>
                    </div>
                    <button class="btn-close-toast" onclick="this.parentElement.remove()"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>
        @endif

        <!-- TAB NAVIGATION SYSTEM -->
        <div class="tabs-wrapper">
            @php $isHistoryTabActive = request()->has('page'); @endphp

            <div class="tab-nav">
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

            <div class="tab-content-container">

                <!-- TAB 1: LAPORAN MASUK -->
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
                                        <th>Group</th>
                                        <th>Shift</th>
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
                                                <div class="date-info"><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($item->report_date)->translatedFormat('d M Y') }}</div>
                                            </td>
                                            <td><div style="font-weight: 600;">Group {{ $item->group_name }}</div></td>
                                            <td><span class="badge-shift {{ strtolower($item->shift) }}">{{ $item->shift }}</span></td>
                                            <td>
                                                <div style="font-weight: 600; color: var(--text-main);">{{ $item->user_name }}</div>
                                                <div style="font-size: 11px; color: var(--text-muted);">Pembuat Laporan</div>
                                            </td>
                                            <td class="col-aksi">
                                                <div class="action-group d-flex" style="gap: 6px">
                                                    <a href="{{ route('reports.show', $item->id) }}" target="_blank" class="btn-icon view" title="Lihat Detail"><i class="fa-regular fa-eye"></i></a>
                                                    {{-- TOMBOL TRIGGER MODAL --}}
                                                    <button type="button" class="btn-sign-action trigger-sign-modal"
                                                            data-url="{{ route('reports.sign', $item->id) }}"
                                                            data-id="{{ $item->id }}">
                                                        <i class="fa-solid fa-pen-nib"></i> TTD
                                                    </button>
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

                <!-- TAB 2: RIWAYAT GROUP -->
                <div id="tab-history" class="tab-pane {{ $isHistoryTabActive ? 'active' : '' }}">
                    <div class="table-card">
                        <div class="card-header-custom">
                            <span class="card-title"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Laporan Group Anda</span>
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
                                                <div class="doc-type"><span class="doc-title">Laporan Shift</span><span class="doc-id">ID: #{{ $report->id }}</span></div>
                                            </td>
                                            <td><div class="date-info"><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($report->report_date)->translatedFormat('d M Y') }}</div></td>
                                            <td>
                                                @php $shiftClass = match($report->shift) { 'Pagi' => 'pagi', 'Sore' => 'sore', 'Malam' => 'malam', default => 'pagi' }; @endphp
                                                <span class="badge-shift {{ $shiftClass }}">{{ $report->shift }}</span>
                                            </td>
                                            <td><div style="font-weight: 600; color: var(--text-main);">{{ $report->user_name ?? Auth::user()->name }}</div></td>
                                            <td class="col-aksi " >
                                                <div class="action-group d-flex" style="gap: 6px">
                                                    <a href="{{ route('reports.show', $report->id) }}" target="_blank" class="btn-icon view" title="Lihat Detail"><i class="fa-regular fa-eye"></i></a>
                                                    @if($report->status === 'submitted')
                                                    <a href="{{ route('reports.edit', $report->id) }}" class="btn-icon edit" title="Edit Laporan"><i class="fa-solid fa-pencil"></i></a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="empty-state"><i class="fa-regular fa-folder-open empty-icon"></i><br>Belum ada riwayat laporan dari Group Anda.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if(isset($groupReports) && $groupReports instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="pagination-wrapper">{{ $groupReports->onEachSide(1)->links('pagination::bootstrap-4') }}</div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <!-- MODAL CONFIRMATION -->
        <div class="modal fade modal-confirm" id="signModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="icon-box-modal"><i class="fa-solid fa-signature"></i></div>
                        <h5 class="modal-title">Konfirmasi Tanda Tangan</h5>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted mb-3">
                            Apakah Anda yakin data laporan <strong>#<span id="modal-report-id"></span></strong> sudah benar?
                            <br><small class="text-danger">*Tanda tangan bersifat mengikat dan laporan akan diteruskan ke Manajer.</small>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                        <form id="signForm" style="margin:0; width:55%;">
                            <button type="submit" class="btn btn-modal-confirm w-100">
                                <i class="fa-solid fa-pen-nib"></i> Ya, Tanda Tangani
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- OVERLAY ANIMATION CONTAINER (Shared) -->
        <div id="anim-overlay">

            <!-- 1. ANIMASI TANDA TANGAN (Pesawat Kertas) -->
            <div id="anim-sign" class="anim-content">
                <div class="paper-plane-container">
                    <div class="doc-icon-anim">
                        <div class="line"></div><div class="line"></div><div class="line"></div><div class="line short"></div><div class="line"></div>
                    </div>
                    <div class="plane-icon-anim"><i class="fa-solid fa-paper-plane"></i></div>
                </div>
                <div class="anim-text">Laporan Terkirim ke Manajer!</div>
            </div>

            <!-- 2. ANIMASI SAVE SUCCESS (Dokumen Centang) -->
            <div id="anim-success" class="anim-content">
                <div class="doc-success">
                    <svg class="checkmark-svg" viewBox="0 0 52 52">
                        <circle cx="26" cy="26" r="25" fill="none"/>
                        <path class="checkmark-path" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                    </svg>
                </div>
                <div class="anim-text">Laporan Berhasil Disimpan!</div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. TAB LOGIC ---
        const tabs = document.querySelectorAll('.tab-item');
        const panes = document.querySelectorAll('.tab-pane');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                panes.forEach(p => p.classList.remove('active'));
                tab.classList.add('active');
                const targetId = tab.getAttribute('data-target');
                const targetPane = document.getElementById(targetId);
                if (targetPane) targetPane.classList.add('active');
            });
        });

        // --- 2. CEK SESSION FLASH UNTUK ANIMASI SAVE ---
        const hasSuccess = @json(session()->has('success'));
        if (hasSuccess) {
            const overlay = document.getElementById('anim-overlay');
            const animSuccess = document.getElementById('anim-success');

            if (overlay && animSuccess) {
                // Tampilkan Overlay & Konten
                overlay.classList.add('show');
                animSuccess.classList.add('active');

                // Trigger Animasi Centang
                setTimeout(() => {
                    document.querySelector('.doc-success').classList.add('animate');
                }, 100);

                // Hilangkan Overlay setelah 2.5 detik
                setTimeout(() => {
                    overlay.classList.remove('show');
                    // Opsional: Hapus class active setelah fade out selesai
                    setTimeout(() => { animSuccess.classList.remove('active'); }, 400);
                }, 2500);
            }
        }

        // --- 3. SIGN MODAL & ANIMATION LOGIC ---
        const signModalEl = document.getElementById('signModal');
        const signModal = signModalEl ? new bootstrap.Modal(signModalEl) : null;
        const signForm = document.getElementById('signForm');
        const modalReportId = document.getElementById('modal-report-id');

        // Anim Elements for Signing
        const overlay = document.getElementById('anim-overlay');
        const animSign = document.getElementById('anim-sign');
        const docIcon = document.querySelector('.doc-icon-anim');
        const planeIcon = document.querySelector('.plane-icon-anim');

        // Event Delegation
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.trigger-sign-modal');
            if (btn) {
                const url = btn.getAttribute('data-url');
                const id = btn.getAttribute('data-id');
                if(signForm && signModal) {
                    signForm.action = url;
                    if(modalReportId) modalReportId.textContent = id;
                    signModal.show();
                }
            }
        });

        // Handle Sign Submit
        if(signForm) {
            signForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const btnSubmit = this.querySelector('button[type="submit"]');
                const originalText = btnSubmit.innerHTML;
                const actionUrl = this.action;

                // UI Loading
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Memproses...';

                // Gunakan GET request sesuai definisi Route
                fetch(actionUrl, {
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                .then(response => {
                    if (response.ok) return { status: 'ok' };
                    else throw new Error('Server status: ' + response.status);
                })
                .then(data => {
                    // SUKSES
                    if(signModal) signModal.hide();

                    // Mainkan Animasi Pesawat
                    if(overlay && animSign) {
                        overlay.classList.add('show');
                        animSign.classList.add('active');

                        setTimeout(() => {
                            if(docIcon) docIcon.classList.add('fold');
                            if(planeIcon) planeIcon.classList.add('appear');
                        }, 300);

                        setTimeout(() => {
                            if(planeIcon) planeIcon.classList.add('fly');
                        }, 1000);

                        setTimeout(() => { window.location.reload(); }, 2500);
                    } else {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memproses tanda tangan. Coba lagi.');
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = originalText;
                });
            });
        }
    });
</script>
@endpush
