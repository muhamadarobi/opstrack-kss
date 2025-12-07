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
                <button class="nav-link" id="pills-operasi-tab" data-bs-toggle="pill" data-bs-target="#pills-operasi" type="button" role="tab" aria-controls="pills-operasi" aria-selected="false">Karyawan Operasi Relief & Lembur</button>
            </li>
            <!-- TAB Op.7 & Pengganti -->
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-op7-tab" data-bs-toggle="pill" data-bs-target="#pills-op7" type="button" role="tab" aria-controls="pills-op7" aria-selected="false">Op.7 & Pengganti</button>
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
                                <th colspan="4" style="font-size: 14px; background-color: var(--orange-kss);">KARYAWAN OPERASI RELIEF & LEMBUR</th>
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

            <!-- TAB 3: OP.7 & PENGGANTI -->
            <div class="tab-pane fade" id="pills-op7" role="tabpanel" aria-labelledby="pills-op7-tab">

                <!-- Tabel Op.7 -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered text-center align-middle">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th colspan="8" style="font-size: 14px; background-color: var(--orange-kss);">KARYAWAN OP.7</th>
                            </tr>
                            <tr>
                                <th style="width: 40px;">NO.</th>
                                <th>NAMA</th>
                                <th>NO. FORKLIFT</th>
                                <th>AREA KERJA</th>
                                <th style="width: 100px;">MASUK</th>
                                <th style="width: 100px;">KELUAR</th>
                                <th>KETERANGAN</th>
                                <th style="width: 40px;"><i class="fa-solid fa-trash-can"></i></th>
                            </tr>
                        </thead>
                        <tbody id="op7-table-body">
                            <!-- Diisi via JS -->
                        </tbody>
                    </table>
                    <button type="button" class="btn w-100" style="background-color: var(--bg-card); border: 1px dashed var(--border-color); color: var(--blue-kss);" onclick="addOp7Row()">
                        <i class="fa-solid fa-plus"></i> Tambah Baris Op.7
                    </button>
                </div>

                <!-- Tabel Pengganti (Disamakan kolomnya dengan Op.7) -->
                <div class="d-flex align-items-center mb-2 mt-4">
                    <h6 class="mb-0 fw-bold" style="color: var(--redcolor);">Daftar Pengganti Operator yang Tidak Masuk</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th style="width: 40px;">NO.</th>
                                <th>NAMA PENGGANTI</th>
                                <th>NO. FORKLIFT</th>
                                <th>AREA KERJA</th>
                                <th style="width: 100px;">MASUK</th>
                                <th style="width: 100px;">KELUAR</th>
                                <th>MENGGANTIKAN / KET</th>
                                <th style="width: 40px;"><i class="fa-solid fa-trash-can"></i></th>
                            </tr>
                        </thead>
                        <tbody id="replacement-table-body">
                            <!-- Diisi via JS -->
                        </tbody>
                    </table>
                    <button type="button" class="btn w-100" style="background-color: var(--bg-card); border: 1px dashed var(--border-color); color: var(--redcolor);" onclick="addReplacementRow()">
                        <i class="fa-solid fa-plus"></i> Tambah Baris Pengganti
                    </button>
                </div>
            </div>

            <!-- TAB 4: LAIN-LAIN -->
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
