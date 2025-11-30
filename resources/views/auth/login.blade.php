{{-- PERHATIAN: Pastikan 'layouts.app' atau 'layouts.auth' adalah file layout Anda yang benar --}}
{{-- Sebuah file tidak bisa @extends ke dirinya sendiri ('auth.login') --}}
@extends('auth.master') {{-- Saya asumsikan ini nama layout Anda --}}

@section('content')

<style>
    /* Ini adalah style untuk pesan "melayang" (toast)
      Pesan ini akan muncul di atas, tengah, dan hilang setelah 5 detik
    */
    .toast-message {
        position: fixed; /* Tetap di layar */
        top: 20px; /* Jarak 20px dari atas */
        left: 50%; /* Posisikan di tengah secara horizontal */
        transform: translateX(-50%); /* Sempurnakan posisi tengah */
        z-index: 9999; /* Tampil di atas segalanya */

        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 500;

        /* Warna (Bootstrap Danger/Merah) */
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;

        box-shadow: 0 5px 15px rgba(0,0,0,0.1);

        /* Animasi untuk muncul dan hilang */
        opacity: 0; /* Mulai transparan */
        animation: fadeInOut 5s ease-in-out forwards;
    }

    /* Keyframes untuk animasi */
    @keyframes fadeInOut {
        0%   { opacity: 0; top: 0; } /* Mulai: transparan, di atas layar */
        15%  { opacity: 1; top: 20px; } /* Muncul & turun ke 20px */
        85%  { opacity: 1; top: 20px; } /* Diam selama beberapa detik */
        100% { opacity: 0; top: 0; } /* Hilang & naik kembali */
    }
</style>
{{-- === AKHIR CSS BARU === --}}


{{-- === MODIFIKASI BLOK SESSION 'error' === --}}
{{-- Blok ini sekarang menggunakan class 'toast-message' baru --}}
@if (session('error'))
    <div class="toast-message" role="alert">
        {{ session('error') }}
    </div>
@endif
{{-- === AKHIR MODIFIKASI === --}}

    <div class="container-login">
        <img class="image-login" src="{{ asset('assets/KSS.png')}}" alt="">
        <div class="box-login d-flex flex-column align-items-center">
            <span class="title-login" style="font-size: 24px;">Masukkan Data Akun</span>
            <span>Sistem Manajemen Dokumen Operasional</span>
        </div>

        <!-- Tampilkan Error Jika Login Gagal -->
        @if ($errors->any())
            <div class="alert alert-danger" style="width: 100%; max-width: 400px; padding: 10px; border-radius: 8px; text-align: center;">
                @error('username')
                    <span>{{ $message }}</span>
                @else
                    <span>Gagal melakukan login.</span>
                @enderror
            </div>
        @endif


        {{-- FORM YANG DIPERBAIKI: action, method, dan @csrf --}}
        <form action="{{ route('login.authenticate') }}" method="POST" class="form-login d-flex flex-column align-items-start align-self-stretch" style="gap: 20px;" data-turbo="false">
            @csrf {{-- Token Wajib untuk keamanan Laravel --}}

            <div class="login-input d-flex flex-column align-items-start align-self-stretch" style="gap: 8px;">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" required>
            </div>

            <!-- MODIFIKASI BAGIAN PASSWORD -->
            <div class="login-input d-flex flex-column align-items-start align-self-stretch" style="gap: 8px;">
                <label for="password">Password</label>
                <!-- Wrapper baru untuk input dan ikon -->
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required>
                    <!-- Ikon toggle -->
                    <span id="togglePassword">
                        <i class="bi bi-eye-slash"></i>
                    </span>
                </div>
            </div>
            <!-- AKHIR MODIFIKASI -->

            {{-- Input Role yang dikomentari (sudah benar, karena login tidak butuh role) --}}
            {{-- ... --}}

            <!-- Tambahan: Checkbox "Remember Me" -->
            <div class="form-check remember-me" style="margin-left: 5px">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">
                    Ingat Saya
                </label>
            </div>
            <button type="submit" class="btn-login">Masuk</button>
        </form>
    </div>
@endsection

<!-- SCRIPT BARU UNTUK TOGGLE PASSWORD (TURBO-SAFE) -->
@push('scripts')
    <script>
        // Gunakan Event Delegation, yang Turbo-safe
        // Listener ini ditambahkan ke 'document' yang selalu ada
        document.addEventListener('click', function (event) {

            // Cari elemen #togglePassword atau salah satu anaknya yang diklik
            const togglePassword = event.target.closest('#togglePassword');

            // Jika yang diklik bukan tombol toggle, abaikan
            if (!togglePassword) {
                return;
            }

            // Temukan elemen terkait
            // Kita gunakan querySelector di dalam 'password-wrapper' terdekat untuk keamanan
            const wrapper = togglePassword.closest('.password-wrapper');
            if (!wrapper) return;

            const passwordInput = wrapper.querySelector('#password');
            const icon = togglePassword.querySelector('i');

            // Jika elemen tidak ditemukan, hentikan
            if (!passwordInput || !icon) {
                return;
            }

            // Cek tipe input saat ini
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Ganti ikon mata
            if (type === 'password') {
                // Jika password, ikon mata tertutup
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                // Jika teks, ikon mata terbuka
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    </script>

    
@endpush
