@extends('admin.layouts.master')

@section('title','User Operasional')

@section('content')
@include('admin.style.penggunastyle')

{{-- Style Tambahan --}}
@push('styles')
<style>
    .badge-status { padding: 5px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; }
    .badge-aktif { background-color: rgba(25, 135, 84, 0.1); color: #198754; }
    .badge-nonaktif { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }

    /* TOAST CUSTOM STYLE */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }
    .toast {
        background-color: #fff;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-radius: 8px;
        overflow: hidden;
        min-width: 300px;
    }
    .toast-header {
        background-color: transparent;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 10px 15px;
    }
    .toast-body {
        padding: 12px 15px;
        font-size: 14px;
        color: #333;
    }
    .toast-success .toast-header { color: #198754; }
    .toast-success { border-left: 5px solid #198754; }
    .toast-error .toast-header { color: #dc3545; }
    .toast-error { border-left: 5px solid #dc3545; }

    /* TOGGLE SWITCH STYLE */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 22px;
    }
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }
    input:checked + .slider {
        background-color: var(--blue-kss) !important;
    }
    input:focus + .slider {
        box-shadow: 0 0 1px #198754;
    }
    input:checked + .slider:before {
        -webkit-transform: translateX(18px);
        -ms-transform: translateX(18px);
        transform: translateX(18px);
    }
    .slider.round {
        border-radius: 34px;
    }
    .slider.round:before {
        border-radius: 50%;
    }

    /* BUTTON COPY STYLE (Untuk Tabel) */
    .btn-copy {
        background-color: var(--blue-kss);
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: background 0.2s;
    }
    .btn-copy:hover {
        background-color: #005f9e;
    }

    /* STYLING TOMBOL MATA PASSWORD (Toggle Show/Hide) */
    .btn-toggle-password {
        border: 1px solid var(--gray-border);
        border-left: none;
        background-color: var(--input-bg);
        color: var(--text-secondary);
        /* Tidak ada border radius kanan karena ada tombol copy di sebelahnya */
        display: flex;
        align-items: center;
        padding: 0 12px;
    }
    .btn-toggle-password:hover {
        background-color: var(--hover-menu-bg);
        color: var(--black-color);
    }

    /* STYLING TOMBOL COPY PASSWORD (Di dalam Input Group) */
    .btn-copy-password-input {
        border: 1px solid var(--gray-border);
        border-left: none;
        background-color: var(--input-bg);
        color: var(--blue-kss);
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
        display: flex;
        align-items: center;
        padding: 0 12px;
        transition: all 0.2s;
    }
    .btn-copy-password-input:hover {
        background-color: var(--blue-kss);
        color: white;
    }

    .input-group .form-control {
        border-right: none; /* Agar menyatu dengan tombol mata */
    }
</style>
@endpush

    <!-- TOAST CONTAINER -->
    <div class="toast-container">
        @if(session('success'))
        <div class="toast toast-success show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="toast-header">
                <strong class="me-auto"><i class="fas fa-check-circle me-2"></i>Berhasil</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">{{ session('success') }}</div>
        </div>
        @endif

        @if(session('error'))
        <div class="toast toast-error show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="toast-header">
                <strong class="me-auto"><i class="fas fa-exclamation-circle me-2"></i>Gagal</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">{{ session('error') }}</div>
        </div>
        @endif

        @if($errors->any())
        <div class="toast toast-error show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="7000">
            <div class="toast-header">
                <strong class="me-auto"><i class="fas fa-exclamation-triangle me-2"></i>Perhatian</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>

    <!-- Content -->
    <div class="content-page d-flex flex-column align-items-center justify-content-center align-self-stretch" style="padding: 0px 25px 25px 25px; gap: 10px;">
        <div class="header-content align-self-stretch">
            <h1 class="title-page">Manajemen Pengguna</h1>
        </div>

        <div class="data-content-wrapper d-flex flex-column align-items-start align-self-stretch" style="width: 100%;">

            <div class="action-bar d-flex justify-content-between align-self-stretch mb-3">
                <button class="btn-add" data-bs-toggle="modal" data-bs-target="#ModalAddUser">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                        <path d="M4.5 6.99902C6.98405 7.00177 8.99712 9.01493 9 11.499C9 11.7752 8.77613 11.999 8.5 11.999H0.5C0.223859 11.999 0 11.7752 0 11.499C0.00290797 9.01493 2.01595 7.00179 4.5 6.99902ZM10 3.99902C10.2761 3.99907 10.5 4.22293 10.5 4.49902V5.49902H11.5C11.7761 5.49907 11.9999 5.72294 12 5.99902C12 6.27515 11.7761 6.49898 11.5 6.49902H10.5V7.49902C10.5 7.77515 10.2761 7.99898 10 7.99902C9.72386 7.99902 9.5 7.77517 9.5 7.49902V6.49902H8.5C8.22386 6.49902 8 6.27517 8 5.99902C8.00005 5.72292 8.22389 5.49902 8.5 5.49902H9.5V4.49902C9.50003 4.2229 9.72388 3.99902 10 3.99902ZM4.5 0C6.15685 0 7.5 1.3431 7.5 3C7.49978 4.65672 6.15672 6 4.5 6C2.84328 6 1.50022 4.65672 1.5 3C1.5 1.3431 2.84315 0 4.5 0Z" fill="white"/>
                    </svg>
                    Tambah
                </button>

                <form action="{{ route('admin.pengguna') }}" method="GET" class="d-flex" style="gap: 10px;">
                    <input type="text" name="cari" class="form-control" placeholder="Cari Nama/Username..." value="{{ request('cari') }}">
                    <button type="submit" class="btn btn-secondary" style="border-radius: 10px; font-size:14px; width:100px">Cari</button>
                </form>
            </div>

            <div class="document-table d-flex flex-column align-items-start align-self-stretch">
                <div class="box-title d-flex flex-column align-items-start align-self-stretch" style="padding: 15px;">
                    <span class="title-table">Tabel User</span>
                </div>
                <table class="table">
                    <thead>
                        <tr class="head d-flex align-items-center align-self-stretch">
                            <th class="number">No</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr class="body d-flex align-items-center align-self-stretch">
                            <td class="number">{{ $users->firstItem() + $index }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->role->name ?? '-' }}</td>
                            <td>
                                @if($user->status == 'aktif')
                                    <span class="badge-status badge-aktif">Aktif</span>
                                @else
                                    <span class="badge-status badge-nonaktif">Nonaktif</span>
                                @endif
                            </td>
                            <td class="aksi d-flex gap-2">
                                <button class="btn-copy" onclick="copyToClipboard('{{ $user->username }}')" title="Copy Username">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                                <button class="btn-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#ModalEditUser"
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    data-username="{{ $user->username }}"
                                    data-role="{{ $user->role_id }}"
                                    data-status="{{ $user->status }}">
                                    Edit
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr class="body d-flex align-items-center align-self-stretch">
                            <td colspan="6" class="text-center w-100 p-3">Tidak ada data pengguna.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div style="padding: 15px; width: 100%;">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <!-- MODAL TAMBAH USER -->
    <div class="modal fade" id="ModalAddUser" tabindex="-1" aria-labelledby="ModalAddUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalAddUserLabel">Buat Pengguna Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <span style="font-size: 14px; font-weight: 600; display: block; margin-bottom: 10px; color: var(--black-color);">Informasi Akun</span>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" id="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" class="form-control" id="password" required>

                                <!-- Tombol Mata (Toggle Show/Hide) -->
                                <button class="btn btn-toggle-password" type="button" tabindex="-1">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <!-- Tombol Copy Password -->
                                <button class="btn btn-copy-password-input" type="button" onclick="copyInputText('password')" title="Copy Password">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <div class="form-text text-muted" style="font-size: 11px;">
                                Klik tombol copy untuk menyalin password yang sedang diketik.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select name="role_id" class="form-select" id="role" required>
                                <option value="" disabled selected>Pilih Role...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <span style="font-size: 14px; font-weight: 600; display: block; margin-bottom: 10px; margin-top: 20px; color: var(--black-color);">Informasi Pribadi</span>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" id="nama" required>
                        </div>
                        <button type="submit" class="btn-submit-modal">Buat Akun</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT USER -->
    <div class="modal fade" id="ModalEditUser" tabindex="-1" aria-labelledby="ModalEditUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalEditUserLabel">Edit Informasi Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditUser" method="POST">
                        @csrf
                        @method('PUT')
                        <span style="font-size: 14px; font-weight: 600; display: block; margin-bottom: 10px; color: var(--black-color);">Informasi Akun</span>
                        <div class="mb-3">
                            <label for="editUsername" class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" id="editUsername" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPassword" class="form-label">Password (Opsional)</label>
                            <div class="input-group">
                                <input type="password" name="password" class="form-control" id="editPassword" placeholder="Isi jika ingin mengubah password">

                                <!-- Tombol Mata (Toggle Show/Hide) -->
                                <button class="btn btn-toggle-password" type="button" tabindex="-1">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <!-- Tombol Copy Password -->
                                <button class="btn btn-copy-password-input" type="button" onclick="copyInputText('editPassword')" title="Copy Password">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editRole" class="form-label">Role</label>
                            <select name="role_id" class="form-select" id="editRole" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status Akun</label>
                            <div class="d-flex align-items-center" style="gap: 10px;">
                                <span class="status-label" id="editStatusLabel" style="font-weight: 600; min-width: 60px;">Aktif</span>
                                <label class="toggle-switch">
                                    <input type="hidden" name="status" id="editStatusInput" value="aktif">
                                    <input type="checkbox" id="editStatusToggle" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <span style="font-size: 14px; font-weight: 600; display: block; margin-bottom: 10px; margin-top: 20px; color: var(--black-color);">Informasi Pribadi</span>
                        <div class="mb-3">
                            <label for="editNama" class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" id="editNama" required>
                        </div>
                        <button type="submit" class="btn-submit-modal">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        // FUNGSI COPY USERNAME (DARI TABEL)
        function copyToClipboard(text) {
            const textToCopy = `Username: ${text}`;
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(textToCopy).then(() => {
                    alert('Berhasil disalin: ' + textToCopy);
                }).catch(err => {
                    console.error('Gagal menyalin: ', err);
                    fallbackCopyTextToClipboard(textToCopy);
                });
            } else {
                fallbackCopyTextToClipboard(textToCopy);
            }
        }

        // FUNGSI COPY PASSWORD (DARI INPUT FORM)
        function copyInputText(elementId) {
            const inputElement = document.getElementById(elementId);
            if (!inputElement) return;

            const text = inputElement.value;
            if (!text) {
                alert('Password kosong, tidak ada yang disalin.');
                return;
            }

            const textToCopy = `Password: ${text}`;

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(textToCopy).then(() => {
                    alert('Password berhasil disalin!');
                }).catch(err => {
                    fallbackCopyTextToClipboard(textToCopy);
                });
            } else {
                fallbackCopyTextToClipboard(textToCopy);
            }
        }

        function fallbackCopyTextToClipboard(text) {
            var textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.position = "fixed";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                var successful = document.execCommand('copy');
                alert('Berhasil disalin (fallback): ' + text);
            } catch (err) {
                console.error('Fallback: Oops, unable to copy', err);
            }
            document.body.removeChild(textArea);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide toast logic
            var toastElList = [].slice.call(document.querySelectorAll('.toast'));
            var toastList = toastElList.map(function (toastEl) {
                if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
                    return new bootstrap.Toast(toastEl);
                }
                return null;
            });
            setTimeout(function() {
                var toasts = document.querySelectorAll('.toast');
                toasts.forEach(function(toast) {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 500);
                });
            }, 5000);

            // 1. Initialize Custom Selects
            const selects = document.querySelectorAll('select');
            selects.forEach(select => {
                if (select.nextElementSibling && select.nextElementSibling.classList.contains('custom-select-wrapper')) return;
                select.style.display = 'none';
                const wrapper = document.createElement('div'); wrapper.classList.add('custom-select-wrapper');
                const customSelect = document.createElement('div'); customSelect.classList.add('custom-select');
                const trigger = document.createElement('div'); trigger.classList.add('custom-select__trigger');
                const selectedOption = select.options[select.selectedIndex];
                trigger.innerHTML = `<span>${selectedOption ? selectedOption.text : 'Pilih...'}</span>`;
                const optionsDiv = document.createElement('div'); optionsDiv.classList.add('custom-options');

                for (const option of select.options) {
                    if (option.disabled) continue;
                    const optionElement = document.createElement('div');
                    optionElement.classList.add('custom-option');
                    optionElement.textContent = option.text;
                    optionElement.setAttribute('data-value', option.value);
                    if (option.selected) optionElement.classList.add('selected');
                    optionElement.addEventListener('click', function() {
                        optionsDiv.querySelectorAll('.custom-option').forEach(el => el.classList.remove('selected'));
                        this.classList.add('selected');
                        trigger.querySelector('span').textContent = this.textContent;
                        select.value = this.getAttribute('data-value');
                        select.dispatchEvent(new Event('change'));
                        wrapper.classList.remove('open');
                    });
                    optionsDiv.appendChild(optionElement);
                }
                customSelect.appendChild(trigger);
                customSelect.appendChild(optionsDiv);
                wrapper.appendChild(customSelect);
                select.parentNode.insertBefore(wrapper, select.nextSibling);
                trigger.addEventListener('click', function(e) { e.stopPropagation(); document.querySelectorAll('.custom-select-wrapper').forEach(el => { if (el !== wrapper) el.classList.remove('open'); }); wrapper.classList.toggle('open'); });
            });
            window.addEventListener('click', function(e) { if (!e.target.closest('.custom-select-wrapper')) { document.querySelectorAll('.custom-select-wrapper').forEach(el => el.classList.remove('open')); } });

            // 2. LOGIC MODAL EDIT
            const modalEdit = document.getElementById('ModalEditUser');
            modalEdit.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const username = button.getAttribute('data-username');
                const roleId = button.getAttribute('data-role');
                const status = button.getAttribute('data-status');

                const form = document.getElementById('formEditUser');
                form.action = `/admin/users/${id}`;
                document.getElementById('editNama').value = name;
                document.getElementById('editUsername').value = username;
                document.getElementById('editPassword').value = '';

                const roleSelect = document.getElementById('editRole');
                roleSelect.value = roleId;
                const roleTrigger = roleSelect.nextElementSibling.querySelector('.custom-select__trigger span');
                if(roleTrigger && roleSelect.options[roleSelect.selectedIndex]) {
                    roleTrigger.textContent = roleSelect.options[roleSelect.selectedIndex].text;
                }

                // UPDATE STATUS & COLOR
                const statusToggle = document.getElementById('editStatusToggle');
                const statusInput = document.getElementById('editStatusInput');
                const statusLabel = document.getElementById('editStatusLabel');

                if (status === 'aktif') {
                    statusToggle.checked = true;
                    statusInput.value = 'aktif';
                    statusLabel.textContent = 'Aktif';
                    statusLabel.style.color = '#198754';
                    statusLabel.style.fontWeight = 'bold';
                } else {
                    statusToggle.checked = false;
                    statusInput.value = 'nonaktif';
                    statusLabel.textContent = 'Nonaktif';
                    statusLabel.style.color = '#6c757d';
                    statusLabel.style.fontWeight = 'normal';
                }
            });

            // 3. Status Toggle Logic (Event Listener)
            const editStatusToggle = document.getElementById('editStatusToggle');
            const editStatusLabel = document.getElementById('editStatusLabel');
            const editStatusInput = document.getElementById('editStatusInput');

            if(editStatusToggle) {
                editStatusToggle.addEventListener('change', function() {
                    if (this.checked) {
                        editStatusLabel.textContent = 'Aktif';
                        editStatusLabel.style.color = '#198754';
                        editStatusLabel.style.fontWeight = 'bold';
                        editStatusInput.value = 'aktif';
                    } else {
                        editStatusLabel.textContent = 'Nonaktif';
                        editStatusLabel.style.color = '#6c757d';
                        editStatusLabel.style.fontWeight = 'normal';
                        editStatusInput.value = 'nonaktif';
                    }
                });
            }

            // --- NOTE: SCRIPT PASSWORD TOGGLE DIHAPUS DARI SINI ---
            // Karena sudah ditangani secara global oleh master.blade.php
            // melalui class .btn-toggle-password
        });
    </script>
@endpush
