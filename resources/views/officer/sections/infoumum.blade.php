        <!-- SECTIONS -->
        <div id="section-info-umum" class="form-section active">
            <div class="box-info-umum d-flex align-items-center align-content-center align-self-stretch flex-wrap">
                <div class="box-input d-flex flex-column align-items-start">
                    <label for="report_date">Hari / Tanggal</label>
                    <input type="text" name="report_date" id="report_date" placeholder="dd/mm/yyyy" style="cursor: pointer;">
                </div>
                <div class="box-input d-flex flex-column align-items-start">
                    <label for="shift">Shift</label>
                    <select class="form-select" name="shift" id="shift">
                        <option value="" selected disabled>Pilih Shift</option>
                        <option value="Pagi">Pagi</option>
                        <option value="Sore">Sore</option>
                        <option value="Malam">Malam</option>
                    </select>
                </div>
                <div class="box-input d-flex flex-column align-items-start">
                    <label for="group">Group / Regu</label>
                    <select class="form-select" name="group_name" id="group_name">
                        <option value="" selected disabled>Pilih Group</option>
                        <option value="A">Group A</option>
                        <option value="B">Group B</option>
                        <option value="C">Group C</option>
                        <option value="D">Group D</option>
                    </select>
                </div>
                <div class="box-input d-flex flex-column align-items-start">
                    <label for="time">Waktu Shift</label>
                    <select class="form-select" name="time_range" id="time_range">
                        <option value="" selected disabled>Pilih Waktu Shift</option>
                        <option value="07-15">07.00 - 15.00</option>
                        <option value="15-23">15.00 - 23.00</option>
                        <option value="23-07">23.00 - 07.00</option>
                    </select>
                </div>

                <!-- INPUT BARU: Diterima Oleh Group -->
                <div class="box-input d-flex flex-column align-items-start">
                    <label for="received_by_group">Diterima Oleh Group</label>
                    <select class="form-select" name="received_by_group" id="received_by_group">
                        <option value="" selected disabled>Pilih Group</option>
                        <option value="A">Group A</option>
                        <option value="B">Group B</option>
                        <option value="C">Group C</option>
                        <option value="D">Group D</option>
                    </select>
                </div>
            </div>

            <div class="button-form d-flex justify-content-between align-items-center align-self-stretch">
                <a href="{{ route('reports.history') }}" class="btn cancel">Batal</a>
                <div class="btn next" onclick="showSection('section-muat-kantong')">Lanjut</div>
            </div>
        </div>
