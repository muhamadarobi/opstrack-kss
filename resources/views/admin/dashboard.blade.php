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
        border-top: 1px solid #f1f5f9;
    }

    /* 1. Tombol Review (Soft Gray) */
    .btn-review-custom {
        background-color: #f8fafc;
        color: #64748b;
        border: 1px solid #e2e8f0;
        padding: 9px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-review-custom:hover {
        background-color: #ffffff;
        color: #0f172a;
        border-color: #cbd5e1;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
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
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid #f1f5f9;
    }
    .notif-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.06) !important;
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

    /* --- TOAST NOTIFICATION STYLE --- */
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
        background-color: #fff;
        border-radius: 12px;
        padding: 16px 20px;
        min-width: 320px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: flex-start;
        gap: 15px;
        border-left: 5px solid;
        pointer-events: auto; /* Re-enable clicks */
        animation: slideInRight 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), fadeOut 0.5s ease 4.5s forwards;
        position: relative;
        overflow: hidden;
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

    .toast-title { font-size: 14px; font-weight: 700; color: #1e293b; }
    .toast-message { font-size: 13px; color: #64748b; line-height: 1.4; }

    .btn-close-toast {
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 0;
        font-size: 14px;
        transition: color 0.2s;
    }
    .btn-close-toast:hover { color: #1e293b; }

    .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        width: 100%;
        background-color: #f1f5f9;
    }

    .toast-progress-bar {
        height: 100%;
        width: 100%;
        animation: progress 4.5s linear forwards;
        transform-origin: left;
    }

    .toast-card.success .toast-progress-bar { background-color: #10b981; }
    .toast-card.error .toast-progress-bar { background-color: #ef4444; }

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

    /* --- MODAL APPROVAL CUSTOM STYLE --- */
    .modal-confirm .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        overflow: hidden;
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
    .modal-confirm .icon-box {
        width: 80px;
        height: 80px;
        margin: 0 auto 15px;
        border-radius: 50%;
        background-color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 20px rgba(22, 163, 74, 0.15);
        animation: pulse-green 2s infinite;
    }
    .modal-confirm .icon-box i {
        font-size: 40px;
        color: #16a34a;
    }
    .modal-confirm .modal-title {
        color: #166534;
        font-weight: 700;
        font-size: 20px;
    }
    .modal-confirm .modal-body {
        padding: 20px 30px;
        text-align: center;
    }
    .doc-preview-box {
        background-color: #f8fafc;
        border: 1px dashed #cbd5e1;
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
    .doc-preview-label { color: #64748b; }
    .doc-preview-value { font-weight: 600; color: #334155; }

    .modal-confirm .modal-footer {
        border: none;
        padding: 0 30px 30px;
        justify-content: center;
        gap: 10px;
    }
    .btn-modal-cancel {
        background-color: #fff;
        border: 1px solid #cbd5e1;
        color: #64748b;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        width: 45%;
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
    }
    @keyframes pulse-green {
        0% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.4); }
        70% { box-shadow: 0 0 0 15px rgba(22, 163, 74, 0); }
        100% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
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
                        <span class="notif-title" style="font-size: 18px; font-weight: 700; color: #1e293b;">
                            Menunggu Persetujuan
                        </span>
                        <span class="badge bg-warning text-dark rounded-pill">{{ $recentReports->count() }}</span>
                    </div>
                </div>

                <p style="font-size: 13px; color: #64748b; margin-bottom: 5px; margin-left: 12px;">
                    Dokumen berikut telah diserahkan oleh petugas dan menunggu tanda tangan digital Anda untuk diarsipkan.
                </p>

                <div class="notif-list d-flex flex-column align-items-start align-self-stretch" style="gap: 20px;">

                    @forelse($recentReports as $report)
                        <!-- CARD NOTIFIKASI ITEM -->
                        <div class="notif-item" style="background-color: #fff; padding: 24px; border-radius: 16px; width: 100%; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                            <div class="detail-notif d-flex flex-column align-items-start" style="gap: 10px; width: 100%;">

                                <!-- Header Card: Group Info & Time -->
                                <div class="d-flex justify-content-between align-items-start w-100 flex-wrap gap-2">
                                    <div class="info-notif d-flex flex-column align-items-start" style="gap: 6px;">

                                        <!-- Baris Judul & Shift -->
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="nama fw-bold text-dark" style="font-size: 16px;">Group {{ $report->group_name }}</span>
                                            @php
                                                $shiftClass = match(strtolower($report->shift)) {
                                                    'pagi' => 'pagi', 'sore' => 'sore', 'malam' => 'malam', default => 'pagi'
                                                };
                                            @endphp
                                            <span class="badge-shift {{ $shiftClass }}">Shift {{ $report->shift }}</span>
                                        </div>

                                        <!-- Detail Laporan -->
                                        <div class="d-flex align-items-center gap-2 text-muted" style="font-size: 13px;">
                                            <i class="fas fa-file-lines text-primary"></i>
                                            <span class="fw-medium text-dark">Laporan Operasional #{{ $report->id }}</span>
                                            <span style="color: #cbd5e1;">|</span>
                                            <span>{{ \Carbon\Carbon::parse($report->report_date)->translatedFormat('l, d F Y') }}</span>
                                        </div>

                                        <!-- Info User Pembuat -->
                                        <div class="d-flex align-items-center gap-2 mt-1" style="font-size: 12px; color: #64748b;">
                                            <i class="fas fa-user-circle"></i>
                                            <span>Dibuat oleh: <strong>{{ $report->creator->name ?? 'Unknown' }}</strong></span>
                                        </div>
                                    </div>

                                    <!-- Waktu Penyerahan -->
                                    <div class="text-end">
                                        <div style="background-color: #f8fafc; padding: 6px 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                                            <span class="fw-bold" style="font-size: 12px; color: #475569; display: block;">Diserahkan</span>
                                            <span style="font-size: 12px; color: #64748b;">
                                                <i class="far fa-clock me-1"></i> {{ \Carbon\Carbon::parse($report->received_at)->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- CUSTOM ACTION BUTTONS -->
                                <div class="btn-action-group">

                                    <!-- 1. Tombol Review (Preview) -->
                                    <a href="{{ route('admin.report.view', $report->id) }}" target="_blank" class="btn-review-custom" title="Lihat detail dokumen PDF">
                                        <i class="fas fa-eye text-secondary"></i> Review Dokumen
                                    </a>

                                    <!-- 2. Tombol Tanda Tangan (Trigger Modal) -->
                                    <!-- Perhatikan atribut data- yang dikirim ke JS -->
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
                        <div class="empty-state text-center w-100 py-5" style="background-color: #f8fafc; border-radius: 16px; border: 2px dashed #e2e8f0;">
                            <div style="background-color: #dcfce7; width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto;">
                                <i class="fas fa-clipboard-check" style="font-size: 32px; color: #16a34a;"></i>
                            </div>
                            <h5 style="color: #334155; font-weight: 700; margin-bottom: 8px;">Semua Bersih!</h5>
                            <p style="font-size: 14px; color: #64748b; max-width: 300px; margin: 0 auto;">
                                Tidak ada dokumen yang menunggu persetujuan Anda saat ini. Kerja bagus!
                            </p>
                        </div>
                    @endforelse

                </div>
            </div>
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
        // Ambil semua tombol trigger
        const triggers = document.querySelectorAll('.trigger-approve-modal');
        const modalEl = document.getElementById('approveModal');
        const approveModal = new bootstrap.Modal(modalEl);

        // Element di dalam modal
        const form = document.getElementById('approveForm');
        const idEl = document.getElementById('modal-doc-id');
        const groupEl = document.getElementById('modal-doc-group');
        const shiftEl = document.getElementById('modal-doc-shift');
        const dateEl = document.getElementById('modal-doc-date');

        triggers.forEach(btn => {
            btn.addEventListener('click', function() {
                // Ambil data dari atribut tombol
                const id = this.getAttribute('data-id');
                const group = this.getAttribute('data-group');
                const shift = this.getAttribute('data-shift');
                const date = this.getAttribute('data-date');
                const url = this.getAttribute('data-url');

                // Isi data ke dalam modal
                idEl.textContent = '#' + id;
                groupEl.textContent = 'Group ' + group;
                shiftEl.textContent = shift;
                dateEl.textContent = date;

                // Update action form
                form.action = url;

                // Tampilkan modal
                approveModal.show();
            });
        });
    });
</script>
@endpush
