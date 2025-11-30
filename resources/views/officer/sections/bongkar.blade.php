<!-- SECTION BONGKAR (MODIFIED WITH REQUESTED STRUCTURE) -->
<div id="section-bongkar" class="form-section">
    <div id="box-unloading-activites" class="box-form-shift d-flex flex-column align-items-start align-self-stretch" style="gap: 20px; padding: 30px; background-color: var(--bg-card); border-radius: 16px; box-shadow: 0px 4px 20px 0px rgba(0, 0, 0, 0.05);">
        <span class="title-form" style="font-size: 18px; font-weight: 700; color: var(--text-main); text-transform: uppercase;">iii. bongkar bahan baku / containter</span>

        <div class="button-tab d-flex align-items-center align-content-center align-self-stretch flex-wrap" style="gap: 15px;">
            <a id="btn-tab-bahan-baku" class="unload-tab active material btn" style="background-color: var(--blue-kss); color: white; padding: 10px 20px; border-radius: 8px; cursor: pointer;" onclick="switchBongkarTab('bahan')">bongkar bahan baku</a>
            <a id="btn-tab-container" class="unload-tab material btn" style="background-color: var(--green);  color: white; padding: 10px 20px; border-radius: 8px; cursor: pointer;" onclick="switchBongkarTab('container')">bongkar container</a>
        </div>

        <!-- CONTENT 1: BONGKAR BAHAN BAKU -->
        <div id="content-bongkar-bahan" class="d-flex flex-column w-100">
            <div class="loading-info mt-3 mb-3" style="background-color: rgba(0, 119, 194, 0.05); border-radius: 10px; padding: 15px; border: 1px solid rgba(0, 119, 194, 0.2);">
                <div class="input-bulk-loading d-flex flex-wrap" style="gap: 15px;">
                    <div class="input-item" style="flex: 1;">
                        <label>Nama Kapal</label>
                        <!-- Change Name to match controller -->
                        <input type="text" name="ship_name_material" class="form-control" placeholder="Nama Kapal">
                    </div>
                    <div class="input-item" style="flex: 1;">
                        <label>Agent</label>
                        <!-- Change Name to match controller -->
                        <input type="text" name="agent_material" class="form-control" placeholder="Nama Agent">
                    </div>
                    <div class="input-item" style="flex: 1;">
                        <label>Kapasitas</label>
                        <!-- Change Name to match controller -->
                        <input type="number" name="capacity_material" class="form-control" placeholder="0">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mb-2">
                <button type="button" class="btn-add-jenis btn btn-sm btn-primary" onclick="addBahanRow()">
                    <i class="fa-solid fa-plus"></i> Tambah Baris
                </button>
            </div>

            <div class="table-responsive w-100 mt-0">
                <table class="table table-bordered text-center table-turba">
                    <thead style="background-color: var(--table-head-bg);">
                        <tr>
                            <th style="width: 50px;">NO.</th>
                            <th>JENIS</th>
                            <th style="width: 150px;">SEKARANG</th>
                            <th style="width: 150px;">LALU</th>
                            <th style="width: 150px;">TOTAL</th>
                            <th style="width: 50px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="bahan-table-body"></tbody>
                </table>
            </div>

            <!-- PETUGAS SECTION (BAHAN BAKU) - MOVED INSIDE TAB -->
            <div class="petugas-information d-flex flex-column align-items-start align-self-stretch mt-4" style="gap: 10px;">
                <span class="title-petugas-information" style="font-weight: 700; text-transform: uppercase; font-size: 14px;">PETUGAS (BAHAN BAKU)</span>
                <div class="box-input-material-info d-flex flex-column align-items-start align-self-stretch"
                    style="gap: 10px; padding: 15px; border-radius: 10px; border: 1px solid var(--border-color); background-color: var(--bg-input);">

                    <div class="input-material-wrapper d-flex align-items-center align-content-center align-self-stretch flex-wrap" style="gap: 20px;">
                        <div class="input-material-info d-flex flex-column align-items-start align-self-stretch" style="gap: 6px; flex: 1 0 0; min-width: 200px;">
                            <label>Tally Kapal</label>
                            <!-- Change Name to match controller -->
                            <input type="text" name="material_ship_tally_names" class="form-control" placeholder="Nama Tally">
                        </div>
                        <div class="input-material-info d-flex flex-column align-items-start align-self-stretch" style="gap: 6px; flex: 1 0 0; min-width: 200px;">
                            <label>Operator Forklift</label>
                            <!-- Change Name to match controller -->
                            <input type="text" name="material_forklift_operator_names" class="form-control" placeholder="Nama Operator">
                        </div>
                    </div>

                    <div class="input-material-wrapper d-flex align-items-center align-content-center align-self-stretch flex-wrap" style="gap: 20px;">
                        <div class="input-material-info d-flex flex-column align-items-start align-self-stretch" style="gap: 6px; flex: 1 0 0; min-width: 200px;">
                            <label>Tally Gudang</label>
                            <!-- Change Name to match controller -->
                            <input type="text" name="material_delivery_tally_names" class="form-control" placeholder="Nama Tally">
                        </div>
                        <div class="input-material-info d-flex flex-column align-items-start align-self-stretch" style="gap: 6px; flex: 1 0 0; min-width: 200px;">
                            <label>Driver</label>
                            <!-- Change Name to match controller -->
                            <input type="text" name="material_driver_names" class="form-control" placeholder="Nama Driver">
                        </div>
                        <div class="input-material-info d-flex flex-column align-items-start align-self-stretch" style="gap: 6px; flex: 1 0 0; min-width: 200px;">
                            <label>Jam Kerja</label>
                            <!-- Change Name to match controller -->
                            <input type="text" name="material_working_hours" class="form-control arrival-time-picker" placeholder="00:00 - 00:00">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTENT 2: BONGKAR CONTAINER -->
        <div id="content-bongkar-container" class="d-flex flex-column w-100 d-none">
            <div class="loading-info mt-3 mb-3" style="background-color: rgba(25, 135, 84, 0.05); border-radius: 10px; padding: 15px; border: 1px solid rgba(25, 135, 84, 0.2);">
                <div class="input-bulk-loading d-flex flex-wrap" style="gap: 15px;">
                    <div class="input-item" style="flex: 1;">
                        <label>Nama Kapal</label>
                        <!-- Change Name to match controller -->
                        <input type="text" name="ship_name_container" class="form-control" placeholder="Nama Kapal" >
                    </div>
                    <div class="input-item" style="flex: 1;">
                        <label>Agent</label>
                        <!-- Change Name to match controller -->
                        <input type="text" name="agent_container" class="form-control" placeholder="Agent Pelayaran">
                    </div>
                    <!-- SINGLE CAPACITY FIELD -->
                    <div class="input-item" style="flex: 1;">
                        <label>Kapasitas</label>
                        <input type="text" name="capacity_container" class="form-control" placeholder="Total Kapasitas">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mb-2">
                <button type="button" class="btn-add-jenis btn btn-sm btn-success" style="background-color: var(--green); border: none;" onclick="addContainerRow()">
                    <i class="fa-solid fa-plus"></i> Tambah Baris
                </button>
            </div>

            <div class="table-responsive w-100 mt-0">
                <table class="table table-bordered text-center table-turba">
                    <thead>
                        <tr style="background-color: var(--green) !important;">
                            <th style="background-color: var(--green); color: white; width: 50px;">NO.</th>
                            <th style="background-color: var(--green); color: white;">JAM</th>
                            <th style="background-color: var(--green); color: white; width: 120px;">SEKARANG</th>
                            <th style="background-color: var(--green); color: white; width: 120px;">LALU</th>
                            <th style="background-color: var(--green); color: white; width: 120px;">TOTAL</th>
                            <th style="background-color: var(--green); color: white; width: 150px;">KET</th>
                            <th style="background-color: var(--green); color: white; width: 50px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="container-table-body"></tbody>
                </table>
            </div>

            <!-- PETUGAS SECTION (CONTAINER) - MOVED INSIDE TAB & CUSTOMIZED -->
            <div class="petugas-information d-flex flex-column align-items-start align-self-stretch mt-4" style="gap: 10px;">
                <span class="title-petugas-information" style="font-weight: 700; text-transform: uppercase; font-size: 14px;">PETUGAS (CONTAINER)</span>
                <div class="box-input-material-info d-flex flex-column align-items-start align-self-stretch"
                    style="gap: 10px; padding: 15px; border-radius: 10px; border: 1px solid var(--border-color); background-color: var(--bg-input);">

                    <div class="input-material-wrapper d-flex align-items-center align-content-center align-self-stretch flex-wrap" style="gap: 20px;">
                        <!-- Mapped to ship_tally_names (Tally Muat) -->
                        <div class="input-material-info d-flex flex-column align-items-start align-self-stretch" style="gap: 6px; flex: 1 0 0; min-width: 200px;">
                            <label>Tally Muat</label>
                            <!-- Change Name to match controller -->
                            <input type="text" name="container_ship_tally_names" class="form-control" placeholder="Nama Tally Muat">
                        </div>
                        <!-- Mapped to delivery_tally_names (Tally Gudang) -->
                        <div class="input-material-info d-flex flex-column align-items-start align-self-stretch" style="gap: 6px; flex: 1 0 0; min-width: 200px;">
                            <label>Tally Gudang</label>
                            <!-- Change Name to match controller -->
                            <input type="text" name="container_gudang_tally_names" class="form-control" placeholder="Nama Tally Gudang">
                        </div>
                        <div class="input-material-info d-flex flex-column align-items-start align-self-stretch" style="gap: 6px; flex: 1 0 0; min-width: 200px;">
                            <label>Tally Driver</label>
                            <!-- Change Name to match controller -->
                            <input type="text" name="container_driver_names" class="form-control" placeholder="Nama Tally Driver">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="button-form d-flex justify-content-between align-items-center align-self-stretch mt-3">
        <div class="btn previous" style="background-color: #6c757d; color: white;" onclick="showSection('section-muat-urea')">Kembali</div>
        <div class="btn save" style="background-color: var(--blue-kss); color: white;" onclick="showSection('section-gudang-turba')">Lanjut</div>
    </div>
</div>
