@extends('admin.layouts.master')

@section('title','Dokumen Operasional')

{{-- Tambahkan Style Khusus untuk Hover Dark Mode --}}
@push('styles')
<style>
    /* FIX: Hover Table Dark Mode - Memastikan teks terlihat */
    [data-theme="dark"] .table-hover tbody tr:hover > * {
        background-color: rgba(255, 255, 255, 0.08) !important;
        color: #ffffff !important;
        --bs-table-accent-bg: rgba(255, 255, 255, 0.08) !important;
        box-shadow: inset 0 0 0 9999px rgba(255, 255, 255, 0.08) !important;
    }

    [data-theme="dark"] .table-hover tbody tr:hover .text-muted {
        color: #e0e0e0 !important;
    }

    /* PENGATURAN LEBAR KOLOM */

    /* Kolom No */
    .col-no { width: 50px; text-align: center; }

    /* Kolom ID & Shift - Max Width diterapkan agar tetap kecil */
    .col-id {
        width: 80px;
        max-width: 80px;
    }
    .col-shift {
        width: 80px;
        max-width: 80px;
    }

    /* Kolom Waktu */
    .col-waktu {
        min-width: 150px;
    }

    /* Style Khusus Font Tanggal yang Lebih Kecil */
    .date-text {
        font-weight: 500;
        font-size: 0.85rem; /* Ukuran font diperkecil */
        line-height: 1.2;
    }
    .time-text {
        font-size: 0.75rem; /* Jam lebih kecil lagi */
    }

    /* Kolom Aksi */
    .col-aksi { min-width: 120px; text-align: center; }

    /* Style Tambahan untuk Tombol di Modal */
    .btn-confirm-delete {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn-confirm-delete:hover {
        background-color: #c82333;
    }
    .btn-cancel-modal {
        background-color: var(--input-bg, #f3f4f6);
        color: var(--black-color, #333);
        border: 1px solid var(--gray-border, #ccc);
        padding: 8px 20px;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s;
    }
</style>
@endpush

@section('content')
@include('admin.style.dokumenstyle')
        <!-- Content -->
        <div class="content-page d-flex flex-column align-items-center justify-content-center align-self-stretch" style="padding: 0px 25px 25px 25px; gap: 20px;">
            <div class="header-content align-self-stretch">
                <h1 class="title-page">Manajemen Dokumen</h1>
            </div>

            <div class="data-content-wrapper d-flex flex-column align-items-start align-self-stretch" style="width: 100%; gap: 20px;">

                <!-- FILTER SECTION -->
                <form action="{{ route('admin.dokumen') }}" method="GET" class="box-filter d-flex align-items-end align-self-stretch" style="gap: 20px; flex-wrap: wrap; width: 100%;">
                    <div class="filters d-flex align-items-end" style="gap: 20px; flex: 1 0 0; flex-wrap: wrap;">
                        <!-- Cari -->
                        <div class="filter d-flex flex-column align-items-start" style="gap: 5px; flex: 1; min-width: 200px;">
                            <label for="cari">Cari Dokumen</label>
                            <input type="search" name="cari" id="cari" value="{{ request('cari') }}" placeholder="ID Laporan / Nama Petugas...">
                        </div>

                        <!-- Jenis Dokumen -->
                        <div class="filter d-flex flex-column align-items-start" style="gap: 5px; flex: 1; min-width: 200px;">
                            <label for="docType">Jenis Dokumen</label>
                            <select name="docType" id="docType">
                                <option value="" selected>Semua Jenis</option>
                                <option value="Laporan" {{ request('docType') == 'Laporan' ? 'selected' : '' }}>Laporan Harian</option>
                            </select>
                        </div>

                        <!-- Tanggal -->
                        <div class="filter d-flex flex-column align-items-start" style="gap: 5px; flex: 1; min-width: 200px;">
                            <label for="tanggal">Tanggal Laporan</label>
                            <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal') }}">
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex align-items-center" style="gap: 10px; height: 42px;">
                            <button class="submit-filter" type="submit">Terapkan</button>
                            <a href="{{ route('admin.dokumen') }}" class="btn-clear" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-times me-2"></i> Clear
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Alert Section -->
                @if(session('success'))
                <div class="alert alert-success w-100 alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger w-100 alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- TABLE SECTION -->
                <div class="document-table d-flex flex-column align-items-start align-self-stretch">
                    <div class="box-title d-flex flex-column align-items-start align-self-stretch">
                        <span class="title-table">Daftar Dokumen ({{ $reports->total() }})</span>
                    </div>

                    <div class="table-responsive w-100">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th class="col-no">No</th>
                                    <th class="col-jenis">Jenis Dokumen</th>
                                    <th class="col-id">ID</th>
                                    <th class="col-pengunggah">Petugas (Group)</th>
                                    <th class="col-waktu">Tanggal</th>
                                    <th class="col-shift">Shift</th>
                                    <th class="col-aksi">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $index => $report)
                                <tr class="body">
                                    <td class="col-no">{{ $reports->firstItem() + $index }}</td>
                                    <td class="col-jenis">
                                        Laporan Harian
                                        @if($report->created_at->diffInHours(now()) < 24)
                                            <span class="badge-baru-table">Baru</span>
                                        @endif
                                    </td>
                                    <td class="col-id">#{{ $report->id }}</td>
                                    <td class="col-pengunggah">
                                        {{-- Tampilan Normal --}}
                                        @if($report->user)
                                            {{ $report->user->name }}
                                            <span class="text-muted small">({{ $report->group_name }})</span>
                                        @else
                                            Group {{ $report->group_name }}
                                        @endif
                                    </td>
                                    <td class="col-waktu">
                                        <div class="d-flex flex-column">
                                            {{-- TANGGAL DIPERKECIL FONTNYA --}}
                                            <span class="date-text">
                                                {{ \Carbon\Carbon::parse($report->report_date)->locale('id')->translatedFormat('l, d M Y') }}
                                            </span>
                                            <span class="text-muted small time-text">
                                                {{ $report->created_at->format('H:i') }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="col-shift">
                                        @php
                                            $shiftColor = 'secondary';
                                            $shiftLower = strtolower($report->shift);
                                            if($shiftLower == 'pagi') { $shiftColor = 'success'; }
                                            elseif($shiftLower == 'sore') { $shiftColor = 'warning'; }
                                            elseif($shiftLower == 'malam') { $shiftColor = 'primary'; }
                                        @endphp
                                        <span class="badge bg-{{ $shiftColor }} text-white" style="font-weight: 500; padding: 4px 8px; font-size: 12px;">
                                            {{ ucfirst($report->shift) }}
                                        </span>
                                    </td>
                                    <td class="col-aksi">
                                        <div class="d-flex justify-content-center gap-2">
                                            <!-- Lihat PDF -->
                                            <a href="{{ route('admin.report.view', $report->id) }}" target="_blank" class="btn-aksi" title="Lihat PDF">
                                                <i class="fas fa-eye" style="color: var(--blue-kss);"></i>
                                            </a>

                                            <!-- Download -->
                                            <a href="{{ route('admin.report.view', $report->id) }}" download="Laporan-{{$report->id}}.pdf" class="btn-aksi" title="Download" style="color: var(--orange-kss);">
                                                <i class="fas fa-download"></i>
                                            </a>

                                            <!-- Tombol Trigger Modal Hapus -->
                                            <button type="button" class="btn-aksi" title="Hapus" style="border: none; background: none; padding: 0;"
                                                onclick="confirmDelete('{{ route('admin.dokumen.destroy', $report->id) }}')"
                                                data-bs-toggle="modal" data-bs-target="#modalDelete">
                                                <i class="fas fa-trash" style="color: #dc3545;"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center p-4">Tidak ada dokumen ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div style="width: 100%; padding: 20px;">
                        {{ $reports->links('pagination::bootstrap-5') }}
                    </div>
                </div>

            </div>
        </div>

        @endsection
    @push('modal')

    <!-- MODAL HAPUS (DELETE CONFIRMATION) - DARI MASTER DATA -->
    <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeleteLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Apakah Anda yakin ingin menghapus dokumen ini? <br> <span class="text-danger small">Tindakan ini tidak dapat dibatalkan.</span></p>
                    <form id="formDelete" method="POST" class="d-flex gap-2 justify-content-center">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-confirm-delete">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endpush

@push('scripts')
<script>
    // Logic untuk mengisi action form pada modal hapus
    function confirmDelete(url) {
        document.getElementById('formDelete').action = url;
    }
</script>
@endpush
