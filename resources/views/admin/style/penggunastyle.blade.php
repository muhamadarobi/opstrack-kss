<style>
        /* --- STATUS TOGGLE SWITCH --- */
        .status-toggle-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: var(--input-bg);
            padding: 10px 15px;
            border: 1px solid var(--gray-border);
            border-radius: 8px;
        }
        .status-label {
            font-size: 14px;
            font-weight: 500;
            color: var(--black-color);
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 46px;
            height: 24px;
            flex-shrink: 0;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-switch .slider {
            background-color: #ccc;
        }
        .toggle-switch .slider:before {
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
        }

        .toggle-switch input:checked + .slider {
            background-color: var(--green-call);
        }
        .toggle-switch input:checked + .slider:before {
            transform: translateX(22px);
        }

        /* --- MODAL & FORM STYLING --- */
        .modal-content {
            border-radius: 20px;
            border: 1px solid var(--gray-border);
            background-color: var(--navbar-bg);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .modal-header {
            border-bottom: none;
            padding: 30px 30px 10px 30px;
        }
        .modal-title {
            font-weight: 700;
            font-size: 20px;
            color: var(--black-color);
        }
        .modal-body {
            padding: 10px 30px 30px 30px;
        }

        .btn-close {
            filter: none;
            background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3e%3cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat;
        }
        [data-theme="dark"] .btn-close {
            background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23FFF'%3e%3cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat;
        }

        .form-label {
            font-size: 12px;
            color: var(--text-secondary);
            margin-bottom: 6px;
            font-weight: 500;
        }

        /* Standard Inputs */
        .form-control {
            background-color: var(--input-bg);
            border: 1px solid var(--gray-border);
            color: var(--black-color);
            border-radius: 8px;
            font-size: 14px;
            padding: 12px 15px;
        }
        .form-control:focus {
            border-color: var(--blue-kss);
            background-color: var(--input-bg);
            color: var(--black-color);
            box-shadow: 0 0 0 3px rgba(0, 119, 194, 0.1);
        }

        /* --- MODERN CUSTOM SELECT STYLING (ADAPTASI UNTUK MODAL) --- */
        /* Sembunyikan select asli */
        /* PERBAIKAN: Tambahkan !important agar menang melawan style Bootstrap */
        select {
            display: none !important;
        }

        /* Wadah Dropdown */
        .custom-select-wrapper {
            position: relative;
            user-select: none;
            width: 100%;
        }

        /* Bagian Pemicu (Tampilan Input) */
        .custom-select {
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .custom-select__trigger {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 15px;
            font-size: 14px;
            font-weight: 400;
            color: var(--black-color);
            background: var(--input-bg);
            border: 1px solid var(--gray-border);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        /* Panah Dropdown */
        .custom-select__trigger:after {
            content: '\f078'; /* FontAwesome Chevron Down */
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 12px;
            color: var(--text-secondary);
            transition: transform 0.3s;
        }

        /* State Hover & Open */
        .custom-select-wrapper.open .custom-select__trigger {
            border-color: var(--blue-kss);
            box-shadow: 0 0 0 3px rgba(0, 119, 194, 0.1);
        }

        .custom-select-wrapper.open .custom-select__trigger:after {
            transform: rotate(180deg);
            color: var(--blue-kss);
        }

        /* Daftar Opsi (Dropdown Menu) */
        .custom-options {
            position: absolute;
            display: block;
            top: 100%;
            left: 0;
            right: 0;
            border: 1px solid var(--gray-border);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            background: var(--input-bg); /* Mengikuti tema */
            transition: all 0.2s;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateY(-10px);
            z-index: 100;
            margin-top: 8px;
            overflow: hidden;
        }

        .custom-select-wrapper.open .custom-options {
            opacity: 1;
            visibility: visible;
            pointer-events: all;
            transform: translateY(0);
        }

        /* Styling Opsi Individual */
        .custom-option {
            position: relative;
            display: block;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 400;
            color: var(--black-color);
            cursor: pointer;
            transition: all 0.2s;
        }

        /* Efek Hover Modern (Orange KSS) */
        .custom-option:hover {
            cursor: pointer;
            background-color: var(--orange-kss);
            color: #fff;
            font-weight: 500;
        }

        /* Opsi yang Sedang Dipilih */
        .custom-option.selected {
            background-color: rgba(243, 157, 18, 0.1); /* Orange pudar */
            color: var(--orange-kss);
            font-weight: 600;
        }
        /* Jika di-hover saat selected, tetap solid orange */
        .custom-option.selected:hover {
            background-color: var(--orange-kss);
            color: #fff;
        }
        /* --- END CUSTOM SELECT --- */

        .btn-submit-modal {
            width: 100%;
            display: flex;
            padding: 12px;
            justify-content: center;
            align-items: center;
            border-radius: 50px;
            background: #FFD117;
            color: #111;
            font-size: 14px;
            font-weight: 600;
            border: none;
            margin-top: 10px;
            transition: all 0.3s ease;
        }
        .btn-submit-modal:hover {
            background: #e5bc15;
        }

        /* Styling Khusus Input Group Password Toggle */
        .input-group .form-control {
            border-right: none;
        }
        .input-group .btn-toggle-password {
            background-color: var(--input-bg);
            border: 1px solid var(--gray-border);
            border-left: none;
            color: var(--text-secondary);
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        .input-group .btn-toggle-password:hover {
            color: var(--blue-kss);
        }
</style>
