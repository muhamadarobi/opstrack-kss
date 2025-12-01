<!-- NAVBAR DENGAN TOGGLE & LOGOUT -->
<div class="navbar d-flex justify-content-between align-items-center align-self-stretch">
    <!-- Kiri: Logo & Info User -->
    <div class="navbar-left d-flex align-items-center">
        <a href="{{ route('reports.history') }}">
            <img src="{{ asset('assets/KSS.png') }}" alt="KSS" style="height: 35px;">
        </a>
        <div class="akun-title d-flex flex-column align-items-start">
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

            {{-- Mengambil Nama User yang sedang Login --}}
            <span class="nama">{{ $greeting }}, {{ Auth::user()->name ?? 'User' }}</span>

            {{-- Mengambil Role User --}}
            <span class="title">{{ ucfirst(Auth::user()->role->name ?? 'Pengguna') }}</span>
        </div>
    </div>

    <!-- Kanan: Toggle Theme, Divider, Logout -->
    <div class="navbar-right d-flex align-items-center">
        <div class="theme-switch-wrapper">
            <label class="theme-switch" for="checkbox">
                <input type="checkbox" id="checkbox" />
                <div class="slider round">
                    <i class="fa-solid fa-moon"></i>
                    <i class="fa-solid fa-sun"></i>
                </div>
            </label>
        </div>

        <!-- Pembatas Garis -->
        <div class="navbar-divider"></div>

        <!-- Tombol Logout -->
        {{-- 1. Link memanggil fungsi submit pada form di bawah --}}
        <a href="{{ route('logout') }}"
           class="btn-logout"
           onclick="event.preventDefault(); document.getElementById('navbar-logout-form').submit();">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
            Logout
        </a>

        {{-- 2. Form Hidden khusus Navbar --}}
        <form id="navbar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none" style="display: none;">
            @csrf
        </form>
    </div>
</div>
