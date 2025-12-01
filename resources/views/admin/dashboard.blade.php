@extends('admin.layouts.master')

@section('title', 'Dashboard Operasional')

@section('content')
    <div class="content-page d-flex flex-column align-items-center justify-content-center align-self-stretch" style="padding: 0px 25px 25px 25px; gap: 10px;">

        <h1 class="title-page align-self-stretch">Dashboard Dokumen Masuk</h1>

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
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="19" viewBox="0 0 16 19" fill="none">
                            <path d="M9.6 5.54167V0.364167C10.34 0.640724 11.0122 1.06979 11.572 1.62292L14.3592 4.38267C14.9188 4.93595 15.3527 5.60096 15.632 6.33333H10.4C10.1878 6.33333 9.98434 6.24993 9.83432 6.10146C9.68429 5.95299 9.6 5.75163 9.6 5.54167ZM16 8.30062V15.0417C15.9987 16.0911 15.5769 17.0972 14.827 17.8392C14.0772 18.5813 13.0605 18.9987 12 19H4C2.93952 18.9987 1.92285 18.5813 1.17298 17.8392C0.423106 17.0972 0.00127029 16.0911 0 15.0417V3.95833C0.00127029 2.9089 0.423106 1.90282 1.17298 1.16076C1.92285 0.418698 2.93952 0.00125705 4 0L7.612 0C7.7424 0 7.8712 0.0102917 8 0.019V5.54167C8 6.17156 8.25286 6.77565 8.70294 7.22105C9.15303 7.66644 9.76348 7.91667 10.4 7.91667H15.9808C15.9896 8.04413 16 8.17158 16 8.30062ZM9.6 15.0417C9.6 14.8317 9.51571 14.6303 9.36569 14.4819C9.21566 14.3334 9.01217 14.25 8.8 14.25H4.8C4.58783 14.25 4.38434 14.3334 4.23431 14.4819C4.08429 14.6303 4 14.8317 4 15.0417C4 15.2516 4.08429 15.453 4.23431 15.6015C4.38434 15.7499 4.58783 15.8333 4.8 15.8333H8.8C9.01217 15.8333 9.21566 15.7499 9.36569 15.6015C9.51571 15.453 9.6 15.2516 9.6 15.0417ZM12 11.875C12 11.665 11.9157 11.4637 11.7657 11.3152C11.6157 11.1667 11.4122 11.0833 11.2 11.0833H4.8C4.58783 11.0833 4.38434 11.1667 4.23431 11.3152C4.08429 11.4637 4 11.665 4 11.875C4 12.085 4.08429 12.2863 4.23431 12.4348C4.38434 12.5833 4.58783 12.6667 4.8 12.6667H11.2C11.4122 12.6667 11.6157 12.5833 11.7657 12.4348C11.9157 12.2863 12 12.085 12 11.875Z" fill="#0077C2"/>
                        </svg>
                    </div>
                </div>

                <!-- Kartu 2: Dokumen Hari Ini -->
                <div class="card">
                    <div class="card-info">
                        <span class="number">{{ $todayDocs }}</span>
                        <span class="card-title">Dokumen Hari Ini</span>
                    </div>
                    <div class="card-icon color-green">
                         <!-- Icon Calendar Check (Bootstrap Icons / FontAwesome equivalent) -->
                         <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#198754" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                            <path d="M9 16l2 2 4-4"></path>
                        </svg>
                    </div>
                </div>

                <!-- Kartu 3: Dokumen Bulan Ini -->
                <div class="card">
                    <div class="card-info">
                        <span class="number">{{ $monthlyDocs }}</span>
                        <span class="card-title">Dokumen Bulan Ini</span>
                    </div>
                    <div class="card-icon color-red">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none">
                            <path d="M19 7.91699V15.042C18.9987 16.0913 18.5808 17.0969 17.8389 17.8389C17.0969 18.5809 16.0913 18.9987 15.042 19H3.95801C2.9087 18.9987 1.90312 18.5808 1.16113 17.8389C0.419158 17.0969 0.00131754 16.0913 0 15.042V7.91699H19ZM5.54199 11.4795C5.22707 11.4795 4.92484 11.6045 4.70215 11.8271C4.47945 12.0498 4.35449 12.352 4.35449 12.667C4.35454 12.9018 4.42427 13.131 4.55469 13.3262C4.6851 13.5214 4.87007 13.6738 5.08691 13.7637C5.3039 13.8536 5.54309 13.8769 5.77344 13.8311C6.00372 13.7852 6.2158 13.6729 6.38184 13.5068C6.54787 13.3408 6.66023 13.1287 6.70605 12.8984C6.75187 12.6681 6.72855 12.4289 6.63867 12.2119C6.54877 11.9951 6.39634 11.8101 6.20117 11.6797C6.00598 11.5493 5.77673 11.4796 5.54199 11.4795ZM9.5 11.4795C9.18506 11.4795 8.88286 11.6044 8.66016 11.8271C8.43753 12.0498 8.3125 12.3521 8.3125 12.667C8.31254 12.9017 8.38234 13.131 8.5127 13.3262C8.64318 13.5215 8.82891 13.6738 9.0459 13.7637C9.26278 13.8535 9.50121 13.8768 9.73145 13.8311C9.96174 13.7852 10.1738 13.6728 10.3398 13.5068C10.5059 13.3408 10.6192 13.1288 10.665 12.8984C10.7108 12.6681 10.6866 12.4289 10.5967 12.2119C10.5068 11.9953 10.3551 11.8101 10.1602 11.6797C9.96487 11.5492 9.73487 11.4795 9.5 11.4795ZM13.458 11.4795C13.1432 11.4796 12.8408 11.6045 12.6182 11.8271C12.3956 12.0498 12.2705 12.3522 12.2705 12.667C12.2706 12.9016 12.3404 13.131 12.4707 13.3262C12.6012 13.5214 12.7869 13.6738 13.0039 13.7637C13.2209 13.8536 13.4601 13.8769 13.6904 13.8311C13.9205 13.7852 14.1319 13.6726 14.2979 13.5068C14.4639 13.3408 14.5772 13.1288 14.623 12.8984C14.6689 12.6681 14.6455 12.4289 14.5557 12.2119C14.4658 11.9951 14.3133 11.8101 14.1182 11.6797C13.9229 11.5492 13.6929 11.4795 13.458 11.4795ZM13.458 0C13.668 0 13.8701 0.0829793 14.0186 0.231445C14.167 0.379912 14.25 0.582029 14.25 0.791992V1.58301H15.042C16.0912 1.58435 17.0969 2.00226 17.8389 2.74414C18.5809 3.4862 18.9987 4.49256 19 5.54199V6.33301H0V5.54199C0.00125705 4.49256 0.419074 3.4862 1.16113 2.74414C1.9031 2.00226 2.90877 1.58435 3.95801 1.58301H4.75V0.791992C4.75 0.582029 4.83298 0.379912 4.98145 0.231445C5.12991 0.082979 5.33203 0 5.54199 0C5.75176 8.60925e-05 5.95319 0.0831574 6.10156 0.231445C6.25003 0.379912 6.33301 0.582029 6.33301 0.791992V1.58301H12.667V0.791992C12.667 0.582029 12.75 0.379912 12.8984 0.231445C13.0468 0.0831576 13.2482 8.62186e-05 13.458 0Z" fill="#D20000"/>
                        </svg>
                    </div>
                </div>

                <!-- Kartu 4: Petugas Lapangan -->
                <div class="card">
                    <div class="card-info">
                        <span class="number">{{ $totalPetugas }}</span>
                        <span class="card-title">Petugas Lapangan</span>
                    </div>
                    <div class="card-icon color-orange">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="19" viewBox="0 0 15 19" fill="none">
                            <path d="M7.125 11.083C11.0584 11.0873 14.2466 14.2755 14.251 18.209C14.2508 18.6461 13.8961 18.9999 13.459 19H0.791992C0.354848 19 0.00015612 18.6461 0 18.209C0.00437898 14.2756 3.19175 11.0875 7.125 11.083ZM7.125 0C9.74836 0 11.8758 2.12662 11.876 4.75C11.876 7.37351 9.74846 9.50098 7.125 9.50098C4.50168 9.50081 2.375 7.37341 2.375 4.75C2.37516 2.12672 4.50178 0.000166337 7.125 0Z" fill="#F39C12"/>
                        </svg>
                    </div>
                </div>

            </div>

            <!-- BAGIAN NOTIFIKASI / DOKUMEN TERBARU -->
            <div class="dashboard-notif d-flex flex-column align-items-start align-self-stretch">
                <span class="notif-title align-self-stretch" style="font-size: 14px; font-weight: 600;">Notifikasi Terbaru</span>

                <div class="notif-list d-flex flex-column align-items-start align-self-stretch" style="gap: 20px;">

                    @forelse($recentReports as $report)
                        <div class="notif-item">
                            <div class="detail-notif d-flex flex-column align-items-start" style="gap: 15px;">
                                <div class="info-notif d-flex flex-column align-items-start align-self-stretch" style="gap: 2px;">
                                    <div class="document-notif d-flex align-items-center" style="gap: 6px; font-size: 14px;">
                                        <!-- MODIFIKASI: Menampilkan Group Name alih-alih User Name -->
                                        <span class="nama fw-bold">Group {{ $report->group_name }}</span>
                                        <span>Mengunggah</span>
                                        <span class="document-name fw-bold" style="color: var(--blue-kss);">Laporan Harian Shift</span>

                                        <!-- BADGE BARU 8 JAM -->
                                        @if($report->created_at->diffInHours(now()) < 8)
                                            <span class="badge bg-danger" style="font-size: 9px; vertical-align: middle;">Baru</span>
                                        @endif
                                    </div>
                                    <span class="doc-keterangan" style="font-size: 13px; font-weight: 300;">
                                        Tanggal: {{ \Carbon\Carbon::parse($report->report_date)->translatedFormat('d F Y') }} || Shift: {{ $report->shift }}
                                    </span>
                                </div>
                                <div class="action d-flex align-items-center" style="gap: 15px;">
                                    <!-- Link ke route admin.report.view -->
                                    <a href="{{ route('admin.report.view', $report->id) }}" target="_blank" class="see-doc d-flex align-items-center" style="text-decoration: none;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="10" viewBox="0 0 13 10" fill="none">
                                            <path d="M6.42273 0C9.73554 7.39256e-05 11.6232 2.26765 12.453 3.61914C12.9761 4.46537 12.9761 5.53462 12.453 6.38086C11.6232 7.73235 9.73554 9.99993 6.42273 10C3.10988 10 1.22231 7.73238 0.392456 6.38086C-0.130754 5.53456 -0.130754 4.46541 0.392456 3.61914C1.22229 2.26763 3.10982 0 6.42273 0ZM6.42273 1.79004C4.6498 1.79004 3.21277 3.22705 3.21277 5C3.2128 6.77293 4.64982 8.20996 6.42273 8.20996C8.19483 8.20809 9.63088 6.77214 9.63269 5C9.63269 3.2271 8.19558 1.79013 6.42273 1.79004ZM6.42273 2.85938C7.6046 2.85947 8.56238 3.81809 8.56238 5C8.56224 6.18179 7.60451 7.13955 6.42273 7.13965C5.24086 7.13965 4.28224 6.18185 4.2821 5C4.2821 3.81803 5.24078 2.85938 6.42273 2.85938Z" fill="#0077C2"/>
                                        </svg>
                                        Lihat Dokumen
                                    </a>
                                    <a href="{{ route('admin.report.view', $report->id) }}" target="_blank" class="download d-flex align-items-center" style="text-decoration: none;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" fill="none">
                                            <path d="M9.375 6.04199C9.72015 6.04203 10 6.32182 10 6.66699V8.58008C9.99882 9.36394 9.36304 9.99908 8.5791 10H1.41992C0.636036 9.99885 0.000917802 9.36306 0 8.5791V6.66699C0 6.32182 0.279824 6.04199 0.625 6.04199C0.970148 6.04203 1.25 6.32184 1.25 6.66699V8.5791C1.25021 8.67298 1.32609 8.74954 1.41992 8.75H8.5791C8.67313 8.74979 8.74977 8.67313 8.75 8.5791V6.66699C8.75 6.32182 9.02982 6.04199 9.375 6.04199ZM5 0C5.34511 7.27429e-05 5.625 0.279869 5.625 0.625L5.63477 6.3125L6.80957 5.1377C7.05785 4.89791 7.45356 4.90505 7.69336 5.15332C7.93285 5.40162 7.92591 5.79741 7.67773 6.03711L6.33984 7.375C5.6076 8.10723 4.41973 8.10725 3.6875 7.375L2.34961 6.03613C2.11568 5.79393 2.11568 5.41018 2.34961 5.16797C2.58942 4.9197 2.98512 4.91254 3.2334 5.15234L4.38379 6.30273L4.375 0.625C4.375 0.279824 4.65482 0 5 0Z" fill="#333333"/>
                                        </svg>
                                        Download
                                    </a>
                                </div>
                            </div>
                            <span class="time-notif fw-light" style="font-size: 12px; color: rgba(17, 17, 17);">{{ $report->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="empty-state text-center w-100 py-4">
                            <p style="color: var(--text-muted); font-size: 14px;">Belum ada dokumen masuk hari ini.</p>
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
@endsection
