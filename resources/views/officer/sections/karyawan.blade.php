<div id="section-gudang-karyawan" class="form-section">
    <div class="box-form-shift d-flex flex-column align-items-start align-self-stretch">
        <div class="d-flex justify-content-between align-items-center align-self-stretch border-bottom pb-3">
            <span class="title-form" style="border-bottom: none; padding:0;">
                vi. gudang karyawan
            </span>
        </div>

        <ul class="nav nav-pills mb-3 mt-3" id="pills-karyawan-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-shift-tab" data-bs-toggle="pill" data-bs-target="#pills-shift" type="button" role="tab" aria-controls="pills-shift" aria-selected="true">Karyawan Shift</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-operasi-tab" data-bs-toggle="pill" data-bs-target="#pills-operasi" type="button" role="tab" aria-controls="pills-operasi" aria-selected="false">Karyawan Operasi</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-lain-tab" data-bs-toggle="pill" data-bs-target="#pills-lain" type="button" role="tab" aria-controls="pills-lain" aria-selected="false">Lain-lain</button>
            </li>
        </ul>

        <div class="tab-content w-100" id="pills-karyawanContent">
            <!-- TAB 1: KARYAWAN SHIFT -->
            <div class="tab-pane fade show active" id="pills-shift" role="tabpanel" aria-labelledby="pills-shift-tab">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="5" style="font-size: 14px; background-color: var(--green);">KARYAWAN SHIFT YANG BERTUGAS</th>
                            </tr>
                            <tr>
                                <th style="width: 60px;">NO.</th>
                                <th>NAMA</th>
                                <th style="width: 150px;">MASUK</th>
                                <th style="width: 150px;">PULANG</th>
                                <th style="width: 200px;">KET</th>
                            </tr>
                        </thead>
                        <tbody id="shift-table-body"></tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 2: KARYAWAN OPERASI -->
            <div class="tab-pane fade" id="pills-operasi" role="tabpanel" aria-labelledby="pills-operasi-tab">
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th colspan="4" style="font-size: 14px; background-color: var(--orange-kss);">KARYAWAN OPERASI</th>
                            </tr>
                            <tr>
                                <th style="width: 60px;">NO.</th>
                                <th>LEMBUR</th>
                                <th style="width: 60px;">NO.</th>
                                <th>RELIEF MALAM</th>
                            </tr>
                        </thead>
                        <tbody id="operasi-table-body"></tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 3: LAIN-LAIN -->
            <div class="tab-pane fade" id="pills-lain" role="tabpanel" aria-labelledby="pills-lain-tab">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="background-color: var(--blue-kss-dark);">KEGIATAN LAIN</th>
                                <th style="width: 300px; background-color: var(--blue-kss-dark);">PERSONIL</th>
                                <th style="width: 150px; background-color: var(--blue-kss-dark);">JAM KERJA</th>
                            </tr>
                        </thead>
                        <tbody id="lain-table-body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="button-form d-flex justify-content-between align-items-center align-self-stretch">
        <div class="btn previous" onclick="showSection('section-gudang-cek-unit')">Kembali</div>
        <button type="submit" class="btn save">Simpan</button>
    </div>
</div>
