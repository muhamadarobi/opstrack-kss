<div id="section-gudang-turba" class="form-section">
    <div id="box-delivery-activities" class="box-form-shift d-flex flex-column align-items-start align-self-stretch">
        <span class="title-form">
            iv. pengiriman pupuk kantong ke gudang turba
        </span>

        <!-- INFO PETUGAS -->
        <div class="box-input-deliv d-flex flex-column align-items-start align-self-stretch" style="padding: 10px 0; gap: 5px;">
            <div class="input-wrapper d-flex align-items-center align-content-center align-self-stretch flex-wrap" style="gap: 20px;">
                <div class="input-deliv-info d-flex flex-column justify-content-center align-items-start" style="gap: 5px; flex: 1 0 0; min-width: 300px;">
                    <label>tally gudang</label>
                    <input type="text" name="tally_gudang_names" placeholder="Nama Tally">
                </div>
                <div class="input-deliv-info d-flex flex-column justify-content-center align-items-start" style="gap: 5px; flex: 1 0 0; min-width: 300px;">
                    <label>Operator Forklift</label>
                    <!-- Note: Nama input ini bentrok dengan bongkar di Controller, pastikan unik di controller atau disini -->
                    <input type="text" name="turba_forklift_operator" placeholder="Nama Operator">
                </div>
            </div>
            <div class="input-wrapper d-flex align-items-center align-content-center align-self-stretch flex-wrap" style="gap: 20px;">
                <div class="input-deliv-info d-flex flex-column justify-content-center align-items-start" style="gap: 5px; flex: 1 0 0; min-width: 300px;">
                    <label>driver</label>
                    <input type="text" name="turba_driver_names" placeholder="Nama Driver">
                </div>
                <div class="input-deliv-info d-flex flex-column justify-content-center align-items-start" style="gap: 5px; flex: 1 0 0; min-width: 300px;">
                    <label>jam kerja</label>
                    <input type="text" name="turba_working_hours" class="flatpickr-time-only" placeholder="00:00">
                </div>
            </div>
        </div>

        <!-- LIST KARTU INFORMASI PENGIRIMAN -->
        <div class="delivery-information d-flex flex-column align-items-start align-self-stretch" style="gap: 15px;">
            <div class="d-flex justify-content-between align-items-center align-self-stretch">
                <span class="title-delivery-information" style="font-weight: 700; text-transform: uppercase; font-size: 14px;">
                    Informasi Pengiriman
                </span>
                <button type="button" class="btn-add-jenis" onclick="addTurbaRow()">
                    <i class="fa-solid fa-plus"></i> Tambah Baris
                </button>
            </div>

            <div class="table-responsive w-100">
                <table class="table table-bordered text-center table-turba">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 50px;">NO.</th>
                            <th rowspan="2" style="min-width: 200px;">NAMA TRUCK</th>
                            <th colspan="2">DO / SO</th>
                            <th rowspan="2" style="min-width: 150px;">JENIS MARKING</th>
                            <th colspan="3">TERKIRIM</th>
                            <th rowspan="2" style="width: 50px;">AKSI</th>
                        </tr>
                        <tr>
                            <th style="min-width: 120px;">NOMOR</th>
                            <th style="min-width: 100px;">KAPASITAS</th>
                            <th style="min-width: 100px;">SEKARANG</th>
                            <th style="min-width: 100px;">LALU</th>
                            <th style="min-width: 100px;">AKUMULASI</th>
                        </tr>
                    </thead>
                    <tbody id="turba-table-body"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="button-form d-flex justify-content-between align-items-center align-self-stretch">
        <div class="btn previous" onclick="showSection('section-bongkar')">Kembali</div>
        <div class="btn save" onclick="showSection('section-gudang-cek-unit')">Lanjut</div>
    </div>
</div>
