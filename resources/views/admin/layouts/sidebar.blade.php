<div class="sidebar d-flex flex-column justify-content-between align-items-start align-self-stretch">
    <div class="sidebar-top d-flex flex-column align-items-start" style="gap: 40px; width: 100%;">
        {{-- ... Bagian atas sidebar (Logo & Menu) tetap sama ... --}}
        <div class="logo d-flex align-items-start" style="gap: 5px">
            <img src="{{ asset('assets/Logo.png') }}" alt="logo" title="Logo KSS" style="width: 46px; height: 30px;">
            <img src="{{ asset('assets/KSS-text.png') }}" alt="Kaltim Satria Samudera" title="Kaltim Satria Samudera" style="width: 75px; height: 30px;">
        </div>

        <div class="side-content d-flex flex-column align-items-start" style="gap: 5px; width: 100%;">
            <span class="menu-text align-self-stretch" style="font-size: 14px; font-weight: 900; color: var(--black-color);">MENU</span>

            <div class="sidebar-menu d-flex flex-column align-items-start" style="gap: 4px; width: 210px;">
                {{-- Link Menu Dashboard, Dokumen, Pengguna, Master Data tetap sama --}}
                <a class="menu d-flex align-items-center align-self-stretch"
                   id="{{ request()->routeIs('admin.dashboard') ? 'active' : 'non-active' }}"
                   href="{{ route('admin.dashboard') }}">
                    {{-- SVG Dashboard --}}
                    <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.4021 4.70923L9.2686 0.87097C8.01475 -0.290323 5.98527 -0.290323 4.73143 0.87097L0.597933 4.70923C0.213808 5.06401 -0.00149608 5.54674 7.82595e-06 6.04986V11.6713C0.00129298 12.405 0.641902 12.9994 1.43211 13H12.5679C13.3581 12.9994 13.9987 12.405 14 11.6713V6.04986C14.0015 5.54674 13.7862 5.06401 13.4021 4.70923ZM12.25 11.375H9.33335V9.6514C9.33335 8.50923 8.3362 7.5833 7.10617 7.5833H6.89385C5.66382 7.5833 4.66668 8.50923 4.66668 9.6514V11.375H1.75001V6.04986C1.7502 5.97803 1.78077 5.90917 1.83518 5.85811L5.96868 2.01985C6.53811 1.49093 7.46148 1.49083 8.03108 2.01959V2.01959L12.1648 5.85811C12.2192 5.90917 12.2498 5.97803 12.25 6.04986L12.25 11.375Z" fill="{{ request()->routeIs('admin.dashboard') ? '#F39C12' : '#111111' }}"/>
                    </svg>
                    <span class="text-sidebar">Dashboard</span>
                </a>

                <a class="menu d-flex align-items-center align-self-stretch"
                   id="{{ request()->routeIs('admin.dokumen') ? 'active' : 'non-active' }}"
                   href="{{ route('admin.dokumen') }}">
                    {{-- SVG Dokumen --}}
                   <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.7917 1.1818H7.28702C7.2399 1.18158 7.19353 1.17003 7.15169 1.14812L5.43376 0.236355C5.14124 0.0812408 4.81601 0.000138494 4.48583 0H3.20833C1.43722 0.00193892 0.00194141 1.45588 0 3.24999V9.75001C0.00194141 11.5441 1.43722 12.9981 3.20833 13H10.7917C12.5628 12.998 13.9981 11.5441 14 9.75001V4.43182C13.9981 2.63768 12.5628 1.18377 10.7917 1.1818ZM12.25 9.74998C12.25 10.5659 11.5971 11.2272 10.7917 11.2272H3.20833C2.40292 11.2273 1.75 10.5659 1.75 9.74998V4.11686H12.215C12.238 4.22023 12.2497 4.32585 12.25 4.43182V9.74998Z" fill="{{ request()->routeIs('admin.dokumen') ? '#F39C12' : '#111111' }}"/>
                    </svg>
                    <span class="text-sidebar">Manajemen Dokumen</span>
                </a>

                <a class="menu d-flex align-items-center align-self-stretch"
                   id="{{ request()->routeIs('admin.pengguna') ? 'active' : 'non-active' }}"
                   href="{{ route('admin.pengguna') }}">
                   {{-- SVG Pengguna --}}
                   <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.65674 8.16699C7.23691 8.16429 9.33065 10.2537 9.3335 12.834V13.125C9.3335 13.6083 8.94176 14 8.4585 14C7.97531 13.9999 7.5835 13.6082 7.5835 13.125V12.834C7.58341 12.7386 7.57923 12.6427 7.56982 12.5479C7.41058 10.9431 5.98026 9.77166 4.37549 9.93066C2.85507 10.1247 1.72418 11.4313 1.75049 12.9639V13.125C1.75049 13.6081 1.35857 13.9998 0.875488 14C0.392227 14 0.000488281 13.6083 0.000488281 13.125V12.999C-0.0325163 10.503 1.86112 8.4025 4.34717 8.17773C4.45009 8.17079 4.55354 8.1671 4.65674 8.16699ZM11.3755 4.08398C11.8587 4.08398 12.2505 4.47572 12.2505 4.95898V5.83398H13.1255C13.6087 5.83398 14.0005 6.22573 14.0005 6.70898C14.0005 7.19225 13.6087 7.58398 13.1255 7.58398H12.2505V8.45898C12.2505 8.94225 11.8587 9.33398 11.3755 9.33398C10.8923 9.33389 10.5005 8.94219 10.5005 8.45898V7.58398H9.62549C9.14228 7.58392 8.75049 7.19221 8.75049 6.70898C8.7505 6.22577 9.14229 5.83405 9.62549 5.83398H10.5005V4.95898C10.5005 4.47578 10.8923 4.08408 11.3755 4.08398ZM4.66748 0C6.60018 0.000234586 8.16725 1.56728 8.16748 3.5C8.16748 5.43292 6.60033 7.00074 4.66748 7.00098C2.73523 6.99904 1.16845 5.43227 1.1665 3.5C1.16673 1.56713 2.73458 0 4.66748 0ZM4.66748 1.75C3.7011 1.75 2.91673 2.53366 2.9165 3.5C2.9165 4.46653 3.70096 5.25098 4.66748 5.25098C5.6338 5.25074 6.41748 4.46639 6.41748 3.5C6.41725 2.53381 5.63366 1.75023 4.66748 1.75Z" fill="{{ request()->routeIs('admin.pengguna') ? '#F39C12' : '#111111' }}"/>
                    </svg>
                    <span class="text-sidebar">Manajemen Pengguna</span>
                </a>

                <a class="menu d-flex align-items-center align-self-stretch"
                   id="{{ request()->routeIs('admin.masterdata') ? 'active' : 'non-active' }}"
                   href="{{ route('admin.masterdata') }}">
                   {{-- SVG Master Data --}}
                   <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.5 3C13.5 3.82843 10.4772 4.5 6.75 4.5C3.02285 4.5 0 3.82843 0 3C0 2.17157 3.02285 1.5 6.75 1.5C10.4772 1.5 13.5 2.17157 13.5 3Z" fill="{{ request()->routeIs('admin.masterdata') ? '#F39C12' : '#F39C12' }}"/>
                        <path d="M0 5.25C0 6.07843 3.02285 6.75 6.75 6.75C10.4772 6.75 13.5 6.07843 13.5 5.25V7.5C13.5 8.32843 10.4772 9 6.75 9C3.02285 9 0 8.32843 0 7.5V5.25Z" fill="{{ request()->routeIs('admin.masterdata') ? '#F39C12' : '#F39C12' }}"/>
                        <path d="M0 9.75C0 10.5784 3.02285 11.25 6.75 11.25C10.4772 11.25 13.5 10.5784 13.5 9.75V12C13.5 12.8284 10.4772 13.5 6.75 13.5C3.02285 13.5 0 12.8284 0 12V9.75Z" fill="{{ request()->routeIs('admin.masterdata') ? '#F39C12' : '#F39C12' }}"/>
                    </svg>
                    <span class="text-sidebar">Master Data</span>
                </a>

            </div>
        </div>
    </div>

    {{-- ========== BAGIAN TOMBOL LOGOUT ========== --}}

    {{-- 1. Tombol yang terlihat --}}
    <a class="logout-button d-flex align-items-center align-self-stretch"
       href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4.33398 0C5.231 0.00101884 6.09129 0.351334 6.72559 0.974609C7.35996 1.59797 7.71683 2.44364 7.71777 3.3252V3.79199C7.71769 4.02379 7.62374 4.24617 7.45703 4.41016C7.29008 4.5742 7.06324 4.66694 6.82715 4.66699C6.59098 4.66699 6.36426 4.5742 6.19727 4.41016C6.03043 4.24615 5.93661 4.02388 5.93652 3.79199V3.3252C5.93652 2.90748 5.76739 2.50631 5.4668 2.21094C5.16632 1.91583 4.75882 1.75009 4.33398 1.75H3.38379C2.95869 1.75 2.55059 1.91557 2.25 2.21094C1.94942 2.50631 1.78125 2.90749 1.78125 3.3252V10.6748C1.78125 11.0925 1.94942 11.4937 2.25 11.7891C2.55059 12.0844 2.95869 12.25 3.38379 12.25H4.33398C4.75882 12.2499 5.16632 12.0842 5.4668 11.7891C5.76739 11.4937 5.93652 11.0925 5.93652 10.6748V10.208C5.93661 9.97612 6.03043 9.75385 6.19727 9.58984C6.36426 9.42575 6.59098 9.33301 6.82715 9.33301C7.06324 9.33306 7.29008 9.4258 7.45703 9.58984C7.62374 9.75383 7.71769 9.97621 7.71777 10.208V10.6748C7.71683 11.5564 7.35996 12.402 6.72559 13.0254C6.09129 13.6487 5.231 13.999 4.33398 14H3.38379C2.48672 13.9991 1.62655 13.6486 0.992188 13.0254C0.357808 12.402 0.000942878 11.5564 0 10.6748V3.3252C0.000942878 2.44363 0.357808 1.59797 0.992188 0.974609C1.62655 0.351357 2.48672 0.000927145 3.38379 0H4.33398ZM10.041 2.625C10.1579 2.62498 10.2738 2.64746 10.3818 2.69141C10.4898 2.73536 10.5882 2.79967 10.6709 2.88086L13.3936 5.55566C13.7822 5.93916 14.0003 6.45876 14 7C13.9997 7.54123 13.7807 8.06034 13.3916 8.44336L10.6689 11.1182C10.5019 11.2822 10.2753 11.3751 10.0391 11.375C9.80291 11.3749 9.57613 11.2823 9.40918 11.1182C9.24233 10.954 9.14838 10.731 9.14844 10.499C9.14859 10.2671 9.24319 10.0448 9.41016 9.88086L11.4678 7.8584L4.15527 7.875C3.91916 7.87496 3.69235 7.78222 3.52539 7.61816C3.35866 7.45419 3.26476 7.23179 3.26465 7C3.26465 6.76802 3.35851 6.54494 3.52539 6.38086C3.69235 6.2168 3.91916 6.12504 4.15527 6.125L11.4365 6.1084L9.41113 4.11816C9.3287 4.0371 9.26345 3.94081 9.21875 3.83496C9.174 3.72886 9.15044 3.61485 9.15039 3.5C9.15033 3.26793 9.24418 3.045 9.41113 2.88086C9.4938 2.79959 9.59218 2.73541 9.7002 2.69141C9.80823 2.6474 9.92407 2.62503 10.041 2.625Z" fill="#111111"/>
        </svg>
        <span class="logout">Logout</span>
    </a>

    {{-- 2. Form Tersembunyi (Required by Laravel) --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none" style="display: none;">
        @csrf
    </form>
</div>

<script>
    (function() {
        // Cek localStorage untuk status sidebar
        const sidebarState = localStorage.getItem('sidebarState');

        // Jika status tersimpan adalah 'collapsed', tambahkan class SEBELUM halaman dirender
        if (sidebarState === 'collapsed') {
            document.body.classList.add('sidebar-collapsed');
        }

        // Hapus class 'preload' setelah loading selesai agar animasi toggle bekerja kembali
        window.addEventListener('load', () => {
            document.body.classList.remove('preload');
        });
    })();
</script>
