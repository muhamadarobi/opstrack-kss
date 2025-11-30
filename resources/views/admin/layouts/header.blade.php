<div class="header d-flex justify-content-between align-items-center align-self-stretch">
    <div class="left-header d-flex align-items-center" style="gap: 22px;">
        <!-- Tombol Trigger Sidebar -->
        <button class="btn-close-sidebar d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="4" height="8" viewBox="0 0 4 8" fill="none">
                <path d="M1.53942 4.1888C1.51662 4.16403 1.49853 4.1346 1.48619 4.1022C1.47385 4.06981 1.4675 4.03508 1.4675 4C1.4675 3.96492 1.47385 3.93019 1.48619 3.8978C1.49853 3.8654 1.51662 3.83597 1.53942 3.8112L3.78476 1.36591C3.92253 1.21591 3.99995 1.01243 4 0.800243C4.00005 0.588055 3.92271 0.384538 3.785 0.234464C3.64729 0.084389 3.46049 5.00327e-05 3.2657 2.22536e-08C3.07091 -4.99882e-05 2.88407 0.0841932 2.7463 0.234197L0.50096 2.68002C0.180153 3.03044 0 3.50512 0 4C0 4.49488 0.180153 4.96956 0.50096 5.31998L2.7463 7.7658C2.88407 7.91581 3.07091 8.00005 3.2657 8C3.46049 7.99995 3.64729 7.91561 3.785 7.76554C3.92271 7.61546 4.00005 7.41195 4 7.19976C3.99995 6.98757 3.92253 6.78409 3.78476 6.63409L1.53942 4.1888Z" fill="#374957"/>
            </svg>
        </button>

        <div class="akun-title d-flex flex-column align-items-start" style="gap: 2px;">
            {{-- LOGIKA GREETING SESUAI WAKTU (WITA / GMT+8) --}}
            @php
                // Mengambil jam saat ini berdasarkan zona waktu Asia/Makassar (WITA)
                $hour = \Carbon\Carbon::now('Asia/Makassar')->format('H');

                $greeting = '';
                if ($hour >= 5 && $hour < 11) {
                    $greeting = 'Selamat Pagi';
                } elseif ($hour >= 11 && $hour < 15) {
                    $greeting = 'Selamat Siang';
                } elseif ($hour >= 15 && $hour < 18) {
                    $greeting = 'Selamat Sore';
                } else {
                    $greeting = 'Selamat Malam';
                }
            @endphp

            {{-- Menampilkan Greeting dan Nama User --}}
            <span class="nama">{{ $greeting }}, {{ Auth::user()->name ?? 'Admin' }}</span>

            {{-- Menampilkan Role --}}
            <span class="title">{{ ucfirst(Auth::user()->role->name ?? 'Administrator') }}</span>
        </div>
    </div>
    <div class="right-header d-flex justify-content-end align-items-center" style="gap: 30px;">

        <!-- Toggle Dark Mode (Menggantikan Tombol Notif) -->
        <div class="theme-switch-wrapper">
            <label class="theme-switch" for="checkbox">
                <input type="checkbox" id="checkbox" />
                <div class="slider round">
                    <i class="fas fa-moon"></i>
                    <i class="fas fa-sun"></i>
                </div>
            </label>
        </div>

        <!-- Image User dan Garis Pemisah dihapus sesuai request -->
    </div>
</div>
