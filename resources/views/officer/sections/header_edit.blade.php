<div class="content-header d-flex justify-content-between align-items-start align-self-stretch">
    <div class="title-content d-flex flex-column align-items-start" style="gap: 6px;">
        {{-- Judul Dinamis: Menggunakan variabel $title, default ke 'Form Laporan Shift Harian' --}}
        <span class="title-text align-self-stretch" style="font-size: 20px; font-weight: 600;">
            Edit Laporan Shift Harian
        </span>

        {{-- ID Laporan Dinamis: Menampilkan ID jika ada, atau '-' jika baru --}}
        <span class="id-laporan" style="font-size: 14px; font-weight: 400;">
            ID Laporan: {{ isset($report->id) ? '#' . $report->id : '-' }}
        </span>
    </div>
    <div class="tab-content d-flex justify-content-end align-items-start align-content-center flex-wrap"
         style="gap: 10px; flex: 1 0 0;">
        <a class="tab active d-flex justify-content-center align-items-center" onclick="showSection('section-info-umum', this)">Info Umum</a>
        <a class="tab d-flex justify-content-center align-items-center" onclick="showSection('section-muat-kantong', this)">Muat Kantong</a>
        <a class="tab d-flex justify-content-center align-items-center" onclick="showSection('section-muat-urea', this)">Muat Urea</a>
        <a class="tab d-flex justify-content-center align-items-center" onclick="showSection('section-bongkar', this)">Bongkar</a>
        <a class="tab d-flex justify-content-center align-items-center" onclick="showSection('section-gudang-turba', this)">Gudang Turba</a>
        <a class="tab d-flex justify-content-center align-items-center" onclick="showSection('section-gudang-cek-unit', this)">Gudang Cek Unit</a>
        <a class="tab d-flex justify-content-center align-items-center" onclick="showSection('section-gudang-karyawan', this)">Gudang Karyawan</a>
    </div>
</div>
