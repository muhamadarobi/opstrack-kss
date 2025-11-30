    <style>
        /* Filter Area */
        .box-filter {
            background-color: var(--card-bg);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid var(--gray-border);
            box-shadow: 0 2px 6px var(--shadow-color);
        }
        .filter label { font-size: 12px; font-weight: 500; color: var(--black-color); margin-bottom: 5px; }

        .filter input {
            appearance: none; background-color: var(--input-bg); border: 1px solid var(--gray-border);
            border-radius: 8px; padding: 10px 15px; width: 100%; height: 42px; font-size: 12px;
            color: var(--black-color); transition: border-color 0.15s;
        }
        .filter input:focus { border-color: var(--blue-kss); outline: 0; box-shadow: 0 0 0 0.2rem rgba(0, 119, 194, 0.15); }

        /* Buttons */
        .btn-clear {
            background-color: #6c757d; color: white; border: none; padding: 0 20px; height: 42px;
            border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-flex;
            align-items: center; justify-content: center; transition: all 0.2s ease; font-size: 12px; white-space: nowrap;
        }
        .btn-clear:hover { background-color: #5a6268; color: white; transform: translateY(-2px); }

        .submit-filter {
            background-color: var(--blue-kss); color: white; border: none; padding: 0 20px; height: 42px;
            border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center;
            justify-content: center; transition: all 0.2s ease; font-size: 12px; white-space: nowrap;
        }
        .submit-filter:hover { background-color: var(--blue-kss-dark); color: white; transform: translateY(-2px); }

        /* --- TABLE STYLES --- */
        .document-table {
            border-radius: 10px;
            background-color: var(--white-color);
            border: 1px solid var(--gray-border);
            transition: background-color 0.3s ease, border-color 0.3s ease;
            width: 100%;
            overflow: hidden;
        }

        .box-title {
            padding: 20px 25px;
            border-bottom: 1px solid var(--gray-border);
        }
        .title-table { font-size: 14px; font-weight: 600; color: var(--black-color); }

        .table {
            color: var(--black-color);
            margin-bottom: 0;
            border-color: var(--gray-border);
            width: 100%;
            display: block; /* Penting untuk Flex layout pada row */
        }

        .table thead, .table tbody {
            display: block;
            width: 100%;
        }

        .table tr {
            display: flex;
            width: 100%;
            align-items: stretch; /* Agar tinggi kolom sama rata */
        }

        /* Styling Cell & Header */
        .table tr th, .table tr td {
            display: flex;
            align-items: center; /* Vertically Center text */
            padding: 15px 15px; /* REVISI: Padding dikurangi sedikit agar tidak terlalu lebar */
            font-size: 12px;
            word-break: break-word;
            border-bottom: 1px solid var(--gray-border);
            min-height: 50px; /* Menjaga tinggi baris */
        }

        .table tr th {
            font-weight: 600;
            background-color: var(--table-head-bg);
            border-bottom: 1px solid var(--gray-border); /* Tambahan border bawah header */
            color: var(--black-color);
            white-space: nowrap;
        }

        /* --- COLUMN SIZING CONFIGURATION (REVISI DISINI) --- */

        .col-no {
            flex: 0 0 50px; /* Fixed width 50px (lebih kecil dari 70px) */
            max-width: 50px;
            justify-content: center;
            padding: 15px 5px !important; /* Padding kiri-kanan sangat kecil khusus No */
            text-align: center;
        }

        .col-jenis {
            flex: 2; /* Mengambil sisa ruang paling banyak (Priority 1) */
            min-width: 200px;
        }

        .col-pengunggah {
            flex: 1;
            min-width: 120px;
        }

        .col-group {
            flex: 0.8;
            min-width: 100px;
        }

        .col-waktu {
            flex: 1; /* Sedikit dikecilkan agar seimbang */
            min-width: 140px;
        }

        .col-aksi {
            flex: 0 0 100px; /* Fixed width aksi */
            max-width: 100px;
            justify-content: center;
            gap: 8px;
        }

        /* Action Buttons */
        .btn-aksi {
            border: none; background: var(--input-bg); border: 1px solid var(--gray-border);
            padding: 0; border-radius: 6px; transition: background-color 0.2s ease; color: var(--black-color);
            display: inline-flex; align-items: center; justify-content: center; text-decoration: none;
            width: 32px; height: 32px; flex-shrink: 0;
        }
        .btn-aksi:hover { background-color: var(--gray-border); color: var(--black-color); }

        .table .body td {
            background-color: var(--white-color);
        }

        /* Hover Effect pada baris data */
        .table tbody tr:hover td {
            background-color: rgba(0,0,0,0.02);
        }

        /* Content Badges */
        .badge-baru-table {
            background-color: var(--redcolor); color: white; font-size: 9px;
            padding: 2px 6px; border-radius: 4px; margin-left: 8px; vertical-align: middle;
            white-space: nowrap; display: inline-block;
        }

        /* Pagination */
        .pagination { margin-top: 20px; display: flex; justify-content: center; gap: 5px; }
        .page-item .page-link {
            border-radius: 6px; color: var(--black-color); background-color: var(--card-bg); border-color: var(--gray-border); font-size: 12px;
        }
        .page-item.active .page-link { background-color: var(--blue-kss); border-color: var(--blue-kss); color: white; }

        /* --- CUSTOM SELECT (Reused) --- */
        select { display: none !important; }
        .custom-select-wrapper { position: relative; user-select: none; width: 100%; }
        .custom-select { position: relative; display: flex; flex-direction: column; }
        .custom-select__trigger {
            position: relative; display: flex; align-items: center; justify-content: space-between;
            padding: 0 15px; height: 42px; font-size: 12px; font-weight: 400; color: var(--black-color);
            background: var(--input-bg); border: 1px solid var(--gray-border); border-radius: 8px;
            cursor: pointer; transition: all 0.3s;
        }
        .custom-select__trigger:after {
            content: '\f078'; font-family: 'Font Awesome 6 Free'; font-weight: 900; font-size: 10px;
            color: var(--text-secondary); transition: transform 0.3s;
        }
        .custom-select-wrapper.open .custom-select__trigger { border-color: var(--blue-kss); box-shadow: 0 0 0 3px rgba(0, 119, 194, 0.1); }
        .custom-select-wrapper.open .custom-select__trigger:after { transform: rotate(180deg); color: var(--blue-kss); }
        .custom-options {
            position: absolute; display: block; top: 100%; left: 0; right: 0;
            border: 1px solid var(--gray-border); border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1); background: var(--input-bg);
            transition: all 0.2s; opacity: 0; visibility: hidden; pointer-events: none;
            transform: translateY(-10px); z-index: 100; margin-top: 5px; overflow: hidden;
        }
        .custom-select-wrapper.open .custom-options { opacity: 1; visibility: visible; pointer-events: all; transform: translateY(0); }
        .custom-option { position: relative; display: block; padding: 10px 15px; font-size: 12px; font-weight: 400; color: var(--black-color); cursor: pointer; transition: all 0.2s; }
        .custom-option:hover { cursor: pointer; background-color: var(--orange-kss); color: #fff; font-weight: 500; }
        .custom-option.selected { background-color: rgba(243, 157, 18, 0.1); color: var(--orange-kss); font-weight: 600; }
        .custom-option.selected:hover { background-color: var(--orange-kss); color: #fff; }

        /* --- PREVIEW MODAL (G-Drive Style) --- */
        #previewModal { padding-right: 0 !important; }
        #previewModal .modal-dialog { max-width: 100%; width: 100%; height: 100%; margin: 0; padding: 0; }
        #previewModal .modal-content {
            height: 100%; border: none; border-radius: 0;
            background-color: rgba(32, 33, 36, 0.95) !important; /* Dark translucent */
            backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px);
            color: #e8eaed !important; display: flex; flex-direction: column;
        }
        #previewModal .modal-header {
            background-color: transparent !important; border-bottom: none; padding: 16px 24px;
            flex-shrink: 0; display: flex; align-items: center; justify-content: space-between;
            z-index: 10; box-shadow: 0 1px 0 rgba(255,255,255,0.1);
        }
        #previewModal .modal-title { color: #e8eaed !important; font-size: 1.1rem; font-weight: 400; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 80%; }
        #previewModal .btn-close { filter: invert(1) grayscale(100%) brightness(200%); opacity: 0.7; }
        #previewModal .btn-close:hover { opacity: 1; }
        #previewModal .modal-body { background-color: transparent !important; padding: 0 !important; flex-grow: 1; display: flex; justify-content: center; align-items: center; overflow: hidden; }
        #previewModalContent { width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; padding: 20px; }
        #previewModalContent img { max-height: 90vh; max-width: 90vw; width: auto; height: auto; object-fit: contain; box-shadow: 0 4px 24px rgba(0,0,0,0.5); }
        #previewModalContent iframe { width: 70vw; height: 90vh; background-color: #fff; border: none; box-shadow: 0 4px 24px rgba(0,0,0,0.5); border-radius: 4px; }
    </style>
