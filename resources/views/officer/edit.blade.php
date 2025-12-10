@extends('officer.layouts.master')

@push('styles')
<style>
    /* --- TOAST NOTIFICATION (Floating Card) --- */
    .toast-container-fixed { position: fixed; top: 30px; right: 30px; z-index: 9999; display: flex; flex-direction: column; gap: 15px; pointer-events: none; }
    .toast-card { background-color: var(--bg-card); border-radius: 12px; padding: 16px 20px; min-width: 320px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15); display: flex; align-items: flex-start; gap: 15px; border-left: 6px solid; pointer-events: auto; animation: slideInRight 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), fadeOut 0.5s ease 4.5s forwards; position: relative; overflow: hidden; }
    .toast-card.success { border-left-color: var(--green); }
    .toast-card.success .icon-box { background-color: rgba(25, 135, 84, 0.1); color: var(--green); }
    .toast-card.error { border-left-color: var(--redcolor); }
    .toast-card.error .icon-box { background-color: rgba(210, 0, 0, 0.1); color: var(--redcolor); }
    .toast-card .icon-box { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .toast-content { display: flex; flex-direction: column; gap: 4px; flex: 1; }
    .toast-title { font-size: 14px; font-weight: 700; color: var(--text-main); }
    .toast-message { font-size: 12px; color: var(--text-muted); line-height: 1.4; }
    .btn-close-toast { background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 0; font-size: 14px; transition: color 0.2s; }
    .btn-close-toast:hover { color: var(--text-main); }
    .toast-progress { position: absolute; bottom: 0; left: 0; height: 3px; width: 100%; background-color: rgba(0,0,0,0.05); }
    .toast-progress-bar { height: 100%; width: 100%; background-color: currentColor; animation: progress 4.5s linear forwards; transform-origin: left; }
    @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @keyframes fadeOut { to { transform: translateX(10px); opacity: 0; } }
    @keyframes progress { to { transform: scaleX(0); } }

    /* Flatpickr Month Fix */
    .flatpickr-current-month .flatpickr-monthDropdown-months { display: inline-block !important; appearance: none; -webkit-appearance: none; background: transparent; border: none; border-radius: 4px; box-sizing: border-box; color: inherit; cursor: pointer; font-size: inherit; font-family: inherit; font-weight: 700 !important; height: auto; line-height: inherit; margin: 0 5px 0 0; outline: none; padding: 0 0 0 0.5ch; position: relative; vertical-align: initial; width: auto; }
    .flatpickr-current-month .numInputWrapper { width: 6ch; display: inline-block; }

    /* Tab Controls */
    .tab-control-group { display: flex; align-items: center; gap: 5px; margin-left: 10px; padding-left: 10px; border-left: 1px solid var(--border-color); }
    .btn-tab-control { width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; border-radius: 4px; border: none; cursor: pointer; font-size: 12px; transition: all 0.2s; }
    .btn-tab-control.add { background-color: var(--blue-kss); color: white; }
    .btn-tab-control.remove { background-color: var(--redcolor); color: white; }
    .btn-tab-control:hover { opacity: 0.8; }
    .btn-tab-control:disabled { background-color: #ccc; cursor: not-allowed; }

    /* --- MODERN CUSTOM SELECT CSS --- */
    select { display: none !important; }
    .custom-select-container { position: relative; width: 100%; font-family: 'Inter', sans-serif; min-width: 100px; }
    .custom-select-trigger { position: relative; display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 12px 20px; font-size: 14px; font-weight: 400; color: var(--text-main); background-color: var(--bg-input); border: 1px solid var(--border-color); border-radius: 8px; cursor: pointer; transition: all 0.3s; user-select: none; height: 100%; min-height: 42px; }
    .custom-select-trigger:after { content: '\f078'; font-family: 'Font Awesome 6 Free'; font-weight: 900; font-size: 12px; color: var(--text-muted); transition: transform 0.3s; margin-left: 8px; }
    .custom-select-container.open .custom-select-trigger { border-color: var(--blue-kss); box-shadow: 0 0 0 3px rgba(0, 119, 194, 0.1); }
    .custom-select-container.open .custom-select-trigger:after { transform: rotate(180deg); color: var(--blue-kss); }
    .custom-select-options { position: absolute; top: calc(100% + 5px); left: 0; right: 0; z-index: 999; background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; box-shadow: 0 4px 12px var(--shadow-color); opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.2s ease; max-height: 250px; overflow-y: auto; }
    .custom-select-container.open .custom-select-options { opacity: 1; visibility: visible; transform: translateY(0); }
    .custom-option { padding: 10px 20px; font-size: 14px; color: var(--text-main); cursor: pointer; transition: background 0.2s; border-bottom: 1px solid transparent; }
    .custom-option.selected { background-color: var(--hover-bg); color: var(--orange-kss); }
    .custom-option:hover { background-color: var(--orange-kss); color: var(--white-color); }

    /* --- ENHANCED ROW ACTIONS --- */
    .btn-add-row-wrapper {
        margin-top: 15px;
        margin-bottom: 10px;
        width: 100%;
        padding: 0 20px;
        box-sizing: border-box;
    }

    .btn-add-row {
        background-color: transparent;
        color: var(--blue-kss);
        border: 1px dashed var(--blue-kss);
        border-radius: 8px;
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        transition: all 0.2s ease;
    }

    .btn-add-row:hover {
        background-color: rgba(0, 119, 194, 0.05);
        border-style: solid;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 119, 194, 0.1);
    }

    .btn-delete-row {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: transparent;
        border: 1px solid transparent;
        cursor: pointer;
        color: var(--text-muted);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-delete-row:hover {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--redcolor);
        border-color: rgba(220, 53, 69, 0.2);
        transform: scale(1.05);
    }
</style>
@endpush

@section('title', 'Edit Laporan Shift Harian')

@section('content')
    <!-- NAVBAR -->
    @include('officer.layouts.navbar')

    <!-- NOTIFIKASI -->
    @if(session('error'))
        <div class="toast-container-fixed">
            <div class="toast-card error">
                <div class="icon-box"><i class="fa-solid fa-exclamation"></i></div>
                <div class="toast-content"><span class="toast-title">Gagal!</span><span class="toast-message">{{ session('error') }}</span></div>
                <button class="btn-close-toast" onclick="this.parentElement.remove()"><i class="fa-solid fa-xmark"></i></button>
                <div class="toast-progress"><div class="toast-progress-bar" style="color: var(--redcolor);"></div></div>
            </div>
        </div>
    @endif

    <form action="{{ route('reports.update', $report->id) }}" method="POST" class="content d-flex flex-column align-items-center align-self-stretch" style="gap: 20px; padding: 0 60px;">
        @csrf
        @method('PUT')

        <!-- HEADER TABIGASI UTAMA -->
        @include('officer.sections.header_edit')

        <!-- SECTIONS (Menggunakan Partials yang sama dengan Create) -->
        @include('officer.sections.infoumum')
        @include('officer.sections.muatkantong')
        @include('officer.sections.muaturea')
        @include('officer.sections.bongkar')
        @include('officer.sections.gudangturba')
        @include('officer.sections.cekunit')
        @include('officer.sections.karyawan')

    </form>
@endsection

@push('scripts')
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

<script>
    // --- 1. SETUP DATA DARI DATABASE ---
    const reportData = @json($report);
    const employeesGrouped = @json($employeesGrouped ?? []);
    const vehicleData = @json($vehicles ?? []);
    const inventoryData = @json($inventories ?? []);

    // --- 2. FUNGSI HELPER ---

    function initFlatpickrOnElement(element) {
        if (!element) return;
        element.querySelectorAll(".flatpickr-time").forEach(el => flatpickr(el, { enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, disableMobile: false, allowInput: true }));
        element.querySelectorAll(".flatpickr-time-only").forEach(el => flatpickr(el, { enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, disableMobile: false }));
        element.querySelectorAll(".flatpickr-datetime").forEach(el => flatpickr(el, { enableTime: true, dateFormat: "Y-m-d H:i", altInput: true, altFormat: "j F Y, H:i", time_24hr: true, disableMobile: false, allowInput: true, locale: "id", defaultDate: "{{ \Carbon\Carbon::now('Asia/Makassar')->format('Y-m-d') }}" }));
    }

    // --- GENERIC BUTTON INJECTOR FOR ALL SECTIONS ---
    function injectAddButton(tableBodyId, onClickFunctionName, buttonText) {
        const tableBody = document.getElementById(tableBodyId);
        if (!tableBody) return;

        const table = tableBody.closest('table');
        if (!table) return;

        // Cek apakah tombol sudah ada setelah tabel
        if (table.nextElementSibling && table.nextElementSibling.classList.contains('btn-add-row-wrapper')) return;

        const wrapper = document.createElement('div');
        wrapper.className = 'btn-add-row-wrapper';
        wrapper.innerHTML = `
            <button type="button" class="btn-add-row" onclick="${onClickFunctionName}()">
                <i class="fa-solid fa-plus-circle"></i> ${buttonText}
            </button>
        `;

        table.parentNode.insertBefore(wrapper, table.nextSibling);
    }


    // --- AUTO SELECT TIME RANGE ---
    function autoSelectTimeRange() {
        const shiftSelect = document.getElementById('shift');
        const timeRangeSelect = document.getElementById('time_range');

        if (!shiftSelect || !timeRangeSelect) return;

        const shift = shiftSelect.value;
        let timeValue = '';

        if (shift === 'Pagi') timeValue = '07.00 - 15.00';
        else if (shift === 'Sore') timeValue = '15.00 - 23.00';
        else if (shift === 'Malam') timeValue = '23.00 - 07.00';

        if (timeValue) {
            timeRangeSelect.value = timeValue;
            refreshCustomSelect(timeRangeSelect); // Update UI
        }
    }

    // --- UPDATE UI CUSTOM SELECT ---
    function refreshCustomSelect(selectElement) {
        if(!selectElement) return;
        const container = selectElement.nextElementSibling;
        if (container && container.classList.contains('custom-select-container')) {
            const trigger = container.querySelector('.custom-select-trigger');
            const options = container.querySelectorAll('.custom-option');
            const selectedOption = selectElement.options[selectElement.selectedIndex];

            if (trigger && selectedOption) {
                trigger.textContent = selectedOption.text;
            }

            options.forEach(opt => {
                if (opt.dataset.value === selectElement.value) opt.classList.add('selected');
                else opt.classList.remove('selected');
            });
        }
    }

    // --- HTML GENERATORS ---
    function getFormHTML(idSuffix) {
        return `
        <div class="header-loading-info d-flex justify-content-between align-items-start align-content-start align-self-stretch flex-wrap" style="gap: 10px; padding-bottom: 10px;">
            <div class="loading-info">
                <div class="input-loading"><label>Nama Kapal</label><input type="text" name="ship_name_${idSuffix}" placeholder="Masukkan Nama Kapal"></div>
                <div class="input-loading"><label>Agen</label><input type="text" name="agent_${idSuffix}" placeholder="Nama Agen"></div>
                <div class="input-loading"><label>Dermaga (Jetty)</label><input type="text" name="jetty_${idSuffix}" placeholder="Lokasi Dermaga"></div>
                <div class="input-loading"><label>Tujuan</label><input type="text" name="destination_${idSuffix}" placeholder="Tujuan Pengiriman"></div>
            </div>
            <div class="loading-info">
                <div class="input-loading"><label>Kapasitas (Ton)</label><input type="number" step="any" name="capacity_${idSuffix}" placeholder="0"></div>
                <div class="input-loading"><label>Nomor WO</label><input type="text" name="wo_number_${idSuffix}" placeholder="No. Work Order"></div>
                <div class="input-loading"><label>Jenis Kargo</label><input type="text" name="cargo_type_${idSuffix}" placeholder="Pilih Jenis Marking"></div>
                <div class="input-loading"><label>Marking</label><input type="text" name="marking_${idSuffix}" placeholder="Pilih Marking"></div>
            </div>
            <div class="loading-info">
                <div class="input-loading"><label>Tiba/Sandar</label><input type="text" name="arrival_time_${idSuffix}" class="flatpickr-datetime" placeholder="Pilih Waktu..."></div>
                <div class="input-loading"><label>Gang Operasi</label><input type="text" name="operating_gang_${idSuffix}" placeholder="Nama/No Gang"></div>
                <div class="input-loading"><label>Jumlah TKBM</label><input type="number" step="any" name="tkbm_count_${idSuffix}" placeholder="0"></div>
                <div class="input-loading"><label>Mandor</label><input type="text" name="foreman_${idSuffix}" placeholder="Nama Foreman"></div>
            </div>
        </div>
        <div class="box-quantity-count d-flex align-items-center align-content-center align-self-stretch flex-wrap" style="gap: 20px;">
            <div class="quantity-count">
                <div class="title-quantity d-flex align-items-center" style="gap: 10px;">
                    <div class="title-icon" style="background: rgba(0, 119, 194, 0.20)"><i class="fa-solid fa-truck" style="width: 20px; height: 16px; flex-shrink: 0; color: var(--blue-kss);"></i></div>
                    <div class="title-quantity d-flex flex-column align-items-start align-self-stretch" style="gap: 5px;"><span class="title">Pengiriman</span><span class="minitext delivery">Delivery</span></div>
                </div>
                <div class="input-quantity d-flex align-items-center align-self-stretch">
                    <div class="input-qty"><label>Sekarang</label><input type="number" step="any" name="qty_delivery_current_${idSuffix}" placeholder="0"></div>
                    <div class="input-qty"><label>Lalu</label><input type="number" step="any" name="qty_delivery_prev_${idSuffix}" placeholder="0"></div>
                </div>
                <div class="loading-accumulated d-flex flex-column align-items-center align-self-stretch" style="gap: 10px;">
                    <div class="accumulated d-flex justify-content-between align-items-center align-self-stretch"><span class="title-accum" style="font-size: 10px; font-weight: 500;">Total Akumulasi</span><span class="qty_delivery_accumulated_${idSuffix}" style="font-weight: 700; color: var(--blue-kss); text-align: right;">0</span></div>
                    <div class="bar deliv" style="width: 100%; height: 4px; background-color: var(--blue-kss);"></div>
                </div>
            </div>
            <div class="quantity-count">
                <div class="title-quantity d-flex align-items-center" style="gap: 10px;">
                    <div class="title-icon" style="background: rgba(243, 156, 18, 0.20)"><i class="fa-solid fa-truck-ramp-box" style="width: 20px; height: 16px; flex-shrink: 0; color:var(--orange-kss);"></i></div>
                    <div class="title-quantity d-flex flex-column align-items-start align-self-stretch" style="gap: 5px;"><span class="title">Pemuatan</span><span class="minitext delivery" style="background-color: rgba(243, 156, 18, 0.20); color: var(--orange-kss);">Loading</span></div>
                </div>
                <div class="input-quantity d-flex align-items-center align-self-stretch">
                    <div class="input-qty"><label>Sekarang</label><input type="number" step="any" name="qty_loading_current_${idSuffix}" placeholder="0"></div>
                    <div class="input-qty"><label>Lalu</label><input type="number" step="any" name="qty_loading_prev_${idSuffix}" placeholder="0"></div>
                </div>
                <div class="loading-accumulated d-flex flex-column align-items-center align-self-stretch" style="gap: 10px;">
                    <div class="accumulated d-flex justify-content-between align-items-center align-self-stretch"><span class="title-accum" style="font-size: 10px; font-weight: 500;">Total Akumulasi</span><span class="qty_loading_accumulated_${idSuffix}" style="font-weight: 700; color: var(--orange-kss); text-align: right;">0</span></div>
                    <div class="bar load" style="width: 100%; height: 4px; background-color: var(--orange-kss);"></div>
                </div>
            </div>
            <div class="quantity-count">
                <div class="title-quantity d-flex align-items-center" style="gap: 10px;">
                    <div class="title-icon" style="background: rgba(210, 0, 0, 0.20)"><i class="fa-solid fa-box-open" style="width: 20px; height: 16px; flex-shrink: 0; color: var(--redcolor);"></i></div>
                    <div class="title-quantity d-flex flex-column align-items-start align-self-stretch" style="gap: 5px;"><span class="title">Kerusakan</span><span class="minitext damage" style="background-color: rgba(210, 0, 0, 0.20); color: var(--redcolor);">Damage</span></div>
                </div>
                <div class="input-quantity d-flex align-items-center align-self-stretch">
                    <div class="input-qty"><label>Sekarang</label><input type="number" step="any" name="qty_damage_current_${idSuffix}" placeholder="0"></div>
                    <div class="input-qty"><label>Lalu</label><input type="number" step="any" name="qty_damage_prev_${idSuffix}" placeholder="0"></div>
                </div>
                <div class="loading-accumulated d-flex flex-column align-items-center align-self-stretch" style="gap: 10px;">
                    <div class="accumulated d-flex justify-content-between align-items-center align-self-stretch"><span class="title-accum" style="font-size: 10px; font-weight: 500;">Total Akumulasi</span><span class="qty_damage_accumulated_${idSuffix}" style="font-weight: 700; color:var(--redcolor); text-align: right;">0</span></div>
                    <div class="bar deliv" style="width: 100%; height: 4px; background-color: var(--redcolor);"></div>
                </div>
            </div>
        </div>
        <div class="box-timesheet d-flex align-items-start align-content-start align-self-stretch flex-wrap" style="gap: 25px;">
            <div class="log-box log-pengiriman">
                <div class="log-title deliv"><span class="title-log">Log Pengiriman</span><span class="badge-log">Outbound</span></div>
                <div class="timesheet d-flex flex-column align-items-start align-self-stretch">
                    <div class="header-timesheet d-flex align-items-center align-self-stretch" style="padding: 12px 15px; gap: 10px;font-size:12px; font-weight: 600;"><i class="fa-solid fa-list" style="color: var(--blue-kss);"></i>Timesheet</div>
                    <div class="input-timesheet">
                        <div class="time-input-wrapper"><input type="tel" maxlength="5" id="time_delivery_${idSuffix}" class="time-input" placeholder="00:00"><button type="button" class="btn-set-now" id="btn-set-now-delivery-${idSuffix}"><i class="fa-regular fa-clock"></i></button></div>
                        <input type="text" id="kegiatan_delivery_${idSuffix}" class="activity-input" placeholder="Ketik Aktivitas..."><button type="button" id="btn-add-delivery-${idSuffix}" class="btn-add add-delivery" data-suffix="${idSuffix}" data-category="delivery"><i class="fa-solid fa-plus"></i></button>
                    </div>
                    <div class="list-timesheet" id="list-delivery-${idSuffix}"></div>
                    <div class="petugas-section">
                        <div class="petugas-row"><div class="petugas-item"><label class="petugas-label">Tally Gudang</label><div class="input-with-icon"><i class="fa-solid fa-user-pen"></i><input type="text" name="tally_warehouse_${idSuffix}" placeholder="Nama Tally"></div></div></div>
                        <div class="petugas-row"><div class="petugas-item" style="flex: 2;"><label class="petugas-label">Driver</label><div class="input-with-icon"><i class="fa-solid fa-id-card"></i><input type="text" name="driver_name_${idSuffix}" placeholder="Nama Supir"></div></div><div class="petugas-item" style="flex: 1;"><label class="petugas-label">Truck No.</label><div class="input-with-icon"><i class="fa-solid fa-truck-moving"></i><input type="text" name="truck_number_${idSuffix}" placeholder="KT 1234 XX"></div></div></div>
                    </div>
                </div>
            </div>
            <div class="log-box log-pemuatan">
                <div class="log-title load"><span class="title-log">Log Pemuatan</span><span class="badge-log">Loading</span></div>
                <div class="timesheet d-flex flex-column align-items-start align-self-stretch">
                    <div class="header-timesheet d-flex align-items-center align-self-stretch" style="padding: 12px 15px; gap: 10px;font-size:12px; font-weight: 600;"><i class="fa-solid fa-list" style="color: var(--green);"></i>Timesheet</div>
                    <div class="input-timesheet">
                        <div class="time-input-wrapper"><input type="tel" maxlength="5" id="time_loading_${idSuffix}" class="time-input" placeholder="00:00"><button type="button" class="btn-set-now" id="btn-set-now-loading-${idSuffix}"><i class="fa-regular fa-clock" style="color: var(--green);"></i></button></div>
                        <input type="text" id="kegiatan_loading_${idSuffix}" class="activity-input" placeholder="Ketik Aktivitas..."><button type="button" id="btn-add-loading-${idSuffix}" class="btn-add add-loading" data-suffix="${idSuffix}" data-category="loading"><i class="fa-solid fa-plus"></i></button>
                    </div>
                    <div class="list-timesheet" id="list-loading-${idSuffix}"></div>
                    <div class="petugas-section">
                        <div class="petugas-row"><div class="petugas-item"><label class="petugas-label">Tally Kapal</label><div class="input-with-icon"><i class="fa-solid fa-user-tie"></i><input type="text" name="tally_ship_${idSuffix}" placeholder="Nama Tally"></div></div><div class="petugas-item"><label class="petugas-label">Operator</label><div class="input-with-icon"><i class="fa-solid fa-user-gear"></i><input type="text" name="operator_ship_${idSuffix}" placeholder="Nama Operator"></div></div><div class="petugas-item"><label class="petugas-label">Forklift No.</label><div class="input-with-icon"><i class="fa-solid fa-hashtag"></i><input type="text" name="forklift_ship_${idSuffix}" placeholder="Unit"></div></div></div>
                        <div class="petugas-row"><div class="petugas-item" style="flex: 2;"><label class="petugas-label">Operator Gudang</label><div class="input-with-icon"><i class="fa-solid fa-helmet-safety"></i><input type="text" name="operator_warehouse_${idSuffix}" placeholder="Nama Operator"></div></div><div class="petugas-item" style="flex: 1;"><label class="petugas-label">Forklift No.</label><div class="input-with-icon"><i class="fa-solid fa-hashtag"></i><input type="text" name="forklift_warehouse_${idSuffix}" placeholder="Unit"></div></div></div>
                    </div>
                </div>
            </div>
        </div>
        `;
    }

    function getUreaFormHTML(idSuffix) {
        return `
        <div class="bulk-loading-info d-flex flex-column align-items-start align-self-stretch" style="padding: 10px 0; gap: 15px;">
            <div class="input-bulk-loading"><div class="input-item"><label>Nama Kapal</label><input type="text" name="ship_name_urea_${idSuffix}" placeholder="Masukkan nama kapal"></div><div class="input-item"><label>Dermaga</label><input type="text" name="jetty_urea_${idSuffix}" placeholder="Contoh: Jetty 1"></div><div class="input-item"><label>Tujuan</label><input type="text" name="destination_urea_${idSuffix}" placeholder="Kota/Negara Tujuan"></div></div>
            <div class="input-bulk-loading"><div class="input-item"><label>Agen</label><input type="text" name="agent_urea_${idSuffix}" placeholder="Nama Agen"></div><div class="input-item"><label>PBM (Stevedoring)</label><input type="text" name="stevedoring_urea_${idSuffix}" placeholder="Nama PBM"></div><div class="input-item"><label>Jenis Urea</label><input type="text" name="commodity_urea_${idSuffix}"></div></div>
            <div class="input-bulk-loading"><div class="input-item"><label>Kapasitas / Partai (Ton)</label><input type="number" step="any" name="capacity_urea_${idSuffix}" placeholder="0"></div><div class="input-item"><label>Tiba/Sandar</label><input type="text" name="berthing_time_urea_${idSuffix}" class="flatpickr-datetime" placeholder="Pilih Tiba/Sandar"></div><div class="input-item"><label>Mulai Muat</label><input type="text" name="start_loading_time_urea_${idSuffix}" class="flatpickr-datetime" placeholder="Pilih Waktu Mulai"></div></div>
        </div>
        <div class="laporan-harian">
            <div class="header-laporan-harian"><span>Laporan Harian</span><span style="font-size: 11px; font-weight: 300; opacity: 0.8;">Catatan uraian kegiatan</span></div>
            <div class="body-laporan-harian d-flex flex-column align-items-start align-self-stretch">
                <div class="input-laporan-harian d-flex align-items-center align-self-stretch" style="padding: 15px; gap: 10px; border-bottom: 1px solid var(--border-color); background-color: var(--bg-card);">
                    <input type="text" id="input-datetime-urea-${idSuffix}" class="input-laporan flatpickr-datetime" style="width: 220px !important; flex-shrink: 0;" placeholder="Pilih Waktu"><input type="text" id="input-activity-urea-${idSuffix}" class="input-laporan" style="flex: 1; min-width: 0; width: auto !important;" placeholder="Ketik Aktivitas"><input type="number" step="any" id="input-cob-urea-${idSuffix}" class="input-laporan" style="text-align: center; width:100px !important; flex-shrink: 0;" placeholder="COB">
                    <button type="button" id="btn-add-bulk-log-${idSuffix}" class="btn-add-laporan" data-suffix="${idSuffix}"><i class="fa-solid fa-plus" style="color: #FDFDFD; font-size: 14px;"></i></button>
                </div>
                <div class="list-laporan" id="timeline-container-urea-${idSuffix}"></div>
            </div>
        </div>
        `;
    }

    // --- UTILITIES ---
    function handleTimeMasking(e) {
        if (e.target.matches('.time-input, .flatpickr-time, .flatpickr-time-only')) {
            let value = e.target.value.replace(/[^0-9]/g, '');
            if (value.length > 4) value = value.substring(0, 4);
            let formatted = value;
            if (value.length >= 3) { formatted = value.substring(0, 2) + ':' + value.substring(2); }
            if (e.target.value !== formatted) { e.target.value = formatted; }
        }
    }

    function showSection(sectionId, tabElement) {
        document.querySelectorAll('.form-section').forEach(el => el.classList.remove('active'));
        const target = document.getElementById(sectionId);
        if(target) target.classList.add('active');
        if (tabElement) {
            document.querySelectorAll('.content-header .tab').forEach(t => t.classList.remove('active'));
            tabElement.classList.add('active');
        } else {
            const sections = ['section-info-umum', 'section-muat-kantong', 'section-muat-urea', 'section-bongkar', 'section-gudang-turba', 'section-gudang-cek-unit', 'section-gudang-karyawan'];
            const index = sections.indexOf(sectionId);
            if (index !== -1) {
                const tabs = document.querySelectorAll('.content-header .tab');
                tabs.forEach(t => t.classList.remove('active'));
                if(tabs[index]) tabs[index].classList.add('active');
            }
        }
        window.scrollTo(0, 0);
    }

    function setupCustomSelects(specificElement = null) {
        let selects;
        if (specificElement) selects = [specificElement];
        else selects = document.querySelectorAll('select.form-select');

        selects.forEach(select => {
            if (select.nextElementSibling && select.nextElementSibling.classList.contains('custom-select-container')) return;
            const closestTable = select.closest('.table');
            if (closestTable) {
                const allowedTableIds = ['container-table-body', 'vehicle-table-body', 'inventory-table-body', 'shelter-table-body', 'shift-table-body', 'op7-table-body', 'replacement-table-body'];
                let isAllowed = false;
                for (const id of allowedTableIds) { if (select.closest('#' + id)) { isAllowed = true; break; } }
                if (!isAllowed) return;
            }
            const container = document.createElement('div'); container.className = 'custom-select-container';
            const trigger = document.createElement('div'); trigger.className = 'custom-select-trigger';
            const selectedText = select.options[select.selectedIndex] ? select.options[select.selectedIndex].text : '-';
            trigger.textContent = selectedText;
            const optionsWrapper = document.createElement('div'); optionsWrapper.className = 'custom-select-options';
            Array.from(select.options).forEach(option => {
                const optionDiv = document.createElement('div'); optionDiv.className = 'custom-option';
                optionDiv.textContent = option.text; optionDiv.dataset.value = option.value;
                if (option.selected) optionDiv.classList.add('selected');
                optionDiv.addEventListener('click', function() {
                    select.value = this.dataset.value; trigger.textContent = this.textContent;
                    optionsWrapper.querySelectorAll('.custom-option').forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected'); container.classList.remove('open');
                    select.dispatchEvent(new Event('change'));
                });
                optionsWrapper.appendChild(optionDiv);
            });
            container.appendChild(trigger); container.appendChild(optionsWrapper);
            select.parentNode.insertBefore(container, select.nextSibling);
            trigger.addEventListener('click', function(e) { e.stopPropagation(); document.querySelectorAll('.custom-select-container').forEach(c => { if (c !== container) c.classList.remove('open'); }); container.classList.toggle('open'); });
        });
        if (!window.customSelectCloseListener) { document.addEventListener('click', function(e) { document.querySelectorAll('.custom-select-container').forEach(container => { if (!container.contains(e.target)) container.classList.remove('open'); }); }); window.customSelectCloseListener = true; }
    }

    // --- SETUP LOGIC (Events) ---
    function setupTimesheet(timeId, activityId, btnAddId, listId, btnSetNowId) {
        const timeInput = document.getElementById(timeId);
        const btnAdd = document.getElementById(btnAddId);
        if(btnSetNowId) {
            const btnSet = document.getElementById(btnSetNowId);
            if(btnSet) {
                btnSet.addEventListener('click', function() {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const picker = timeInput._flatpickr;
                    if(picker) picker.setDate(`${hours}:${minutes}`, true);
                    else timeInput.value = `${hours}:${minutes}`;
                });
            }
        }
        if(btnAdd){
            btnAdd.addEventListener('click', function() {
                const inputActivity = document.getElementById(activityId);
                let timeVal = timeInput.value;
                let activityVal = inputActivity.value;
                const suffix = this.getAttribute('data-suffix');
                const cat = this.getAttribute('data-category');

                if (timeVal === '' || activityVal === '') { alert('Mohon isi Jam dan Kegiatan'); return; }
                const idx = Date.now();
                renderTimesheetItem(listId, timeVal, activityVal, suffix, cat, idx);
                timeInput.value = ''; inputActivity.value = '';
            });
        }
    }

    function renderTimesheetItem(listId, time, activity, suffix, category, idx) {
        let color = listId.includes('loading') ? 'var(--green)' : 'var(--blue-kss)';
        let newItemHTML = `
            <div class="timesheet-item">
                <div class="d-flex align-items-start w-100">
                    <div class="ts-dot" style="background-color: ${color};"></div>
                    <div style="display:flex; flex-direction:column; flex:1; gap:5px;">
                        <span class="ts-time-badge" style="color: ${color};">${time}</span>
                        <div class="ts-content">${activity}</div>
                        <input type="hidden" name="timesheets[${suffix}][${category}][${idx}][time]" value="${time}">
                        <input type="hidden" name="timesheets[${suffix}][${category}][${idx}][activity]" value="${activity}">
                    </div>
                    <i class="fa-solid fa-trash-can ts-delete" onclick="this.closest('.timesheet-item').remove()"></i>
                </div>
            </div>`;
        document.getElementById(listId).insertAdjacentHTML('beforeend', newItemHTML);
    }

    function setupAccumulationLogic(suffix) {
        ['delivery', 'loading', 'damage'].forEach(type => {
            const currentInput = document.querySelector(`input[name="qty_${type}_current_${suffix}"]`);
            const prevInput = document.querySelector(`input[name="qty_${type}_prev_${suffix}"]`);
            const accumSpan = document.querySelector(`.qty_${type}_accumulated_${suffix}`);
            if(currentInput && prevInput && accumSpan) {
                const calculate = () => {
                    const valCurrent = parseFloat(currentInput.value) || 0;
                    const valPrev = parseFloat(prevInput.value) || 0;
                    const total = valCurrent + valPrev;
                    // Menggunakan toFixed(2) untuk membatasi desimal, lalu parseFloat untuk menghilangkan trailing zeros (misal 10.50 jadi 10.5)
                    accumSpan.textContent = parseFloat(total.toFixed(2));
                };
                currentInput.addEventListener('input', calculate);
                prevInput.addEventListener('input', calculate);
            }
        });
    }

    function setupBulkLog(btnId, datetimeId, activityId, cobId, containerId) {
        const btnAddBulk = document.getElementById(btnId);
        if(btnAddBulk) {
            btnAddBulk.addEventListener('click', function() {
                const datetimeInput = document.getElementById(datetimeId);
                const activityInput = document.getElementById(activityId);
                const cobInput = document.getElementById(cobId);
                const suffix = this.getAttribute('data-suffix');
                if (!datetimeInput.value || !activityInput.value) { alert("Mohon lengkapi data Waktu dan Aktivitas."); return; }
                const idx = Date.now();
                renderBulkLogItem(containerId, datetimeInput.value, activityInput.value, cobInput.value, suffix, idx);
                activityInput.value = ''; cobInput.value = '';
            });
        }
    }

    function renderBulkLogItem(containerId, time, activity, cob, suffix, idx) {
        const timelineContainer = document.getElementById(containerId);
        const newItem = document.createElement('div'); newItem.classList.add('timeline-item');
        const cobHtml = cob ? `<span class="label-cob">COB : ${cob}</span>` : '';
        newItem.innerHTML = `
            <div class="timeline-header"><div class="timeline-dot"></div><span class="timeline-time">${time}</span></div>
            <div class="timeline-content">${cobHtml}<span class="text-activity">${activity}</span>
                <input type="hidden" name="bulk_logs[${suffix}][${idx}][time]" value="${time}">
                <input type="hidden" name="bulk_logs[${suffix}][${idx}][activity]" value="${activity}">
                <input type="hidden" name="bulk_logs[${suffix}][${idx}][cob]" value="${cob}">
            </div>`;
        timelineContainer.insertBefore(newItem, timelineContainer.firstChild);
    }

    function switchBongkarTab(type) {
        const tabBahan = document.getElementById('btn-tab-bahan-baku');
        const tabContainer = document.getElementById('btn-tab-container');
        const contentBahan = document.getElementById('content-bongkar-bahan');
        const contentContainer = document.getElementById('content-bongkar-container');
        if (type === 'bahan') {
            tabBahan.classList.add('active'); tabContainer.classList.remove('active');
            contentBahan.classList.remove('d-none'); contentContainer.classList.add('d-none');
        } else {
            tabContainer.classList.add('active'); tabBahan.classList.remove('active');
            contentContainer.classList.remove('d-none'); contentBahan.classList.add('d-none');
        }
    }

    // --- ROW MANAGEMENT ---
    let bahanRowCount = 0;
    const bahanTableBody = document.getElementById('bahan-table-body');
    function addBahanRow(data = null) {
        bahanRowCount++;
        const tr = document.createElement('tr');
        const valType = (data && data.raw_material_type) ? data.raw_material_type : '';
        const valCurr = (data && data.qty_current) ? data.qty_current : '';
        const valPrev = (data && data.qty_prev) ? data.qty_prev : '';
        const valTotal = (data && data.qty_total) ? data.qty_total : '';

        tr.innerHTML = `
            <td class="align-middle row-num">${bahanRowCount}</td>
            <td><input type="text" class="form-control" name="unloading_materials[${bahanRowCount}][raw_material_type]" value="${valType}"></td>
            <td><input type="number" step="any" class="form-control qty-calc-bahan current" data-row="${bahanRowCount}" name="unloading_materials[${bahanRowCount}][qty_current]" value="${valCurr}" placeholder="0"></td>
            <td><input type="number" step="any" class="form-control qty-calc-bahan prev" data-row="${bahanRowCount}" name="unloading_materials[${bahanRowCount}][qty_prev]" value="${valPrev}" placeholder="0"></td>
            <td><input type="number" step="any" class="form-control accum" name="unloading_materials[${bahanRowCount}][qty_total]" value="${valTotal}" placeholder="0" readonly style="background-color: var(--table-head-bg);"></td>
            <td class="align-middle"><button type="button" class="btn-delete-row" onclick="removeBahanRow(this)"><i class="fa-solid fa-trash-can"></i></button></td>
        `;
        bahanTableBody.appendChild(tr);
        attachCalcEventBahan(tr);
    }
    function removeBahanRow(btn) { if(bahanTableBody.children.length > 1) { btn.closest('tr').remove(); bahanRowCount--; } else { btn.closest('tr').querySelectorAll('input').forEach(i => i.value = ''); } }
    function attachCalcEventBahan(row) {
        row.querySelectorAll('.qty-calc-bahan').forEach(input => {
            input.addEventListener('input', function() {
                const rowElem = this.closest('tr');
                const c = parseFloat(rowElem.querySelector('.current').value) || 0;
                const p = parseFloat(rowElem.querySelector('.prev').value) || 0;
                rowElem.querySelector('.accum').value = c + p;
            });
        });
    }

    let containerRowCount = 0;
    const containerTableBody = document.getElementById('container-table-body');
    function addContainerRow(data = null) {
        containerRowCount++;
        const tr = document.createElement('tr');
        const valTime = (data && data.time) ? data.time : '';
        const valCurr = (data && data.qty_current) ? data.qty_current : '';
        const valPrev = (data && data.qty_prev) ? data.qty_prev : '';
        const valTotal = (data && data.qty_total) ? data.qty_total : '';
        const valStatus = (data && data.status) ? data.status : '';

        tr.innerHTML = `
            <td class="align-middle row-num">${containerRowCount}</td>
            <td><input type="text" class="form-control flatpickr-time-only" name="unloading_containers[${containerRowCount}][time]" value="${valTime}" placeholder="00:00"></td>
            <td><input type="number" step="any" class="form-control qty-calc-cont current" name="unloading_containers[${containerRowCount}][qty_current]" value="${valCurr}" placeholder="0"></td>
            <td><input type="number" step="any" class="form-control qty-calc-cont prev" name="unloading_containers[${containerRowCount}][qty_prev]" value="${valPrev}" placeholder="0"></td>
            <td><input type="number" step="any" class="form-control accum" name="unloading_containers[${containerRowCount}][qty_total]" value="${valTotal}" placeholder="0" readonly style="background-color: var(--table-head-bg);"></td>
            <td><select class="form-select" name="unloading_containers[${containerRowCount}][status]">
                <option value="Full" ${valStatus == 'Full' ? 'selected' : ''}>Full</option>
                <option value="Empty" ${valStatus == 'Empty' ? 'selected' : ''}>Empty</option>
            </select></td>
            <td class="align-middle"><button type="button" class="btn-delete-row" onclick="removeContainerRow(this)"><i class="fa-solid fa-trash-can"></i></button></td>
        `;
        containerTableBody.appendChild(tr);
        flatpickr(tr.querySelector('.flatpickr-time-only'), { enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, disableMobile: false });
        setupCustomSelects(tr.querySelector('select'));
        attachCalcEventContainer(tr);
    }
    function removeContainerRow(btn) { if(containerTableBody.children.length > 1) { btn.closest('tr').remove(); containerRowCount--; } else { btn.closest('tr').querySelectorAll('input').forEach(i => i.value = ''); } }
    function attachCalcEventContainer(row) {
        row.querySelectorAll('.qty-calc-cont').forEach(input => {
            input.addEventListener('input', function() {
                const rowElem = this.closest('tr');
                const c = parseFloat(rowElem.querySelector('.current').value) || 0;
                const p = parseFloat(rowElem.querySelector('.prev').value) || 0;
                rowElem.querySelector('.accum').value = c + p;
            });
        });
    }

    let turbaRowCount = 0;
    const turbaTableBody = document.getElementById('turba-table-body');
    function addTurbaRow(initialData = null, dbData = null) {
        turbaRowCount++;
        const tr = document.createElement('tr');
        let name = '', doSo = '', cap = '', mark = '', curr = '', prev = '', accum = '';

        if (dbData) {
            name = (dbData.truck_name) ? dbData.truck_name : '';
            doSo = (dbData.do_so_number) ? dbData.do_so_number : '';
            cap = (dbData.capacity !== null && dbData.capacity !== undefined) ? dbData.capacity : '';
            mark = (dbData.marking_type) ? dbData.marking_type : '';
            curr = (dbData.qty_current !== null && dbData.qty_current !== undefined) ? dbData.qty_current : '';
            prev = (dbData.qty_prev !== null && dbData.qty_prev !== undefined) ? dbData.qty_prev : '';
            accum = (dbData.qty_accumulated !== null && dbData.qty_accumulated !== undefined) ? dbData.qty_accumulated : '';
        } else if (initialData) {
            name = initialData.name;
        }

        tr.innerHTML = `
            <td class="text-center align-middle row-num">${turbaRowCount}</td>
            <td><input type="text" class="form-control" name="turba_deliveries[${turbaRowCount}][truck_name]" value="${name}" placeholder="Pilih"></td>
            <td><input type="text" class="form-control" name="turba_deliveries[${turbaRowCount}][do_so_number]" value="${doSo}" placeholder="No. DO"></td>
            <td><input type="number" step="any" class="form-control" name="turba_deliveries[${turbaRowCount}][capacity]" value="${cap}" placeholder="0"></td>
            <td><input type="text" class="form-control" name="turba_deliveries[${turbaRowCount}][marking_type]" value="${mark}" placeholder="Jenis Marking"></td>
            <td><input type="number" step="any" class="form-control qty-calc current" data-row="${turbaRowCount}" name="turba_deliveries[${turbaRowCount}][qty_current]" value="${curr}" placeholder="0"></td>
            <td><input type="number" step="any" class="form-control qty-calc prev" data-row="${turbaRowCount}" name="turba_deliveries[${turbaRowCount}][qty_prev]" value="${prev}" placeholder="0"></td>
            <td><input type="number" step="any" class="form-control accum" name="turba_deliveries[${turbaRowCount}][qty_accumulated]" value="${accum}" placeholder="0" readonly style="background-color: var(--table-head-bg);"></td>
            <td class="text-center align-middle"><button type="button" class="btn-delete-row" onclick="removeTurbaRow(this)"><i class="fa-solid fa-trash-can"></i></button></td>
        `;
        turbaTableBody.appendChild(tr);
        attachCalcEvent(tr);
    }
    function removeTurbaRow(btn) { if(turbaTableBody.children.length > 1) { btn.closest('tr').remove(); turbaRowCount--; } else { btn.closest('tr').querySelectorAll('input').forEach(i => i.value = ''); } }
    function attachCalcEvent(row) {
        row.querySelectorAll('.qty-calc').forEach(input => {
            input.addEventListener('input', function() {
                const rowElem = this.closest('tr');
                const c = parseFloat(rowElem.querySelector('.current').value) || 0;
                const p = parseFloat(rowElem.querySelector('.prev').value) || 0;
                rowElem.querySelector('.accum').value = c + p;
            });
        });
    }

    // --- SHIFT ROW LOGIC (ADDED) ---
    let shiftRowCount = 0;

    function addShiftRow(data = null) {
        const tbody = document.getElementById('shift-table-body');
        if (!tbody) return;

        shiftRowCount++;

        const tr = document.createElement('tr');
        const valNama = (data && data.name) ? data.name : '';
        const valMasuk = (data && data.time_in) ? data.time_in : '';
        const valPulang = (data && data.time_out) ? data.time_out : '';
        const valKet = (data && data.description) ? data.description : '';

        tr.innerHTML = `
            <td class="text-center align-middle row-num">${shiftRowCount}</td>
            <td><input type="text" class="form-control" name="shift_nama_${shiftRowCount}" value="${valNama}"></td>
            <td><input type="text" class="form-control flatpickr-time" name="shift_masuk_${shiftRowCount}" placeholder="00:00" value="${valMasuk}"></td>
            <td><input type="text" class="form-control flatpickr-time" name="shift_pulang_${shiftRowCount}" placeholder="00:00" value="${valPulang}"></td>
            <td><input type="text" class="form-control" name="shift_ket_${shiftRowCount}" value="${valKet}" placeholder="Keterangan"></td>
            <td class="align-middle" style="width: 1%; white-space: nowrap;">
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn-delete-row" onclick="removeShiftRow(this)" title="Hapus Baris">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </div>
            </td>
        `;

        tbody.appendChild(tr);
        initFlatpickrOnElement(tr);
        reindexShiftRows();
    }

    function removeShiftRow(btn) {
        const tbody = document.getElementById('shift-table-body');
        if (tbody.children.length > 1) {
            btn.closest('tr').remove();
            reindexShiftRows();
        } else {
            // Jika tinggal 1 baris, hanya kosongkan valuenya
            const row = btn.closest('tr');
            row.querySelectorAll('input').forEach(input => input.value = '');
        }
    }

    function reindexShiftRows() {
        const tbody = document.getElementById('shift-table-body');
        if (!tbody) return;

        const rows = tbody.querySelectorAll('tr');
        shiftRowCount = rows.length;

        rows.forEach((row, index) => {
            const num = index + 1;
            row.querySelector('.row-num').textContent = num;

            // Update name attributes untuk memastikan urutan benar saat submit
            const inputs = row.querySelectorAll('input');
            inputs.forEach(input => {
                const nameParts = input.name.split('_');
                if (nameParts.length > 0) {
                    nameParts[nameParts.length - 1] = num;
                    input.name = nameParts.join('_');
                }
            });
        });
    }

    let op7RowCount = 0;
    function addOp7Row(data = null) {
        const tbody = document.getElementById('op7-table-body');
        op7RowCount++;
        const tr = document.createElement('tr');
        const name = (data && data.name) ? data.name : '';
        const forklift = (data && data.no_forklift_) ? data.no_forklift_ : '';
        const area = (data && data.work_area) ? data.work_area : '';
        const inTime = (data && data.time_in) ? data.time_in : '';
        const outTime = (data && data.time_out) ? data.time_out : '';
        const desc = (data && data.description) ? data.description : '';

        tr.innerHTML = `
            <td class="text-center align-middle">${op7RowCount}</td>
            <td><input type="text" class="form-control" name="op7_logs[${op7RowCount}][name]" value="${name}" placeholder="Nama"></td>
            <td><input type="text" class="form-control" name="op7_logs[${op7RowCount}][no_forklift_]" value="${forklift}" placeholder="No Forklift"></td>
            <td><input type="text" class="form-control" name="op7_logs[${op7RowCount}][work_area]" value="${area}" placeholder="Area"></td>
            <td><input type="text" class="form-control flatpickr-time" name="op7_logs[${op7RowCount}][time_in]" value="${inTime}" placeholder="00:00"></td>
            <td><input type="text" class="form-control flatpickr-time" name="op7_logs[${op7RowCount}][time_out]" value="${outTime}" placeholder="00:00"></td>
            <td><input type="text" class="form-control" name="op7_logs[${op7RowCount}][description]" value="${desc}" placeholder="Keterangan"></td>
            <td class="align-middle text-center"><i class="fa-solid fa-trash-can" style="cursor:pointer; color:var(--redcolor);" onclick="this.closest('tr').remove()"></i></td>
        `;
        tbody.appendChild(tr);
        initFlatpickrOnElement(tr);
        setupCustomSelects(tr.querySelector('select'));
    }

    let replacementRowCount = 0;
    function addReplacementRow(data = null) {
        const tbody = document.getElementById('replacement-table-body');
        replacementRowCount++;
        const tr = document.createElement('tr');
        const name = (data && data.name) ? data.name : '';
        const forklift = (data && data.no_forklift_) ? data.no_forklift_ : '';
        const area = (data && data.work_area) ? data.work_area : '';
        const inTime = (data && data.time_in) ? data.time_in : '';
        const outTime = (data && data.time_out) ? data.time_out : '';
        const desc = (data && data.description) ? data.description : '';

        tr.innerHTML = `
            <td class="text-center align-middle">${replacementRowCount}</td>
            <td><input type="text" class="form-control" name="replacement_logs[${replacementRowCount}][name]" value="${name}" placeholder="Nama"></td>
            <td><input type="text" class="form-control" name="replacement_logs[${replacementRowCount}][no_forklift_]" value="${forklift}" placeholder="No Forklift"></td>
            <td><input type="text" class="form-control" name="replacement_logs[${replacementRowCount}][work_area]" value="${area}" placeholder="Area"></td>
            <td><input type="text" class="form-control flatpickr-time" name="replacement_logs[${replacementRowCount}][time_in]" value="${inTime}" placeholder="00:00"></td>
            <td><input type="text" class="form-control flatpickr-time" name="replacement_logs[${replacementRowCount}][time_out]" value="${outTime}" placeholder="00:00"></td>
            <td><input type="text" class="form-control" name="replacement_logs[${replacementRowCount}][description]" value="${desc}" placeholder="Ket."></td>
            <td class="align-middle text-center"><i class="fa-solid fa-trash-can" style="cursor:pointer; color:var(--redcolor);" onclick="this.closest('tr').remove()"></i></td>
        `;
        tbody.appendChild(tr);
        initFlatpickrOnElement(tr);
    }

    // --- TAB LOGIC ---
    let tabCounters = { kantong: 4, urea: 2 };
    function initSpecificRowLogic(type, seq) {
        if (type === 'kantong') {
             setupTimesheet(`time_delivery_${seq}`, `kegiatan_delivery_${seq}`, `btn-add-delivery-${seq}`, `list-delivery-${seq}`, `btn-set-now-delivery-${seq}`);
             setupTimesheet(`time_loading_${seq}`, `kegiatan_loading_${seq}`, `btn-add-loading-${seq}`, `list-loading-${seq}`, `btn-set-now-loading-${seq}`);
             setupAccumulationLogic(seq);
        } else if (type === 'urea') {
             setupBulkLog(`btn-add-bulk-log-${seq}`, `input-datetime-urea-${seq}`, `input-activity-urea-${seq}`, `input-cob-urea-${seq}`, `timeline-container-urea-${seq}`);
        }
    }
    function addTab(type) {
        tabCounters[type]++;
        const seq = tabCounters[type];
        const sectionId = type === 'kantong' ? 'section-muat-kantong' : 'section-muat-urea';
        const sectionEl = document.getElementById(sectionId);
        if(!sectionEl) return;
        const activitiesContainer = sectionEl.querySelector('.activities');
        const newTab = document.createElement('a');
        newTab.className = 'activities-tab';
        newTab.setAttribute('data-sequence', seq);
        newTab.textContent = `Kegiatan ${seq}`;
        activitiesContainer.insertBefore(newTab, activitiesContainer.querySelector('.tab-control-group'));
        const newPane = document.createElement('div');
        newPane.className = 'activity-pane';
        const contentIdPrefix = type === 'kantong' ? 'activity-content-' : 'urea-activity-content-';
        newPane.id = `${contentIdPrefix}${seq}`;
        if (type === 'kantong') { newPane.innerHTML = getFormHTML(seq); } else { newPane.innerHTML = getUreaFormHTML(seq); }
        const lastPane = sectionEl.querySelector('.activity-pane:last-of-type');
        if (lastPane) { lastPane.parentNode.insertBefore(newPane, lastPane.nextSibling); } else { sectionEl.querySelector('.box-form-shift').appendChild(newPane); }
        initFlatpickrOnElement(newPane);
        initSpecificRowLogic(type, seq);
        return seq; // Return seq needed for population
    }
    function removeTab(type) {
        if (tabCounters[type] <= 1) return;
        const seq = tabCounters[type];
        const sectionId = type === 'kantong' ? 'section-muat-kantong' : 'section-muat-urea';
        const sectionEl = document.getElementById(sectionId);
        const tabToRemove = sectionEl.querySelector(`.activities-tab[data-sequence="${seq}"]`);
        if (tabToRemove) {
            if (tabToRemove.classList.contains('active')) { const prevTab = sectionEl.querySelector(`.activities-tab[data-sequence="${seq-1}"]`); if (prevTab) prevTab.click(); }
            tabToRemove.remove();
        }
        const contentIdPrefix = type === 'kantong' ? 'activity-content-' : 'urea-activity-content-';
        const paneToRemove = document.getElementById(`${contentIdPrefix}${seq}`);
        if (paneToRemove) paneToRemove.remove();
        tabCounters[type]--;
    }
    function injectTabControls(sectionId, type) {
        const sectionEl = document.getElementById(sectionId);
        if (!sectionEl) return;
        const activitiesContainer = sectionEl.querySelector('.activities');
        if (!activitiesContainer || activitiesContainer.querySelector('.tab-control-group')) return;
        const controlGroup = document.createElement('div');
        controlGroup.className = 'tab-control-group';
        controlGroup.innerHTML = `<button type="button" class="btn-tab-control add" onclick="addTab('${type}')"><i class="fa-solid fa-plus"></i></button><button type="button" class="btn-tab-control remove" onclick="removeTab('${type}')"><i class="fa-solid fa-minus"></i></button>`;
        activitiesContainer.appendChild(controlGroup);
    }
    document.addEventListener('click', function(e) {
        if (e.target.matches('.activities-tab')) {
            e.preventDefault();
            const clickedTab = e.target;
            const parent = clickedTab.parentNode;
            parent.querySelectorAll('.activities-tab').forEach(t => t.classList.remove('active'));
            clickedTab.classList.add('active');
            const seq = clickedTab.getAttribute('data-sequence');
            const section = clickedTab.closest('.box-form-shift').parentNode;
            if(seq) {
                section.querySelectorAll('.activity-pane').forEach(pane => pane.classList.remove('active'));
                let targetIdPrefix = 'activity-content-';
                if(section.id === 'section-muat-urea') { targetIdPrefix = 'urea-activity-content-'; }
                const targetPane = section.querySelector(`#${targetIdPrefix}${seq}`);
                if(targetPane) targetPane.classList.add('active');
            }
        }
    });

    function setAllGood(type) {
        let containerId;
        if (type === 'vehicle') containerId = 'vehicle-table-body';
        else if (type === 'inventory') containerId = 'inventory-table-body';
        else if (type === 'shelter') containerId = 'shelter-table-body';
        const selects = document.querySelectorAll(`#${containerId} .status-select`);
        selects.forEach(select => {
            select.value = "Baik";
            select.dispatchEvent(new Event('change'));
            const customContainer = select.nextElementSibling;
            if (customContainer && customContainer.classList.contains('custom-select-container')) {
                const trigger = customContainer.querySelector('.custom-select-trigger');
                if (trigger) trigger.textContent = "Baik";
                customContainer.querySelectorAll('.custom-option').forEach(opt => {
                    if (opt.dataset.value === "Baik") opt.classList.add('selected'); else opt.classList.remove('selected');
                });
            }
        });
    }

    // --- MAIN POPULATION FUNCTION ---
    function populateForm() {
        // 1. Info Umum
        const dateInput = document.getElementById('report_date');
        if(dateInput) {
            dateInput.value = reportData.report_date;
            if(dateInput._flatpickr) dateInput._flatpickr.setDate(reportData.report_date);
        }

        const selects = ['shift', 'group_name', 'time_range', 'received_by_group'];
        selects.forEach(id => {
            const el = document.getElementById(id);
            if(el) {
                el.value = reportData[id];
                refreshCustomSelect(el); // Helper to update visual
            }
        });

        // 2. Muat Kantong
        if (reportData.loading_activities) {
            reportData.loading_activities.forEach(act => {
                const seq = act.sequence;
                if (seq > 4) { addTab('kantong'); }
                const fields = ['ship_name', 'agent', 'jetty', 'destination', 'capacity', 'wo_number', 'cargo_type', 'marking', 'operating_gang', 'tkbm_count', 'foreman', 'tally_warehouse', 'driver_name', 'truck_number', 'tally_ship', 'operator_ship', 'forklift_ship', 'operator_warehouse', 'forklift_warehouse'];
                fields.forEach(f => {
                    const el = document.querySelector(`[name="${f}_${seq}"]`);
                    if(el) el.value = act[f] || '';
                });

                // --- FIX: Gunakan selector name, bukan ID, karena input tiba/sandar tidak memiliki ID ---
                const arrivalEl = document.querySelector(`[name="arrival_time_${seq}"]`);
                if(arrivalEl) {
                      if(arrivalEl._flatpickr) {
                          arrivalEl._flatpickr.setDate(act.arrival_time, true);
                      } else {
                          arrivalEl.value = act.arrival_time;
                      }
                }

                ['delivery', 'loading', 'damage'].forEach(type => {
                    const cur = document.querySelector(`[name="qty_${type}_current_${seq}"]`);
                    const prev = document.querySelector(`[name="qty_${type}_prev_${seq}"]`);
                    if(cur) { cur.value = act[`qty_${type}_current`]; cur.dispatchEvent(new Event('input')); }
                    if(prev) { prev.value = act[`qty_${type}_prev`]; prev.dispatchEvent(new Event('input')); }
                });
                if (act.timesheets) {
                    act.timesheets.forEach((ts, idx) => {
                        const listId = ts.category === 'delivery' ? `list-delivery-${seq}` : `list-loading-${seq}`;
                        renderTimesheetItem(listId, ts.time, ts.activity, seq, ts.category, idx);
                    });
                }
            });
        }

        // 3. Muat Urea
        if (reportData.bulk_loading_activities) {
            reportData.bulk_loading_activities.forEach(act => {
                const seq = act.sequence;
                if (seq > 2) { addTab('urea'); }
                const fields = ['ship_name', 'jetty', 'destination', 'agent', 'stevedoring', 'commodity', 'capacity'];
                fields.forEach(f => {
                    const el = document.querySelector(`[name="${f}_urea_${seq}"]`);
                    if(el) el.value = act[f] || '';
                });
                const berthEl = document.querySelector(`[name="berthing_time_urea_${seq}"]`);
                if(berthEl && berthEl._flatpickr) berthEl._flatpickr.setDate(act.berthing_time, true);
                const startEl = document.querySelector(`[name="start_loading_time_urea_${seq}"]`);
                if(startEl && startEl._flatpickr) startEl._flatpickr.setDate(act.start_loading_time, true);
                if (act.logs) {
                    act.logs.forEach((log, idx) => {
                        renderBulkLogItem(`timeline-container-urea-${seq}`, log.datetime, log.activity, log.cob, seq, idx);
                    });
                }
            });
        }

        // 4. Bongkar
        if (reportData.material_activity) {
            const mat = reportData.material_activity;
            document.querySelector('[name="ship_name_material"]').value = mat.ship_name || '';
            document.querySelector('[name="agent_material"]').value = mat.agent || '';
            document.querySelector('[name="capacity_material"]').value = mat.capacity || '';
            document.querySelector('[name="material_ship_tally_names"]').value = mat.ship_tally_names || '';
            document.querySelector('[name="material_forklift_operator_names"]').value = mat.forklift_operator_names || '';
            document.querySelector('[name="material_delivery_tally_names"]').value = mat.delivery_tally_names || '';
            document.querySelector('[name="material_driver_names"]').value = mat.driver_names || '';
            const whEl = document.querySelector('[name="material_working_hours"]');
            if(whEl && whEl._flatpickr) whEl._flatpickr.setDate(mat.working_hours, true);
            else if(whEl) whEl.value = mat.working_hours || '';
            const bahanBody = document.getElementById('bahan-table-body');
            bahanBody.innerHTML = ''; bahanRowCount = 0;
            if(mat.items && mat.items.length > 0) { mat.items.forEach(item => addBahanRow(item)); } else { addBahanRow(); }
        } else { document.getElementById('bahan-table-body').innerHTML = ''; addBahanRow(); }

        if (reportData.container_activity) {
            const cont = reportData.container_activity;
            document.querySelector('[name="ship_name_container"]').value = cont.ship_name || '';
            document.querySelector('[name="agent_container"]').value = cont.agent || '';
            document.querySelector('[name="capacity_container"]').value = cont.capacity || '';
            document.querySelector('[name="container_ship_tally_names"]').value = cont.ship_tally_names || '';
            document.querySelector('[name="container_gudang_tally_names"]').value = cont.gudang_tally_names || '';
            document.querySelector('[name="container_driver_names"]').value = cont.driver_names || '';
            const contBody = document.getElementById('container-table-body');
            contBody.innerHTML = ''; containerRowCount = 0;
            if(cont.items && cont.items.length > 0) { cont.items.forEach(item => addContainerRow(item)); } else { addContainerRow(); }
        } else { document.getElementById('container-table-body').innerHTML = ''; addContainerRow(); }

        // 5. Turba
        if (reportData.turba_activity) {
            const turba = reportData.turba_activity;
            document.querySelector('[name="tally_gudang_names"]').value = turba.tally_gudang_names || '';
            document.querySelector('[name="turba_forklift_operator"]').value = turba.forklift_operator_names || '';
            document.querySelector('[name="turba_driver_names"]').value = turba.driver_names || '';
            const turbaWh = document.querySelector('[name="turba_working_hours"]');
            if(turbaWh && turbaWh._flatpickr) turbaWh._flatpickr.setDate(turba.working_hours, true);
            const turbaBody = document.getElementById('turba-table-body');
            turbaBody.innerHTML = ''; turbaRowCount = 0;
            if(turba.deliveries && turba.deliveries.length > 0) { turba.deliveries.forEach(del => addTurbaRow(null, del)); }
            else { const initialTurbaRows = [{ name: "Buffer Stock" }, { name: "Buffer Stufing" }, { name: "Buffer Stock" }, { name: "Buffer Stufing" }, { name: "" }]; initialTurbaRows.forEach(d => addTurbaRow(d)); }
        } else {
             const turbaBody = document.getElementById('turba-table-body');
             turbaBody.innerHTML = ''; turbaRowCount = 0;
             const initialTurbaRows = [{ name: "Buffer Stock" }, { name: "Buffer Stufing" }, { name: "Buffer Stock" }, { name: "Buffer Stufing" }, { name: "" }];
             initialTurbaRows.forEach(d => addTurbaRow(d));
        }

        // 6. Cek Unit & Inventory
        const vehicleBody = document.getElementById('vehicle-table-body');
        vehicleBody.innerHTML = '';
        vehicleData.forEach((item, index) => {
            const log = reportData.unit_check_logs ? reportData.unit_check_logs.find(l => l.category === 'vehicle' && l.master_id == item.id) : null;
            const fuel = log && log.fuel_level ? log.fuel_level : '';
            const rec = log ? log.condition_received : '';
            const hand = log ? log.condition_handed_over : '';
            vehicleBody.innerHTML += `<tr><td class="text-center">${index + 1}</td><td>${item.name}<input type="hidden" name="unit_logs[${index}][master_unit_id]" value="${item.id}"></td><td><input type="number" step="any" name="unit_logs[${index}][fuel_level]" class="form-control" placeholder="0" value="${fuel}"></td><td><select name="unit_logs[${index}][condition_received]" class="form-select status-select"><option value="" disabled ${!rec?'selected':''}>-</option><option value="Baik" ${rec=='Baik'?'selected':''}>Baik</option><option value="Rusak" ${rec=='Rusak'?'selected':''}>Rusak</option></select></td><td><select name="unit_logs[${index}][condition_handed_over]" class="form-select status-select"><option value="" disabled ${!hand?'selected':''}>-</option><option value="Baik" ${hand=='Baik'?'selected':''}>Baik</option><option value="Rusak" ${hand=='Rusak'?'selected':''}>Rusak</option></select></td></tr>`;
        });

        const inventoryBody = document.getElementById('inventory-table-body');
        inventoryBody.innerHTML = '';
        inventoryData.forEach((item, index) => {
            const log = reportData.unit_check_logs ? reportData.unit_check_logs.find(l => l.category === 'inventory' && l.master_id == item.id) : null;
            const qty = log && log.quantity ? log.quantity : (item.qty || 1);
            const rec = log ? log.condition_received : '';
            const hand = log ? log.condition_handed_over : '';
            inventoryBody.innerHTML += `<tr><td class="text-center">${index + 1}</td><td>${item.name}<input type="hidden" name="inventory_logs[${index}][master_inventory_item_id]" value="${item.id}"></td><td><input type="number" step="any" name="inventory_logs[${index}][quantity]" class="form-control" value="${qty}"></td><td><select name="inventory_logs[${index}][condition_received]" class="form-select status-select"><option value="" disabled ${!rec?'selected':''}>-</option><option value="Baik" ${rec=='Baik'?'selected':''}>Baik</option><option value="Rusak" ${rec=='Rusak'?'selected':''}>Rusak</option></select></td><td><select name="inventory_logs[${index}][condition_handed_over]" class="form-select status-select"><option value="" disabled ${!hand?'selected':''}>-</option><option value="Baik" ${hand=='Baik'?'selected':''}>Baik</option><option value="Rusak" ${hand=='Rusak'?'selected':''}>Rusak</option></select></td></tr>`;
        });

        const shelterBody = document.getElementById('shelter-table-body');
        shelterBody.innerHTML = '';
        const shelterData = [{ category: "KEBERSIHAN :", items: ["Ruangan Shelter", "Halaman Shelter", "Selokan/Parit"] }, { category: "KERAPIAN :", items: ["Jala-Jala Angkat", "Jala-Jala Lambung", "Terpal", "Chain Sling"] }];
        let globalIndex = 0;
        shelterData.forEach((group, groupIndex) => {
            shelterBody.innerHTML += `<tr class="category-row"><td class="text-center">${groupIndex + 1}</td><td colspan="3">${group.category}</td></tr>`;
            group.items.forEach((item, itemIndex) => {
                const log = reportData.unit_check_logs ? reportData.unit_check_logs.find(l => l.category === 'shelter' && l.item_name === item) : null;
                const rec = log ? log.condition_received : '';
                const hand = log ? log.condition_handed_over : '';
                shelterBody.innerHTML += `<tr><td></td><td style="padding-left: 30px;">${item}<input type="hidden" name="shelter_logs[${globalIndex}][item_name]" value="${item}"><input type="hidden" name="shelter_logs[${globalIndex}][category]" value="${group.category.replace(' :', '')}"></td><td><select name="shelter_logs[${globalIndex}][condition_received]" class="form-select status-select"><option value="" disabled ${!rec?'selected':''}>-</option><option value="Baik" ${rec=='Baik'?'selected':''}>Baik</option><option value="Rusak" ${rec=='Rusak'?'selected':''}>Rusak</option></select></td><td><select name="shelter_logs[${globalIndex}][condition_handed_over]" class="form-select status-select"><option value="" disabled ${!hand?'selected':''}>-</option><option value="Baik" ${hand=='Baik'?'selected':''}>Baik</option><option value="Rusak" ${hand=='Rusak'?'selected':''}>Rusak</option></select></td></tr>`;
                globalIndex++;
            });
        });

        // 7. Employees
        const shiftBody = document.getElementById('shift-table-body');
        shiftBody.innerHTML = ''; shiftRowCount = 0;
        const shiftLogs = reportData.employee_logs ? reportData.employee_logs.filter(l => l.category === 'shift') : [];
        if (shiftLogs.length > 0) {
            shiftLogs.forEach(log => addShiftRow(log));
        } else {
            // Default 1 empty row if no data
            addShiftRow();
        }
        // Inject button for Shift
        injectAddButton('shift-table-body', 'addShiftRow', 'Tambah Karyawan Lainnya');

        const operasiBody = document.getElementById('operasi-table-body');
        operasiBody.innerHTML = '';
        const opLogs = reportData.employee_logs ? reportData.employee_logs.filter(l => l.category === 'operasi') : [];
        for (let i = 1; i <= 15; i++) {
            const lemburLog = opLogs.filter(l => l.description === 'Lembur')[i-1];
            const reliefLog = opLogs.filter(l => l.description === 'Relief Malam')[i-1];

            const valLembur = (lemburLog && lemburLog.name) ? lemburLog.name : '';
            const valRelief = (reliefLog && reliefLog.name) ? reliefLog.name : '';

            operasiBody.innerHTML += `<tr><td>${i}</td><td><input type="text" class="form-control" name="lembur_${i}" placeholder="Nama Karyawan" value="${valLembur}"></td><td>${i + 15}</td><td><input type="text" class="form-control" name="relief_${i + 15}" placeholder="Nama Karyawan" value="${valRelief}"></td></tr>`;
        }

        const lainBody = document.getElementById('lain-table-body');
        lainBody.innerHTML = '';
        const lainLogs = reportData.employee_logs ? reportData.employee_logs.filter(l => l.category === 'lain') : [];
        for (let i = 1; i <= 5; i++) {
            const log = lainLogs[i-1];
            const valDesc = (log && log.description) ? log.description : '';
            const valName = (log && log.name) ? log.name : '';
            const valTime = (log && log.time_in) ? log.time_in : '';

            lainBody.innerHTML += `<tr><td><textarea class="form-control" name="kegiatan_desc_${i}" placeholder="Deskripsi kegiatan...">${valDesc}</textarea></td><td><input type="text" class="form-control" name="kegiatan_personil_${i}" value="${valName}"></td><td><input type="text" class="form-control" name="kegiatan_jam_${i}" placeholder="00:00 - 00:00" value="${valTime}"></td></tr>`;
        }
        initFlatpickrOnElement(lainBody);

        const op7Logs = reportData.employee_logs ? reportData.employee_logs.filter(l => l.category === 'op7') : [];
        document.getElementById('op7-table-body').innerHTML = ''; op7RowCount=0;
        if(op7Logs.length > 0) op7Logs.forEach(l => addOp7Row(l)); else addOp7Row();

        const repLogs = reportData.employee_logs ? reportData.employee_logs.filter(l => l.category === 'replacement') : [];
        document.getElementById('replacement-table-body').innerHTML = ''; replacementRowCount=0;
        if(repLogs.length > 0) repLogs.forEach(l => addReplacementRow(l)); else addReplacementRow();

        // RE-TRIGGER CUSTOM SELECTS (Fix for missing inputs)
        setupCustomSelects();
    }

    // --- INIT ---
    document.addEventListener('DOMContentLoaded', () => {
        // Init Empty Forms first (Standard create logic, but minimal)
        ['1', '2', '3', '4'].forEach(seq => {
            const container = document.getElementById(`activity-content-${seq}`);
            if(container) { container.innerHTML = getFormHTML(seq); initSpecificRowLogic('kantong', seq); }
        });
        ['1', '2'].forEach(seq => {
            const container = document.getElementById(`urea-activity-content-${seq}`);
            if(container) { container.innerHTML = getUreaFormHTML(seq); initSpecificRowLogic('urea', seq); }
        });

        // Initialize Helpers
        initPickers();
        document.body.removeEventListener('input', handleTimeMasking);
        document.body.addEventListener('input', handleTimeMasking);

        injectTabControls('section-muat-kantong', 'kantong');
        injectTabControls('section-muat-urea', 'urea');

        // Setup Custom Selects for initial static elements (INFO UMUM SECTION FIX)
        setupCustomSelects();

        // Listener Auto Shift
        const shiftSelect = document.getElementById('shift');
        if(shiftSelect) {
            shiftSelect.addEventListener('change', autoSelectTimeRange);
        }

        // POPULATE DATA
        populateForm();
    });

    function initPickers() {
        initFlatpickrOnElement(document.body);
        const reportDateInput = document.querySelector("#report_date");
        if (reportDateInput) {
            flatpickr(reportDateInput, {
                dateFormat: "Y-m-d", altInput: true, altFormat: "d/m/Y", locale: "id", disableMobile: false, allowInput: true,
                // Default date set from JS populateForm, so no need defaultDate here
            });
        }
    }
</script>
@endpush
