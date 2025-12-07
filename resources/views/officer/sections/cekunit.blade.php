<div id="section-gudang-cek-unit" class="form-section">
    <div class="box-form-shift d-flex flex-column align-items-start align-self-stretch">
        <div class="d-flex justify-content-between align-items-center align-self-stretch border-bottom pb-3">
            <span class="title-form" style="border-bottom: none; padding:0;">
                v. gudang cek unit & inventaris
            </span>
        </div>

        <ul class="nav nav-pills mb-3 mt-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-vehicle-tab" data-bs-toggle="pill" data-bs-target="#pills-vehicle" type="button" role="tab" aria-controls="pills-vehicle" aria-selected="true">Unit Kendaraan</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-inventory-tab" data-bs-toggle="pill" data-bs-target="#pills-inventory" type="button" role="tab" aria-controls="pills-inventory" aria-selected="false">Inventaris</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-shelter-tab" data-bs-toggle="pill" data-bs-target="#pills-shelter" type="button" role="tab" aria-controls="pills-shelter" aria-selected="false">Lingkungan Shelter</button>
            </li>
        </ul>

        <div class="tab-content w-100" id="pills-tabContent">
            <!-- TAB KENDARAAN -->
            <div class="tab-pane fade show active" id="pills-vehicle" role="tabpanel" aria-labelledby="pills-vehicle-tab">
                <div class="d-flex justify-content-end mb-2">
                    <button type="button" class="btn-set-all" onclick="setAllGood('vehicle')">
                        <i class="fas fa-check-double"></i> Set Semua Baik
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 40px;">NO.</th>
                                <th rowspan="2">NAMA ALAT</th>
                                <th rowspan="2" style="width: 120px;">ISI BBM (LITER)</th>
                                <th colspan="2">KONDISI UNIT</th>
                            </tr>
                            <tr>
                                <th style="width: 150px;">TERIMA</th>
                                <th style="width: 150px;">DISERAHKAN</th>
                            </tr>
                        </thead>
                        <tbody id="vehicle-table-body"></tbody>
                    </table>
                </div>
            </div>

            <!-- TAB INVENTARIS -->
            <div class="tab-pane fade" id="pills-inventory" role="tabpanel" aria-labelledby="pills-inventory-tab">
                <div class="d-flex justify-content-end mb-2">
                    <button type="button" class="btn-set-all" onclick="setAllGood('inventory')">
                        <i class="fas fa-check-double"></i> Set Semua Baik
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 40px;">NO.</th>
                                <th rowspan="2">NAMA BARANG</th>
                                <th rowspan="2" style="width: 120px;">JUMLAH BARANG</th>
                                <th colspan="2">KONDISI</th>
                            </tr>
                            <tr>
                                <th style="width: 150px;">TERIMA</th>
                                <th style="width: 150px;">DISERAHKAN</th>
                            </tr>
                        </thead>
                        <tbody id="inventory-table-body"></tbody>
                    </table>
                </div>
            </div>

            <!-- TAB LINGKUNGAN SHELTER -->
            <div class="tab-pane fade" id="pills-shelter" role="tabpanel" aria-labelledby="pills-shelter-tab">
                <div class="d-flex justify-content-end mb-2">
                    <button type="button" class="btn-set-all" onclick="setAllGood('shelter')">
                        <i class="fas fa-check-double"></i> Set Semua Baik
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 40px;">NO.</th>
                                <th rowspan="2">NAMA BARANG / LINGKUNGAN</th>
                                <th colspan="2">KONDISI</th>
                            </tr>
                            <tr>
                                <th style="width: 150px;">TERIMA</th>
                                <th style="width: 150px;">DISERAHKAN</th>
                            </tr>
                        </thead>
                        <tbody id="shelter-table-body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="button-form d-flex justify-content-between align-items-center align-self-stretch">
        <div class="btn previous" onclick="showSection('section-gudang-turba')">Kembali</div>
        <div class="btn save" onclick="showSection('section-gudang-karyawan')">Lanjut</div>
    </div>
</div>
