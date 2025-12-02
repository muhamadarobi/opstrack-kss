@extends('officer.layouts.master')

@push('styles')
<style>
    /* --- TOAST NOTIFICATION (Floating Card) --- */
    .toast-container-fixed {
        position: fixed;
        top: 30px;
        right: 30px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 15px;
        pointer-events: none;
    }

    .toast-card {
        background-color: var(--bg-card);
        border-radius: 12px;
        padding: 16px 20px;
        min-width: 320px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: flex-start;
        gap: 15px;
        border-left: 6px solid;
        pointer-events: auto;
        animation: slideInRight 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), fadeOut 0.5s ease 4.5s forwards;
        position: relative;
        overflow: hidden;
    }

    /* Success Theme */
    .toast-card.success { border-left-color: var(--green); }
    .toast-card.success .icon-box { background-color: rgba(25, 135, 84, 0.1); color: var(--green); }

    /* Error Theme */
    .toast-card.error { border-left-color: var(--redcolor); }
    .toast-card.error .icon-box { background-color: rgba(210, 0, 0, 0.1); color: var(--redcolor); }

    .toast-card .icon-box {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .toast-content { display: flex; flex-direction: column; gap: 4px; flex: 1; }
    .toast-title { font-size: 14px; font-weight: 700; color: var(--text-main); }
    .toast-message { font-size: 12px; color: var(--text-muted); line-height: 1.4; }

    .btn-close-toast {
        background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 0; font-size: 14px; transition: color 0.2s;
    }
    .btn-close-toast:hover { color: var(--text-main); }

    .toast-progress { position: absolute; bottom: 0; left: 0; height: 3px; width: 100%; background-color: rgba(0,0,0,0.05); }
    .toast-progress-bar {
        height: 100%; width: 100%; background-color: currentColor;
        animation: progress 4.5s linear forwards; transform-origin: left;
    }

    @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @keyframes fadeOut { to { transform: translateX(10px); opacity: 0; } }
    @keyframes progress { to { transform: scaleX(0); } }

    /* --- FIX TAMPILAN BULAN DI KALENDER (FLATPICKR) --- */
    /* Menambahkan style ini agar dropdown bulan terlihat jelas */
    .flatpickr-current-month .flatpickr-monthDropdown-months {
        display: inline-block !important;
        appearance: none;
        -webkit-appearance: none;
        background: transparent;
        border: none;
        border-radius: 4px;
        box-sizing: border-box;
        color: inherit;
        cursor: pointer;
        font-size: inherit;
        font-family: inherit;
        font-weight: 700 !important;
        height: auto;
        line-height: inherit;
        margin: 0 5px 0 0;
        outline: none;
        padding: 0 0 0 0.5ch;
        position: relative;
        vertical-align: initial;
        width: auto;
    }
    .flatpickr-current-month .numInputWrapper {
        width: 6ch;
        display: inline-block;
    }
</style>
@endpush

@section('title', 'Form Laporan Shift Harian')

@section('content')
    <!-- NAVBAR -->
    @include('officer.layouts.navbar')

    <!-- NOTIFIKASI ERROR -->
    @if(session('error'))
        <div class="toast-container-fixed">
            <div class="toast-card error">
                <div class="icon-box"><i class="fa-solid fa-exclamation"></i></div>
                <div class="toast-content">
                    <span class="toast-title">Gagal Menyimpan!</span>
                    <span class="toast-message">{{ session('error') }}</span>
                </div>
                <button class="btn-close-toast" onclick="this.parentElement.remove()"><i class="fa-solid fa-xmark"></i></button>
                <div class="toast-progress"><div class="toast-progress-bar" style="color: var(--redcolor);"></div></div>
            </div>
        </div>
    @endif

    <!-- NOTIFIKASI SUCCESS -->
    @if(session('success'))
        <div class="toast-container-fixed">
            <div class="toast-card success">
                <div class="icon-box"><i class="fa-solid fa-check"></i></div>
                <div class="toast-content">
                    <span class="toast-title">Berhasil!</span>
                    <span class="toast-message">{{ session('success') }}</span>
                </div>
                <button class="btn-close-toast" onclick="this.parentElement.remove()"><i class="fa-solid fa-xmark"></i></button>
                <div class="toast-progress"><div class="toast-progress-bar" style="color: var(--green);"></div></div>
            </div>
        </div>
    @endif

    <form action="{{ route('reports.store') }}" method="POST" class="content d-flex flex-column align-items-center align-self-stretch" style="gap: 20px; padding: 0 60px;">
        @csrf

        <!-- Hidden ID Inputs -->
        <input type="hidden" name="id">
        <input type="hidden" name="daily_report_id">
        <input type="hidden" name="sequence">

        <!-- HEADER TABIGASI UTAMA -->
        @include('officer.sections.header')

        <!-- SECTIONS -->
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
<!-- Load Locale Indonesia untuk Flatpickr -->
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

<script>
    // --- AMBIL DATA KARYAWAN DARI CONTROLLER ---
    const employeesGrouped = @json($employeesGrouped ?? []);

    // --- HELPER: Init Flatpickr pada Element Tertentu (OPTIMASI AGAR TIDAK LAG) ---
    // Fungsi ini menggantikan init global di dalam loop
    function initFlatpickrOnElement(element) {
        if (!element) return;

        // Init Time Picker
        element.querySelectorAll(".flatpickr-time").forEach(el => {
            flatpickr(el, { enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, disableMobile: false, allowInput: true });
        });

        // Init Time Only (Bongkar)
        element.querySelectorAll(".flatpickr-time-only").forEach(el => {
            flatpickr(el, { enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, disableMobile: false });
        });

        // Init DateTime
        element.querySelectorAll(".flatpickr-datetime").forEach(el => {
            // Gunakan Locale ID agar nama bulan Indonesia
            flatpickr(el, { enableTime: true, dateFormat: "Y-m-d H:i", altInput: true, altFormat: "j F Y, H:i", time_24hr: true, disableMobile: false, allowInput: true, locale: "id" });
        });
    }

    // --- LOGIC HANDLING ABSENSI TIDAK MASUK ---
    // Fungsi ini dipanggil ketika select option Keterangan berubah
    function checkShiftAttendance(selectElement, index) {
        if (selectElement.value === "Tidak Masuk") {
            // Cari input jam masuk dan pulang berdasarkan index
            const inputMasuk = document.querySelector(`input[name="shift_masuk_${index}"]`);
            const inputPulang = document.querySelector(`input[name="shift_pulang_${index}"]`);

            // Kosongkan nilainya
            if (inputMasuk) inputMasuk.value = "";
            if (inputPulang) inputPulang.value = "";
        }
    }

    // --- LOGIC AUTO FILL EMPLOYEE DENGAN DYNAMIC ROWS ---
    function autoFillEmployees() {
        const groupSelect = document.getElementById('group_name');
        const timeRangeSelect = document.getElementById('time_range');
        const shiftBody = document.getElementById('shift-table-body');

        if (!groupSelect || !timeRangeSelect || !shiftBody) return;

        const selectedGroup = groupSelect.value;
        const selectedTimeRange = timeRangeSelect.value;

        let timeIn = '';
        let timeOut = '';

        if (selectedTimeRange === '07.00 - 15.00') {
            timeIn = '07:00';
            timeOut = '15:00';
        } else if (selectedTimeRange === '15.00 - 23.00') {
            timeIn = '15:00';
            timeOut = '23:00';
        } else if (selectedTimeRange === '23.00 - 07.00') {
            timeIn = '23:00';
            timeOut = '07:00';
        }

        let employees = [];
        if (selectedGroup) {
            const groupKey = "Group " + selectedGroup;
            employees = [...(employeesGrouped[groupKey] || [])];

            employees.sort((a, b) => {
                const npkA = a.npk || '';
                const npkB = b.npk || '';
                return npkA.localeCompare(npkB, undefined, { numeric: true, sensitivity: 'base' });
            });
        }

        const rowCount = employees.length > 0 ? employees.length : 14;
        shiftBody.innerHTML = '';

        // Gunakan Fragment atau string builder untuk performa
        let htmlContent = '';
        for (let i = 1; i <= rowCount; i++) {
            const employee = employees[i - 1];
            let valNama = '';
            let valMasuk = '';
            let valPulang = '';

            if (employee) {
                valNama = employee.name;
                if(selectedTimeRange) {
                    valMasuk = timeIn;
                    valPulang = timeOut;
                }
            }

            htmlContent += `
                <tr>
                    <td class="text-center">${i}</td>
                    <td>
                        <input type="text" class="form-control" name="shift_nama_${i}" value="${valNama}">
                    </td>
                    <td>
                        <input type="text" class="form-control flatpickr-time" name="shift_masuk_${i}" placeholder="00:00" value="${valMasuk}">
                    </td>
                    <td>
                        <input type="text" class="form-control flatpickr-time" name="shift_pulang_${i}" placeholder="00:00" value="${valPulang}">
                    </td>
                    <td>
                        <select class="form-select" name="shift_ket_${i}" onchange="checkShiftAttendance(this, ${i})">
                            <option value=""></option>
                            <option value="Tidak Masuk">Tidak Masuk</option>
                        </select>
                    </td>
                </tr>
            `;
        }
        shiftBody.innerHTML = htmlContent;
        // Init Flatpickr pada elemen baru
        initFlatpickrOnElement(shiftBody);

        // PERBAIKAN: Init Custom Select pada elemen baru di tabel shift
        shiftBody.querySelectorAll('select').forEach(sel => setupCustomSelects(sel));
    }

    // --- LOGIC AUTO SYNC SHIFT TO TIME RANGE ---
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
            const customContainer = timeRangeSelect.nextElementSibling;
            if (customContainer && customContainer.classList.contains('custom-select-container')) {
                const trigger = customContainer.querySelector('.custom-select-trigger');
                const options = customContainer.querySelectorAll('.custom-option');
                const selectedOption = timeRangeSelect.options[timeRangeSelect.selectedIndex];
                if(trigger && selectedOption) trigger.textContent = selectedOption.text;
                options.forEach(opt => {
                    if (opt.dataset.value === timeValue) opt.classList.add('selected');
                    else opt.classList.remove('selected');
                });
            }
            timeRangeSelect.dispatchEvent(new Event('change'));
        }
    }

    // --- HELPER FUNCTIONS FOR DYNAMIC HTML ---
    function getFormHTML(idSuffix) {
        return `
        <!-- HEADER INFO -->
        <div class="header-loading-info d-flex justify-content-between align-items-start align-content-start align-self-stretch flex-wrap" style="gap: 10px; padding-bottom: 10px;">
            <div class="loading-info">
                <div class="input-loading"><label>Nama Kapal</label><input type="text" name="ship_name_${idSuffix}" placeholder="Masukkan Nama Kapal"></div>
                <div class="input-loading"><label>Agen</label><input type="text" name="agent_${idSuffix}" placeholder="Nama Agen"></div>
                <div class="input-loading"><label>Dermaga (Jetty)</label><input type="text" name="jetty_${idSuffix}" placeholder="Lokasi Dermaga"></div>
                <div class="input-loading"><label>Tujuan</label><input type="text" name="destination_${idSuffix}" placeholder="Tujuan Pengiriman"></div>
            </div>
            <div class="loading-info">
                <div class="input-loading"><label>Kapasitas (Ton)</label><input type="number" name="capacity_${idSuffix}" placeholder="0"></div>
                <div class="input-loading"><label>Nomor WO</label><input type="text" name="wo_number_${idSuffix}" placeholder="No. Work Order"></div>
                <div class="input-loading"><label>Jenis Kargo</label><input type="text" name="cargo_type_${idSuffix}" placeholder="Pilih Jenis Marking"></div>
                <div class="input-loading"><label>Marking</label><input type="text" name="marking_${idSuffix}" placeholder="Pilih Marking"></div>
            </div>
            <div class="loading-info">
                <div class="input-loading"><label>Tiba/Sandar</label><input type="text" id="arrival_time_${idSuffix}" class="arrival-time-picker" placeholder="Pilih Waktu..."></div>
                <div class="input-loading"><label>Gang Operasi</label><input type="text" name="operating_gang_${idSuffix}" placeholder="Nama/No Gang"></div>
                <div class="input-loading"><label>Jumlah TKBM</label><input type="number" name="tkbm_count_${idSuffix}" placeholder="0"></div>
                <div class="input-loading"><label>Mandor</label><input type="text" name="foreman_${idSuffix}" placeholder="Nama Foreman"></div>
            </div>
        </div>

        <!-- QUANTITY BOXES -->
        <div class="box-quantity-count d-flex align-items-center align-content-center align-self-stretch flex-wrap" style="gap: 20px;">
            <!-- Delivery Box -->
            <div class="quantity-count">
                <div class="title-quantity d-flex align-items-center" style="gap: 10px;">
                    <div class="title-icon" style="background: rgba(0, 119, 194, 0.20)"><i class="fa-solid fa-truck" style="width: 20px; height: 16px; flex-shrink: 0; color: var(--blue-kss);"></i></div>
                    <div class="title-quantity d-flex flex-column align-items-start align-self-stretch" style="gap: 5px;">
                        <span class="title">Pengiriman</span><span class="minitext delivery">Delivery</span>
                    </div>
                </div>
                <div class="input-quantity d-flex align-items-center align-self-stretch">
                    <div class="input-qty"><label>Sekarang</label><input type="number" name="qty_delivery_current_${idSuffix}" placeholder="0"></div>
                    <div class="input-qty"><label>Lalu</label><input type="number" name="qty_delivery_prev_${idSuffix}" placeholder="0"></div>
                </div>
                <div class="loading-accumulated d-flex flex-column align-items-center align-self-stretch" style="gap: 10px;">
                    <div class="accumulated d-flex justify-content-between align-items-center align-self-stretch">
                        <span class="title-accum" style="font-size: 10px; font-weight: 500;">Total Akumulasi</span>
                        <span class="qty_delivery_accumulated_${idSuffix}" style="font-weight: 700; color: var(--blue-kss); text-align: right;">0</span>
                    </div>
                    <div class="bar deliv" style="width: 100%; height: 4px; background-color: var(--blue-kss);"></div>
                </div>
            </div>
            <!-- Loading Box -->
            <div class="quantity-count">
                <div class="title-quantity d-flex align-items-center" style="gap: 10px;">
                    <div class="title-icon" style="background: rgba(243, 156, 18, 0.20)"><i class="fa-solid fa-truck-ramp-box" style="width: 20px; height: 16px; flex-shrink: 0; color:var(--orange-kss);"></i></div>
                    <div class="title-quantity d-flex flex-column align-items-start align-self-stretch" style="gap: 5px;">
                        <span class="title">Pemuatan</span><span class="minitext delivery" style="background-color: rgba(243, 156, 18, 0.20); color: var(--orange-kss);">Loading</span>
                    </div>
                </div>
                <div class="input-quantity d-flex align-items-center align-self-stretch">
                    <div class="input-qty"><label>Sekarang</label><input type="number" name="qty_loading_current_${idSuffix}" placeholder="0"></div>
                    <div class="input-qty"><label>Lalu</label><input type="number" name="qty_loading_prev_${idSuffix}" placeholder="0"></div>
                </div>
                <div class="loading-accumulated d-flex flex-column align-items-center align-self-stretch" style="gap: 10px;">
                    <div class="accumulated d-flex justify-content-between align-items-center align-self-stretch">
                        <span class="title-accum" style="font-size: 10px; font-weight: 500;">Total Akumulasi</span>
                        <span class="qty_loading_accumulated_${idSuffix}" style="font-weight: 700; color: var(--orange-kss); text-align: right;">0</span>
                    </div>
                    <div class="bar load" style="width: 100%; height: 4px; background-color: var(--orange-kss);"></div>
                </div>
            </div>
            <!-- Damage Box -->
            <div class="quantity-count">
                <div class="title-quantity d-flex align-items-center" style="gap: 10px;">
                    <div class="title-icon" style="background: rgba(210, 0, 0, 0.20)"><i class="fa-solid fa-box-open" style="width: 20px; height: 16px; flex-shrink: 0; color: var(--redcolor);"></i></div>
                    <div class="title-quantity d-flex flex-column align-items-start align-self-stretch" style="gap: 5px;">
                        <span class="title">Kerusakan</span><span class="minitext damage" style="background-color: rgba(210, 0, 0, 0.20); color: var(--redcolor);">Damage</span>
                    </div>
                </div>
                <div class="input-quantity d-flex align-items-center align-self-stretch">
                    <div class="input-qty"><label>Sekarang</label><input type="number" name="qty_damage_current_${idSuffix}" placeholder="0"></div>
                    <div class="input-qty"><label>Lalu</label><input type="number" name="qty_damage_prev_${idSuffix}" placeholder="0"></div>
                </div>
                <div class="loading-accumulated d-flex flex-column align-items-center align-self-stretch" style="gap: 10px;">
                    <div class="accumulated d-flex justify-content-between align-items-center align-self-stretch">
                        <span class="title-accum" style="font-size: 10px; font-weight: 500;">Total Akumulasi</span>
                        <span class="qty_damage_accumulated_${idSuffix}" style="font-weight: 700; color:var(--redcolor); text-align: right;">0</span>
                    </div>
                    <div class="bar deliv" style="width: 100%; height: 4px; background-color: var(--redcolor);"></div>
                </div>
            </div>
        </div>

        <!-- TIMESHEETS -->
        <div class="box-timesheet d-flex align-items-start align-content-start align-self-stretch flex-wrap" style="gap: 25px;">
            <!-- Log Pengiriman -->
            <div class="log-box log-pengiriman">
                <div class="log-title deliv"><span class="title-log">Log Pengiriman</span><span class="badge-log">Outbound</span></div>
                <div class="timesheet d-flex flex-column align-items-start align-self-stretch">
                    <div class="header-timesheet d-flex align-items-center align-self-stretch" style="padding: 12px 15px; gap: 10px;font-size:12px; font-weight: 600;">
                        <i class="fa-solid fa-list" style="color: var(--blue-kss);"></i>Timesheet
                    </div>
                    <div class="input-timesheet">
                        <div class="time-input-wrapper">
                            <input type="tel" maxlength="5" id="time_delivery_${idSuffix}" class="time-input" placeholder="00:00">
                            <button type="button" class="btn-set-now" id="btn-set-now-delivery-${idSuffix}"><i class="fa-regular fa-clock"></i></button>
                        </div>
                        <input type="text" id="kegiatan_delivery_${idSuffix}" class="activity-input" placeholder="Ketik Aktivitas...">
                        <button type="button" id="btn-add-delivery-${idSuffix}" class="btn-add add-delivery" data-suffix="${idSuffix}" data-category="delivery"><i class="fa-solid fa-plus"></i></button>
                    </div>
                    <div class="list-timesheet" id="list-delivery-${idSuffix}"></div>
                    <div class="petugas-section">
                        <div class="petugas-row">
                            <div class="petugas-item">
                                <label class="petugas-label">Tally Gudang</label>
                                <div class="input-with-icon"><i class="fa-solid fa-user-pen"></i><input type="text" name="tally_warehouse_${idSuffix}" placeholder="Nama Tally"></div>
                            </div>
                        </div>
                        <div class="petugas-row">
                            <div class="petugas-item" style="flex: 2;">
                                <label class="petugas-label">Driver</label>
                                <div class="input-with-icon"><i class="fa-solid fa-id-card"></i><input type="text" name="driver_name_${idSuffix}" placeholder="Nama Supir"></div>
                            </div>
                            <div class="petugas-item" style="flex: 1;">
                                <label class="petugas-label">Truck No.</label>
                                <div class="input-with-icon"><i class="fa-solid fa-truck-moving"></i><input type="text" name="truck_number_${idSuffix}" placeholder="KT 1234 XX"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Log Pemuatan -->
            <div class="log-box log-pemuatan">
                <div class="log-title load"><span class="title-log">Log Pemuatan</span><span class="badge-log">Loading</span></div>
                <div class="timesheet d-flex flex-column align-items-start align-self-stretch">
                    <div class="header-timesheet d-flex align-items-center align-self-stretch" style="padding: 12px 15px; gap: 10px;font-size:12px; font-weight: 600;">
                        <i class="fa-solid fa-list" style="color: var(--green);"></i>Timesheet
                    </div>
                    <div class="input-timesheet">
                        <div class="time-input-wrapper">
                            <input type="tel" maxlength="5" id="time_loading_${idSuffix}" class="time-input" placeholder="00:00">
                            <button type="button" class="btn-set-now" id="btn-set-now-loading-${idSuffix}"><i class="fa-regular fa-clock" style="color: var(--green);"></i></button>
                        </div>
                        <input type="text" id="kegiatan_loading_${idSuffix}" class="activity-input" placeholder="Ketik Aktivitas...">
                        <button type="button" id="btn-add-loading-${idSuffix}" class="btn-add add-loading" data-suffix="${idSuffix}" data-category="loading"><i class="fa-solid fa-plus"></i></button>
                    </div>
                    <div class="list-timesheet" id="list-loading-${idSuffix}"></div>
                    <div class="petugas-section">
                        <div class="petugas-row">
                            <div class="petugas-item"><label class="petugas-label">Tally Kapal</label><div class="input-with-icon"><i class="fa-solid fa-user-tie"></i><input type="text" name="tally_ship_${idSuffix}" placeholder="Nama Tally"></div></div>
                            <div class="petugas-item"><label class="petugas-label">Operator</label><div class="input-with-icon"><i class="fa-solid fa-user-gear"></i><input type="text" name="operator_ship_${idSuffix}" placeholder="Nama Operator"></div></div>
                            <div class="petugas-item"><label class="petugas-label">Forklift No.</label><div class="input-with-icon"><i class="fa-solid fa-hashtag"></i><input type="text" name="forklift_ship_${idSuffix}" placeholder="Unit"></div></div>
                        </div>
                        <div class="petugas-row">
                            <div class="petugas-item" style="flex: 2;"><label class="petugas-label">Operator Gudang</label><div class="input-with-icon"><i class="fa-solid fa-helmet-safety"></i><input type="text" name="operator_warehouse_${idSuffix}" placeholder="Nama Operator"></div></div>
                            <div class="petugas-item" style="flex: 1;"><label class="petugas-label">Forklift No.</label><div class="input-with-icon"><i class="fa-solid fa-hashtag"></i><input type="text" name="forklift_warehouse_${idSuffix}" placeholder="Unit"></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `;
    }

    function getUreaFormHTML(idSuffix) {
        return `
        <!-- FORM UTAMA: bulk_loading_activities -->
        <div class="bulk-loading-info d-flex flex-column align-items-start align-self-stretch" style="padding: 10px 0; gap: 15px;">
            <!-- Row 1: Ship Name, Jetty, Destination -->
            <div class="input-bulk-loading">
                <div class="input-item">
                    <label>Nama Kapal</label>
                    <input type="text" name="ship_name_urea_${idSuffix}" placeholder="Masukkan nama kapal">
                </div>
                <div class="input-item">
                    <label>Dermaga</label>
                    <input type="text" name="jetty_urea_${idSuffix}" placeholder="Contoh: Jetty 1">
                </div>
                <div class="input-item">
                    <label>Tujuan</label>
                    <input type="text" name="destination_urea_${idSuffix}" placeholder="Kota/Negara Tujuan">
                </div>
            </div>

            <!-- Row 2: Agent, Stevedoring, Commodity -->
            <div class="input-bulk-loading">
                <div class="input-item">
                    <label>Agen</label>
                    <input type="text" name="agent_urea_${idSuffix}" placeholder="Nama Agen">
                </div>
                <div class="input-item">
                    <label>PBM (Stevedoring)</label>
                    <input type="text" name="stevedoring_urea_${idSuffix}" placeholder="Nama PBM">
                </div>
                <div class="input-item">
                    <label>Komoditas</label>
                    <input type="text" name="commodity_urea_${idSuffix}">
                </div>
            </div>

            <!-- Row 3: Capacity, Berthing Time, Start Loading Time -->
            <div class="input-bulk-loading">
                <div class="input-item">
                    <label>Kapasitas / Partai (Ton)</label>
                    <input type="number" name="capacity_urea_${idSuffix}" placeholder="0">
                </div>
                <div class="input-item">
                    <label>Tiba/Sandar</label>
                    <input type="text" name="berthing_time_urea_${idSuffix}" class="flatpickr-datetime" placeholder="Pilih Tiba/Sandar">
                </div>
                <div class="input-item">
                    <label>Mulai Muat</label>
                    <input type="text" name="start_loading_time_urea_${idSuffix}" class="flatpickr-datetime" placeholder="Pilih Waktu Mulai">
                </div>
            </div>
        </div>

        <!-- CONTAINER LAPORAN HARIAN / TIMELINE (bulk_timesheets) -->
        <div class="laporan-harian">
            <div class="header-laporan-harian">
                <span>Laporan Harian</span>
                <span style="font-size: 11px; font-weight: 300; opacity: 0.8;">Catatan uraian kegiatan</span>
            </div>

            <div class="body-laporan-harian d-flex flex-column align-items-start align-self-stretch">

                <!-- FORM INPUT KEGIATAN -->
                <div class="input-laporan-harian d-flex align-items-center align-self-stretch"
                    style="padding: 15px; gap: 10px; border-bottom: 1px solid var(--border-color); background-color: var(--bg-card);">

                    <input type="text" id="input-datetime-urea-${idSuffix}" class="input-laporan flatpickr-datetime" style="width: 220px !important; flex-shrink: 0;" placeholder="Pilih Waktu">
                    <input type="text" id="input-activity-urea-${idSuffix}" class="input-laporan" style="flex: 1; min-width: 0; width: auto !important;" placeholder="Ketik Aktivitas">
                    <input type="number" id="input-cob-urea-${idSuffix}" class="input-laporan" style="text-align: center; width:100px !important; flex-shrink: 0;" placeholder="COB">

                    <button type="button" id="btn-add-bulk-log-${idSuffix}" class="btn-add-laporan" data-suffix="${idSuffix}">
                        <i class="fa-solid fa-plus" style="color: #FDFDFD; font-size: 14px;"></i>
                    </button>
                </div>

                <!-- LIST / TIMELINE AKTIVITAS -->
                <div class="list-laporan" id="timeline-container-urea-${idSuffix}"></div>

            </div>
        </div>
        `;
    }

    // --- SENIOR FRIENDLY LOGIC START ---

    // 1. Inisialisasi Flatpickr
    function initPickers() {
        // Init elemen statis saja (Prevent Double Init)
        initFlatpickrOnElement(document.body);

        // CONFIG KHUSUS REPORT DATE (INFO UMUM)
        const reportDateInput = document.querySelector("#report_date");
        if (reportDateInput) {
            flatpickr(reportDateInput, {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d/m/Y", // Tetap Format Angka dd/mm/yyyy
                locale: "id",       // Locale ID agar nama bulan di kalender bahasa Indonesia
                disableMobile: false,
                allowInput: true,
                // SET DEFAULT DATE KE WAKTU WITA (Via Carbon)
                defaultDate: "{{ \Carbon\Carbon::now('Asia/Makassar')->format('Y-m-d') }}"
            });
        }

        // Hapus listener lama jika ada (untuk mencegah duplikasi jika dipanggil ulang)
        document.body.removeEventListener('input', handleTimeMasking);
        // Pasang listener global untuk input time masking
        document.body.addEventListener('input', handleTimeMasking);
    }

    // --- GLOBAL TIME MASKING HANDLER ---
    function handleTimeMasking(e) {
        if (e.target.matches('.time-input, .flatpickr-time, .flatpickr-time-only')) {
            let value = e.target.value.replace(/[^0-9]/g, '');
            if (value.length > 4) value = value.substring(0, 4);

            let formatted = value;
            if (value.length >= 3) {
                formatted = value.substring(0, 2) + ':' + value.substring(2);
            }

            if (e.target.value !== formatted) {
                e.target.value = formatted;
            }
        }
    }

    // --- NAVIGATION LOGIC ---
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

    // --- CUSTOM SELECT LOGIC ---
    function setupCustomSelects(specificElement = null) {
        let selects;
        if (specificElement) selects = [specificElement];
        else selects = document.querySelectorAll('select.form-select');

        selects.forEach(select => {
            if (select.nextElementSibling && select.nextElementSibling.classList.contains('custom-select-container')) return;
            // Skip if container logic handles it separately
            // PERBAIKAN: Menambahkan !select.closest('#shift-table-body') agar tabel shift TIDAK di-skip
            if (select.closest('.table') && !select.closest('#container-table-body') && !select.closest('#vehicle-table-body') && !select.closest('#inventory-table-body') && !select.closest('#shelter-table-body') && !select.closest('#shift-table-body')) return;

            const container = document.createElement('div');
            container.className = 'custom-select-container';
            const trigger = document.createElement('div');
            trigger.className = 'custom-select-trigger';
            const selectedText = select.options[select.selectedIndex] ? select.options[select.selectedIndex].text : '-';
            trigger.textContent = selectedText;

            const optionsWrapper = document.createElement('div');
            optionsWrapper.className = 'custom-select-options';
            Array.from(select.options).forEach(option => {
                const optionDiv = document.createElement('div');
                optionDiv.className = 'custom-option';
                optionDiv.textContent = option.text;
                optionDiv.dataset.value = option.value;
                if (option.selected) optionDiv.classList.add('selected');
                optionDiv.addEventListener('click', function() {
                    select.value = this.dataset.value;
                    trigger.textContent = this.textContent;
                    optionsWrapper.querySelectorAll('.custom-option').forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    container.classList.remove('open');
                    // Dispatch change event manually
                    select.dispatchEvent(new Event('change'));
                });
                optionsWrapper.appendChild(optionDiv);
            });
            container.appendChild(trigger);
            container.appendChild(optionsWrapper);
            select.parentNode.insertBefore(container, select.nextSibling);
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                document.querySelectorAll('.custom-select-container').forEach(c => {
                    if (c !== container) c.classList.remove('open');
                });
                container.classList.toggle('open');
            });
        });

        if (!window.customSelectCloseListener) {
            document.addEventListener('click', function(e) {
                document.querySelectorAll('.custom-select-container').forEach(container => {
                    if (!container.contains(e.target)) container.classList.remove('open');
                });
            });
            window.customSelectCloseListener = true;
        }
    }
    document.addEventListener('DOMContentLoaded', () => setupCustomSelects());

    // --- HELPER FUNCTIONS ---
    function setupTimesheet(timeId, activityId, btnAddId, listId, btnSetNowId) {
        const timeInput = document.getElementById(timeId);
        const btnAdd = document.getElementById(btnAddId);
        // Hapus init flatpickr disini karena sudah dihandle initPickers global
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
                let color = listId.includes('loading') ? 'var(--green)' : 'var(--blue-kss)';

                let newItemHTML = `
                    <div class="timesheet-item">
                        <div class="d-flex align-items-start w-100">
                            <div class="ts-dot" style="background-color: ${color};"></div>
                            <div style="display:flex; flex-direction:column; flex:1; gap:5px;">
                                <span class="ts-time-badge" style="color: ${color};">${timeVal}</span>
                                <div class="ts-content">${activityVal}</div>

                                <input type="hidden" name="timesheets[${suffix}][${cat}][${idx}][time]" value="${timeVal}">
                                <input type="hidden" name="timesheets[${suffix}][${cat}][${idx}][activity]" value="${activityVal}">

                            </div>
                            <i class="fa-solid fa-trash-can ts-delete" onclick="this.closest('.timesheet-item').remove()"></i>
                        </div>
                    </div>`;
                document.getElementById(listId).insertAdjacentHTML('beforeend', newItemHTML);
                timeInput.value = '';
                inputActivity.value = '';
            });
        }
    }

    function setupAccumulationLogic(suffix) {
        ['delivery', 'loading', 'damage'].forEach(type => {
            const currentInput = document.querySelector(`input[name="qty_${type}_current_${suffix}"]`);
            const prevInput = document.querySelector(`input[name="qty_${type}_prev_${suffix}"]`);
            const accumSpan = document.querySelector(`.qty_${type}_accumulated_${suffix}`);
            if(currentInput && prevInput && accumSpan) {
                const calculate = () => { accumSpan.textContent = (parseFloat(currentInput.value)||0) + (parseFloat(prevInput.value)||0); };
                currentInput.addEventListener('input', calculate);
                prevInput.addEventListener('input', calculate);
            }
        });
    }

    function setupBulkLog(btnId, datetimeId, activityId, cobId, containerId) {
        const btnAddBulk = document.getElementById(btnId);
        const timelineContainer = document.getElementById(containerId);
        if(btnAddBulk) {
            btnAddBulk.addEventListener('click', function() {
                const datetimeInput = document.getElementById(datetimeId);
                const activityInput = document.getElementById(activityId);
                const cobInput = document.getElementById(cobId);
                const suffix = this.getAttribute('data-suffix');

                if (!datetimeInput.value || !activityInput.value) { alert("Mohon lengkapi data Waktu dan Aktivitas."); return; }

                const newItem = document.createElement('div'); newItem.classList.add('timeline-item');
                const cobHtml = cobInput.value ? `<span class="label-cob">COB : ${cobInput.value}</span>` : '';
                const idx = Date.now();

                newItem.innerHTML = `
                    <div class="timeline-header"><div class="timeline-dot"></div><span class="timeline-time">${datetimeInput.value}</span></div>
                    <div class="timeline-content">${cobHtml}<span class="text-activity">${activityInput.value}</span>
                        <input type="hidden" name="bulk_logs[${suffix}][${idx}][time]" value="${datetimeInput.value}">
                        <input type="hidden" name="bulk_logs[${suffix}][${idx}][activity]" value="${activityInput.value}">
                        <input type="hidden" name="bulk_logs[${suffix}][${idx}][cob]" value="${cobInput.value}">
                    </div>`;
                timelineContainer.insertBefore(newItem, timelineContainer.firstChild);
                activityInput.value = ''; cobInput.value = '';
            });
        }
    }

    // --- TAB SWITCH LOGIC FOR BONGKAR ---
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

    // --- TABLE LOGIC (Bongkar & Turba) ---
    // ============================================
    // UPDATED FOR ARRAY SYNTAX INPUTS
    // ============================================

    let bahanRowCount = 0;
    const bahanTableBody = document.getElementById('bahan-table-body');
    function addBahanRow() {
        bahanRowCount++;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="align-middle row-num">${bahanRowCount}</td>
            <td><input type="text" class="form-control" name="unloading_materials[${bahanRowCount}][raw_material_type]"></td>
            <td><input type="number" class="form-control qty-calc-bahan current" data-row="${bahanRowCount}" name="unloading_materials[${bahanRowCount}][qty_current]" placeholder="0"></td>
            <td><input type="number" class="form-control qty-calc-bahan prev" data-row="${bahanRowCount}" name="unloading_materials[${bahanRowCount}][qty_prev]" placeholder="0"></td>
            <td><input type="number" class="form-control accum" name="unloading_materials[${bahanRowCount}][qty_total]" placeholder="0" readonly style="background-color: var(--table-head-bg);"></td>
            <td class="align-middle"><button type="button" class="btn-delete-row" onclick="removeBahanRow(this)"><i class="fa-solid fa-trash-can"></i></button></td>
        `;
        bahanTableBody.appendChild(tr);
        attachCalcEventBahan(tr);
    }
    function removeBahanRow(btn) {
        if(bahanTableBody.children.length > 1) { btn.closest('tr').remove(); bahanRowCount--; }
        else { btn.closest('tr').querySelectorAll('input').forEach(i => i.value = ''); }
    }
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
    function addContainerRow() {
        containerRowCount++;
        const tr = document.createElement('tr');
        // MODIFIKASI: Menggunakan class 'flatpickr-time-only' dan init khusus agar hanya jam (noCalendar: true)
        tr.innerHTML = `
            <td class="align-middle row-num">${containerRowCount}</td>
            <td><input type="text" class="form-control flatpickr-time-only" name="unloading_containers[${containerRowCount}][time]" placeholder="00:00"></td>
            <td><input type="number" class="form-control qty-calc-cont current" name="unloading_containers[${containerRowCount}][qty_current]" placeholder="0"></td>
            <td><input type="number" class="form-control qty-calc-cont prev" name="unloading_containers[${containerRowCount}][qty_prev]" placeholder="0"></td>
            <td><input type="number" class="form-control accum" name="unloading_containers[${containerRowCount}][qty_total]" placeholder="0" readonly style="background-color: var(--table-head-bg);"></td>
            <td><select class="form-select" name="unloading_containers[${containerRowCount}][status]"><option value="Full">Full</option><option value="Empty">Empty</option></select></td>
            <td class="align-middle"><button type="button" class="btn-delete-row" onclick="removeContainerRow(this)"><i class="fa-solid fa-trash-can"></i></button></td>
        `;
        containerTableBody.appendChild(tr);

        // MODIFIKASI: Pastikan enableTime: true, noCalendar: true, time_24hr: true
        flatpickr(tr.querySelector('.flatpickr-time-only'), {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            disableMobile: false
        });

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
    function addTurbaRow(initialData = null) {
        turbaRowCount++;
        const tr = document.createElement('tr');
        const name = initialData ? initialData.name : '';
        tr.innerHTML = `
            <td class="text-center align-middle row-num">${turbaRowCount}</td>
            <td><input type="text" class="form-control" name="turba_deliveries[${turbaRowCount}][truck_name]" value="${name}" placeholder="Pilih"></td>
            <td><input type="text" class="form-control" name="turba_deliveries[${turbaRowCount}][do_so_number]" placeholder="No. DO"></td>
            <td><input type="number" class="form-control" name="turba_deliveries[${turbaRowCount}][capacity]" placeholder="0"></td>
            <td><input type="text" class="form-control" name="turba_deliveries[${turbaRowCount}][marking_type]" placeholder="Jenis Marking"></td>
            <td><input type="number" class="form-control qty-calc current" data-row="${turbaRowCount}" name="turba_deliveries[${turbaRowCount}][qty_current]" placeholder="0"></td>
            <td><input type="number" class="form-control qty-calc prev" data-row="${turbaRowCount}" name="turba_deliveries[${turbaRowCount}][qty_prev]" placeholder="0"></td>
            <td><input type="number" class="form-control accum" name="turba_deliveries[${turbaRowCount}][qty_accumulated]" placeholder="0" readonly style="background-color: var(--table-head-bg);"></td>
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

    // --- GUDANG CEK UNIT LOGIC ---
    const vehicleData = @json($vehicles ?? []);
    const inventoryData = @json($inventories ?? []);

    // Untuk Shelter Data, biarkan hardcode jika tidak ada tabel masternya
    const shelterData = [
        { category: "KEBERSIHAN :", items: ["Ruangan Shelter", "Halaman Shelter", "Selokan/Parit"] },
        { category: "KERAPIAN :", items: ["Jala-Jala Angkat", "Jala-Jala Lambung", "Terpal", "Chain Sling"] }
    ];

    function renderTables() {
        // Vehicle
        const vehicleBody = document.getElementById('vehicle-table-body');
        vehicleData.forEach((item, index) => {
            vehicleBody.innerHTML += `<tr><td class="text-center">${index + 1}</td><td>${item.name}<input type="hidden" name="unit_logs[${index}][master_unit_id]" value="${item.id}"></td><td><input type="number" step="0.1" name="unit_logs[${index}][fuel_level]" class="form-control" placeholder="0"></td><td><select name="unit_logs[${index}][condition_received]" class="form-select status-select"><option value="" selected disabled>-</option><option value="Baik">Baik</option><option value="Rusak">Rusak</option></select></td><td><select name="unit_logs[${index}][condition_handed_over]" class="form-select status-select"><option value="" selected disabled>-</option><option value="Baik">Baik</option><option value="Rusak">Rusak</option></select></td></tr>`;
        });

        // Inventory
        const inventoryBody = document.getElementById('inventory-table-body');
        inventoryData.forEach((item, index) => {
            const defaultQty = item.qty || 1;
            inventoryBody.innerHTML += `<tr><td class="text-center">${index + 1}</td><td>${item.name}<input type="hidden" name="inventory_logs[${index}][master_inventory_item_id]" value="${item.id}"></td><td><input type="number" name="inventory_logs[${index}][quantity]" class="form-control" value="${defaultQty}"></td><td><select name="inventory_logs[${index}][condition_received]" class="form-select status-select"><option value="" selected disabled>-</option><option value="Baik">Baik</option><option value="Rusak">Rusak</option></select></td><td><select name="inventory_logs[${index}][condition_handed_over]" class="form-select status-select"><option value="" selected disabled>-</option><option value="Baik">Baik</option><option value="Rusak">Rusak</option></select></td></tr>`;
        });

        // Shelter
        const shelterBody = document.getElementById('shelter-table-body');
        let globalIndex = 0;
        shelterData.forEach((group, groupIndex) => {
            shelterBody.innerHTML += `<tr class="category-row"><td class="text-center">${groupIndex + 1}</td><td colspan="3">${group.category}</td></tr>`;
            group.items.forEach((item, itemIndex) => {
                shelterBody.innerHTML += `<tr><td></td><td style="padding-left: 30px;">${item}<input type="hidden" name="shelter_logs[${globalIndex}][item_name]" value="${item}"><input type="hidden" name="shelter_logs[${globalIndex}][category]" value="${group.category.replace(' :', '')}"></td><td><select name="shelter_logs[${globalIndex}][condition_received]" class="form-select status-select"><option value="" selected disabled>-</option><option value="Baik">Baik</option><option value="Rusak">Rusak</option></select></td><td><select name="shelter_logs[${globalIndex}][condition_handed_over]" class="form-select status-select"><option value="" selected disabled>-</option><option value="Baik">Baik</option><option value="Rusak">Rusak</option></select></td></tr>`;
                globalIndex++;
            });
        });
        // Init custom selects for these tables
        const allNewSelects = document.querySelectorAll('#vehicle-table-body select, #inventory-table-body select, #shelter-table-body select');
        allNewSelects.forEach(sel => setupCustomSelects(sel));
    }

    // --- GUDANG KARYAWAN LOGIC ---
    function renderKaryawanTables() {
        // 1. Karyawan Operasi
        const operasiBody = document.getElementById('operasi-table-body');
        for (let i = 1; i <= 7; i++) {
            operasiBody.innerHTML += `<tr><td>${i}</td><td><input type="text" class="form-control" name="lembur_${i}" placeholder="Nama Karyawan"></td><td>${i + 7}</td><td><input type="text" class="form-control" name="relief_${i + 7}" placeholder="Nama Karyawan"></td></tr>`;
        }

        // 2. Karyawan Shift
        // MODIFIED: Dipindahkan ke autoFillEmployees() untuk dynamic rows.
        // Fungsi ini hanya akan dipanggil 1x untuk inisiasi awal jika diperlukan, atau
        // biarkan kosong dulu dan panggil autoFillEmployees() di akhir DOMContentLoaded.
        // Agar konsisten, kita panggil autoFillEmployees() nanti.

        // 3. Kegiatan Lain
        const lainBody = document.getElementById('lain-table-body');
        for (let i = 1; i <= 5; i++) {
            lainBody.innerHTML += `<tr><td><textarea class="form-control" name="kegiatan_desc_${i}" placeholder="Deskripsi kegiatan..."></textarea></td><td><input type="text" class="form-control" name="kegiatan_personil_${i}"></td><td><input type="text" class="form-control flatpickr-time" name="kegiatan_jam_${i}" placeholder="00:00"></td></tr>`;
        }

        // --- TAMBAHAN BARU: GENERATE DEFAULT ROWS FOR OP.7 & PENGGANTI ---
        const op7DataDefaults = [
            { f: "FL.KSS-100", a: "P.6" },
            { f: "FL.KSS-101", a: "Popka" },
            { f: "FL.KSS-102", a: "Bagging-1" },
            { f: "FL.KSS-104", a: "Bagging-1" },
            { f: "FL.KSS-105", a: "Bagging-2" },
            { f: "FL.KSS-106", a: "Bagging-2" },
            { f: "FL.KSS-108", a: "Gudang Produk Tursina" },
            { f: "FL.KSS-109", a: "Blending" },
            { f: "FL.KSS-103", a: "Blending" },
            { f: "FL.KSS-107", a: "Blending" },
            { f: "FL.KSS-110", a: "Blending" }
        ];
        op7DataDefaults.forEach(item => addOp7Row(item.f, item.a));

        for(let i=0; i<3; i++) addReplacementRow();

        // Re-init flatpickr for new inputs
        // flatpickr(".flatpickr-time", { enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, disableMobile: false, allowInput: true });
        initFlatpickrOnElement(lainBody);
    }

    // --- TAMBAHAN BARU: FUNGSI UNTUK OP.7 & PENGGANTI ---
    let op7RowCount = 0;
    function addOp7Row(forkliftVal = '', areaVal = '') {
        const tbody = document.getElementById('op7-table-body');
        if(!tbody) return; // Guard clause
        op7RowCount++;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="text-center align-middle">${op7RowCount}</td>
            <td><input type="text" class="form-control" name="op7_logs[${op7RowCount}][name]" placeholder="Nama"></td>
            <td><input type="text" class="form-control" name="op7_logs[${op7RowCount}][no_forklift_]" value="${forkliftVal}" placeholder="No Forklift"></td>
            <td><input type="text" class="form-control" name="op7_logs[${op7RowCount}][work_area]" value="${areaVal}" placeholder="Area"></td>
            <td><input type="text" class="form-control flatpickr-time" name="op7_logs[${op7RowCount}][time_in]" placeholder="00:00"></td>
            <td><input type="text" class="form-control flatpickr-time" name="op7_logs[${op7RowCount}][time_out]" placeholder="00:00"></td>
            <td><input type="text" class="form-control" name="op7_logs[${op7RowCount}][description]" placeholder="Ket."></td>
            <td class="align-middle text-center"><i class="fa-solid fa-trash-can" style="cursor:pointer; color:var(--redcolor);" onclick="this.closest('tr').remove()"></i></td>
        `;
        tbody.appendChild(tr);
        initFlatpickrOnElement(tr); // Init hanya untuk baris baru
    }

    let replacementRowCount = 0;
    function addReplacementRow() {
        const tbody = document.getElementById('replacement-table-body');
        if(!tbody) return;
        replacementRowCount++;
        const tr = document.createElement('tr');
        // --- MODIFIKASI: MENYAMAKAN KOLOM DENGAN OP7 ---
        tr.innerHTML = `
            <td class="text-center align-middle">${replacementRowCount}</td>
            <td><input type="text" class="form-control" name="replacement_logs[${replacementRowCount}][name]" placeholder="Nama"></td>
            <td><input type="text" class="form-control" name="replacement_logs[${replacementRowCount}][no_forklift_]" placeholder="No Forklift"></td>
            <td><input type="text" class="form-control" name="replacement_logs[${replacementRowCount}][work_area]" placeholder="Area"></td>
            <td><input type="text" class="form-control flatpickr-time" name="replacement_logs[${replacementRowCount}][time_in]" placeholder="00:00"></td>
            <td><input type="text" class="form-control flatpickr-time" name="replacement_logs[${replacementRowCount}][time_out]" placeholder="00:00"></td>
            <td><input type="text" class="form-control" name="replacement_logs[${replacementRowCount}][description]" placeholder="Ket."></td>
            <td class="align-middle text-center"><i class="fa-solid fa-trash-can" style="cursor:pointer; color:var(--redcolor);" onclick="this.closest('tr').remove()"></i></td>
        `;
        tbody.appendChild(tr);
        // --- MODIFIKASI: INIT FLATPICKR UNTUK BARIS BARU ---
        initFlatpickrOnElement(tr);
    }

    function setAllGood(type) {
        let containerId;
        if (type === 'vehicle') containerId = 'vehicle-table-body';
        else if (type === 'inventory') containerId = 'inventory-table-body';
        else if (type === 'shelter') containerId = 'shelter-table-body';

        const selects = document.querySelectorAll(`#${containerId} .status-select`);
        selects.forEach(select => {
            select.value = "Baik";
            const customContainer = select.nextElementSibling;
            if (customContainer && customContainer.classList.contains('custom-select-container')) {
                const trigger = customContainer.querySelector('.custom-select-trigger');
                if (trigger) trigger.textContent = "Baik";
                const options = customContainer.querySelectorAll('.custom-option');
                options.forEach(opt => {
                    if (opt.dataset.value === "Baik") opt.classList.add('selected');
                    else opt.classList.remove('selected');
                });
            }
        });
    }

    // --- INIT SECTIONS & TABLES ---
    ['1', '2', '3', '4'].forEach(seq => {
        const container = document.getElementById(`activity-content-${seq}`);
        if(container) {
            container.innerHTML = getFormHTML(seq);
            setupTimesheet(`time_delivery_${seq}`, `kegiatan_delivery_${seq}`, `btn-add-delivery-${seq}`, `list-delivery-${seq}`, `btn-set-now-delivery-${seq}`);
            setupTimesheet(`time_loading_${seq}`, `kegiatan_loading_${seq}`, `btn-add-loading-${seq}`, `list-loading-${seq}`, `btn-set-now-loading-${seq}`);

            // --- PANGGIL FUNGSI AKUMULASI DI SINI ---
            setupAccumulationLogic(seq);
        }
    });
    ['1', '2'].forEach(seq => {
        const container = document.getElementById(`urea-activity-content-${seq}`);
        if(container) {
            container.innerHTML = getUreaFormHTML(seq);
            setupBulkLog(`btn-add-bulk-log-${seq}`, `input-datetime-urea-${seq}`, `input-activity-urea-${seq}`, `input-cob-urea-${seq}`, `timeline-container-urea-${seq}`);
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        // Default rows for Bongkar
        for(let i=0; i<3; i++) { addBahanRow(); addContainerRow(); }
        // Default rows for Turba
        const initialTurbaRows = [{ name: "Buffer Stock" }, { name: "Buffer Stufing" }, { name: "Buffer Stock" }, { name: "Buffer Stufing" }, { name: "" }];
        initialTurbaRows.forEach(data => addTurbaRow(data));

        // Render Cek Unit & Karyawan
        renderTables();
        renderKaryawanTables();

        // Panggil autoFillEmployees satu kali untuk render default rows (14 baris kosong)
        autoFillEmployees();

        // --- TAMBAHAN EVENT LISTENER UNTUK AUTO FILL ---
        // Pasang listener setelah tabel di-render
        const groupSelect = document.getElementById('group_name');
        const timeRangeSelect = document.getElementById('time_range');
        const shiftSelect = document.getElementById('shift');

        if(groupSelect) {
            groupSelect.addEventListener('change', autoFillEmployees);
        }
        if(timeRangeSelect) {
            timeRangeSelect.addEventListener('change', autoFillEmployees);
        }
        if(shiftSelect) {
            // Saat shift berubah, panggil autoSelectTimeRange
            shiftSelect.addEventListener('change', () => {
                autoSelectTimeRange();
                // Note: autoSelectTimeRange men-trigger 'change' pada timeRangeSelect
                // yang kemudian akan memanggil autoFillEmployees secara berantai.
            });
        }

        // Panggil initPickers terakhir
        initPickers();
    });

    // --- TAB TOGGLE LOGIC (Unified) ---
    document.querySelectorAll('.activities-tab').forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.parentNode;
            parent.querySelectorAll('.activities-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            const seq = this.getAttribute('data-sequence');
            const section = this.closest('.box-form-shift').parentNode;
            if(seq) {
                section.querySelectorAll('.activity-pane').forEach(pane => pane.classList.remove('active'));
                let targetIdPrefix = 'activity-content-';
                if(section.id === 'section-muat-urea') { targetIdPrefix = 'urea-activity-content-'; }
                const targetPane = section.querySelector(`#${targetIdPrefix}${seq}`);
                if(targetPane) targetPane.classList.add('active');
                const seqInput = document.querySelector('input[name="sequence"]');
                if(seqInput) seqInput.value = seq;
            }
        });
    });
</script>
@endpush
