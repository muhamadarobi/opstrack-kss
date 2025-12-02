<!-- SECTIONS -->
        <div id="section-info-umum" class="form-section active">
            <div class="box-info-umum d-flex align-items-center align-content-center align-self-stretch flex-wrap">

                <!-- Input Tanggal -->
                <div class="box-input d-flex flex-column align-items-start">
                    <label for="report_date">Hari / Tanggal</label>
                    <input type="text" name="report_date" id="report_date" placeholder="dd/mm/yyyy" style="cursor: pointer;">
                </div>

                <!-- Input Shift -->
                <div class="box-input d-flex flex-column align-items-start">
                    <label for="shift">Shift</label>
                    <select class="form-select" name="shift" id="shift">
                        <option value="" selected disabled>Pilih Shift</option>
                        <option value="Pagi">Pagi</option>
                        <option value="Sore">Sore</option>
                        <option value="Malam">Malam</option>
                    </select>
                </div>

                <!-- Input Group (OTOMATIS TERISI) -->
                <div class="box-input d-flex flex-column align-items-start">
                    <label for="group">Group / Regu</label>

                    @php
                        // Ambil group dari user login, ubah ke Huruf Besar (A, B, C, D)
                        // Asumsi di database tersimpan 'a', 'b', 'c', 'd'
                        $userGroup = strtoupper(auth()->user()->group ?? '');
                    @endphp

                    {{--
                        Logic:
                        1. Jika user punya group, set background jadi abu-abu (readonly look).
                        2. Gunakan pointer-events: none agar tidak bisa diklik ganti (tapi data tetap terkirim).
                    --}}
                    <select class="form-select" name="group_name" id="group_name"
                            style="{{ !empty($userGroup) ? 'background-color: #e9ecef; pointer-events: none;' : '' }}"
                            tabindex="{{ !empty($userGroup) ? '-1' : '0' }}">

                        <option value="" {{ empty($userGroup) ? 'selected' : '' }} disabled>Pilih Group</option>
                        <option value="A" {{ $userGroup === 'A' ? 'selected' : '' }}>Group A</option>
                        <option value="B" {{ $userGroup === 'B' ? 'selected' : '' }}>Group B</option>
                        <option value="C" {{ $userGroup === 'C' ? 'selected' : '' }}>Group C</option>
                        <option value="D" {{ $userGroup === 'D' ? 'selected' : '' }}>Group D</option>
                    </select>

                    {{--
                        HACK: Karena pointer-events:none kadang membuat form tidak mengirim value di browser tertentu,
                        kita buat hidden input cadangan jika userGroup ada, untuk memastikan data terkirim.
                    --}}
                    @if(!empty($userGroup))
                        <input type="hidden" name="group_name" value="{{ $userGroup }}">
                    @endif
                </div>

                <!-- Input Waktu Shift -->
                <div class="box-input d-flex flex-column align-items-start">
                    <label for="time">Waktu Shift</label>
                    <select class="form-select" name="time_range" id="time_range">
                        <option value="" selected disabled>Pilih Waktu Shift</option>
                        <option value="07.00 - 15.00">07.00 - 15.00</option>
                        <option value="15.00 - 23.00">15.00 - 23.00</option>
                        <option value="23.00 - 07.00">23.00 - 07.00</option>
                    </select>
                </div>

                <!-- Input Diterima Oleh Group -->
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
                <a href="{{ route('reports.index') }}" class="btn cancel">Batal</a>
                <div class="btn next" onclick="showSection('section-muat-kantong')">Lanjut</div>
            </div>
        </div>
