@extends('admin.layouts.master')

@section('title','Master Data')

@section('content')
{{-- STYLE KHUSUS --}}
<style>
    /* --- Pagination Style --- */
    .pagination-wrapper { font-family: sans-serif; }
    .pagination-wrapper nav { display: flex; flex-direction: row; justify-content: space-between; align-items: center; width: 100%; padding: 10px 0; }
    .pagination-wrapper nav > div:first-child.flex { display: none; }
    .pagination-wrapper nav > div:last-child { display: flex; justify-content: space-between; align-items: center; width: 100%; flex-wrap: wrap; gap: 15px; }
    .pagination-wrapper p { margin: 0; color: #6b7280; font-size: 0.875rem; }
    .pagination-wrapper nav > div:last-child > div:last-child { display: flex; gap: 5px; }
    .pagination-wrapper nav span[aria-current="page"] span, .pagination-wrapper nav a, .pagination-wrapper nav span.relative { display: inline-flex; align-items: center; justify-content: center; padding: 6px 14px; border: 1px solid #e5e7eb; background-color: white; color: #374151; text-decoration: none; border-radius: 6px; font-weight: 500; font-size: 0.875rem; min-width: 36px; height: 36px; transition: all 0.2s ease; cursor: pointer; }
    .pagination-wrapper nav a:hover { background-color: #f3f4f6; border-color: #d1d5db; color: #111827; }
    .pagination-wrapper nav span[aria-current="page"] span, .pagination-wrapper nav span[aria-current="page"] { background-color: #2563eb !important; color: white !important; border-color: #2563eb !important; z-index: 1; }
    .pagination-wrapper nav span[aria-disabled="true"] span, .pagination-wrapper nav span[aria-disabled="true"] { opacity: 0.6; cursor: not-allowed; background-color: #f9fafb; color: #9ca3af; }
    .pagination-wrapper svg { width: 16px !important; height: 16px !important; fill: currentColor; }
    .pagination-wrapper .hidden { display: none !important; }
    @media (min-width: 640px) { .pagination-wrapper .sm\:flex-1 { flex: 1 1 0%; } .pagination-wrapper .sm\:hidden { display: none !important; } .pagination-wrapper .sm\:flex { display: flex !important; } .pagination-wrapper .sm\:items-center { align-items: center !important; } .pagination-wrapper .sm\:justify-between { justify-content: space-between !important; } }

    /* --- Tab Content Helper --- */
    .tab-content { display: none; }
    .tab-content.active-content { display: block; animation: fadeIn 0.3s; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .tab-btn.active { background-color: #2563eb; color: white; border-color: #2563eb; }

    /* --- TOAST NOTIFICATION STYLE (Floating Card) --- */
    .toast-container {
        position: fixed;
        top: 25px;
        right: 25px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 15px;
        pointer-events: none; /* Allows clicking through container */
    }

    .toast-card {
        pointer-events: auto; /* Re-enable clicks on card */
        background: white;
        min-width: 320px;
        max-width: 400px;
        border-radius: 12px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        padding: 16px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        position: relative;
        overflow: hidden;
        animation: slideInRight 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid #f3f4f6;
    }

    .toast-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .toast-content {
        flex: 1;
    }

    .toast-title {
        font-weight: 600;
        font-size: 0.95rem;
        color: #111827;
        margin-bottom: 2px;
    }

    .toast-message {
        font-size: 0.85rem;
        color: #6b7280;
        line-height: 1.4;
    }

    .toast-close {
        background: transparent;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        padding: 0;
        font-size: 1.2rem;
        line-height: 1;
        transition: color 0.2s;
    }
    .toast-close:hover { color: #374151; }

    /* Success Theme */
    .toast-success .toast-icon { color: #10b981; }
    .toast-success .toast-progress-bar { background-color: #10b981; }

    /* Error Theme */
    .toast-error .toast-icon { color: #ef4444; }
    .toast-error .toast-progress-bar { background-color: #ef4444; }

    /* Progress Bar Animation */
    .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: rgba(0,0,0,0.05);
    }

    .toast-progress-bar {
        height: 100%;
        width: 100%;
        animation: progress 3s linear forwards;
    }

    @keyframes slideInRight {
        from { transform: translateX(120%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes fadeOutRight {
        to { transform: translateX(120%); opacity: 0; }
    }

    @keyframes progress {
        from { width: 100%; }
        to { width: 0%; }
    }
</style>

<div class="content-page d-flex flex-column align-items-center justify-content-center align-self-stretch" style="padding: 0px 25px 25px 25px; gap: 10px;">
    <div class="header-content align-self-stretch">
        <h1 class="title-page">Master Data (Referensi)</h1>
    </div>

    <!-- TOAST NOTIFICATION CONTAINER (Floating) -->
    <div class="toast-container">
        @if(session('success'))
            <div class="toast-card toast-success">
                <div class="toast-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                <div class="toast-content">
                    <div class="toast-title">Berhasil!</div>
                    <div class="toast-message">{{ session('success') }}</div>
                </div>
                <button class="toast-close" onclick="closeToast(this)">&times;</button>
                <div class="toast-progress">
                    <div class="toast-progress-bar"></div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="toast-card toast-error">
                <div class="toast-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                </div>
                <div class="toast-content">
                    <div class="toast-title">Gagal!</div>
                    <div class="toast-message">
                        <ul style="margin: 0; padding-left: 15px; list-style-type: disc;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button class="toast-close" onclick="closeToast(this)">&times;</button>
                <div class="toast-progress">
                    <div class="toast-progress-bar"></div>
                </div>
            </div>
        @endif
    </div>

    <!-- TAB MENU -->
    <div class="master-tabs align-self-stretch">
        <button class="tab-btn" onclick="openTab(event, 'units')">Master Units</button>
        <button class="tab-btn" onclick="openTab(event, 'trucks')">Master Trucks</button>
        <button class="tab-btn" onclick="openTab(event, 'inventory')">Master Inventory</button>
        <button class="tab-btn" onclick="openTab(event, 'employees')">Master Employees</button>
    </div>

    <div class="data-content-wrapper d-flex flex-column align-items-start align-self-stretch" style="width: 100%;">

        <!-- CONTENT 1: MASTER UNITS -->
        <div id="units" class="tab-content">
            <div class="action-bar d-flex justify-content-between align-self-stretch mb-3">
                 <button class="btn-add" data-bs-toggle="modal" data-bs-target="#modalAddUnit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M4.5 6.99902C6.98405 7.00177 8.99712 9.01493 9 11.499C9 11.7752 8.77613 11.999 8.5 11.999H0.5C0.223859 11.999 0 11.7752 0 11.499C0.00290797 9.01493 2.01595 7.00179 4.5 6.99902ZM10 3.99902C10.2761 3.99907 10.5 4.22293 10.5 4.49902V5.49902H11.5C11.7761 5.49907 11.9999 5.72294 12 5.99902C12 6.27515 11.7761 6.49898 11.5 6.49902H10.5V7.49902C10.5 7.77515 10.2761 7.99898 10 7.99902C9.72386 7.99902 9.5 7.77517 9.5 7.49902V6.49902H8.5C8.22386 6.49902 8 6.27517 8 5.99902C8.00005 5.72292 8.22389 5.49902 8.5 5.49902H9.5V4.49902C9.50003 4.2229 9.72388 3.99902 10 3.99902ZM4.5 0C6.15685 0 7.5 1.3431 7.5 3C7.49978 4.65672 6.15672 6 4.5 6C2.84328 6 1.50022 4.65672 1.5 3C1.5 1.3431 2.84315 0 4.5 0Z" fill="white"/></svg>
                    Tambah Unit
                </button>
            </div>
            <div class="document-table d-flex flex-column align-items-start align-self-stretch">
                <div class="box-title d-flex flex-column align-items-start align-self-stretch" style="padding: 15px;">
                    <span class="title-table">Tabel Master Units</span>
                </div>
                <table class="table">
                    <tr class="head d-flex align-items-center align-self-stretch">
                        <th class="number">No</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th class="aksi">Aksi</th>
                    </tr>
                    @forelse($units as $unit)
                    <tr class="body d-flex align-items-center align-self-stretch">
                        <td class="number">{{ $units->firstItem() + $loop->index }}</td>
                        <td>{{ $unit->name }}</td>
                        <td>{{ $unit->type }}</td>
                        <td class="aksi">
                            <button class="btn-edit"
                                onclick="editUnit('{{ $unit->id }}', '{{ $unit->name }}', '{{ $unit->type }}')"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditUnit">Edit</button>
                            <button class="btn-delete"
                                onclick="confirmDelete('{{ route('admin.units.destroy', $unit->id) }}')"
                                data-bs-toggle="modal"
                                data-bs-target="#modalDelete">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr class="body d-flex align-items-center align-self-stretch">
                        <td colspan="4" class="text-center p-3">Belum ada data unit.</td>
                    </tr>
                    @endforelse
                </table>
                <div class="p-3 pagination-wrapper">
                    {{ $units->appends(['trucks_page' => request('trucks_page'), 'inv_page' => request('inv_page'), 'emps_page' => request('emps_page')])->links() }}
                </div>
            </div>
        </div>

        <!-- CONTENT 2: MASTER TRUCKS -->
        <div id="trucks" class="tab-content">
            <div class="action-bar d-flex justify-content-between align-self-stretch mb-3">
                 <button class="btn-add" data-bs-toggle="modal" data-bs-target="#modalAddTruck">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M4.5 6.99902C6.98405 7.00177 8.99712 9.01493 9 11.499C9 11.7752 8.77613 11.999 8.5 11.999H0.5C0.223859 11.999 0 11.7752 0 11.499C0.00290797 9.01493 2.01595 7.00179 4.5 6.99902ZM10 3.99902C10.2761 3.99907 10.5 4.22293 10.5 4.49902V5.49902H11.5C11.7761 5.49907 11.9999 5.72294 12 5.99902C12 6.27515 11.7761 6.49898 11.5 6.49902H10.5V7.49902C10.5 7.77515 10.2761 7.99898 10 7.99902C9.72386 7.99902 9.5 7.77517 9.5 7.49902V6.49902H8.5C8.22386 6.49902 8 6.27517 8 5.99902C8.00005 5.72292 8.22389 5.49902 8.5 5.49902H9.5V4.49902C9.50003 4.2229 9.72388 3.99902 10 3.99902ZM4.5 0C6.15685 0 7.5 1.3431 7.5 3C7.49978 4.65672 6.15672 6 4.5 6C2.84328 6 1.50022 4.65672 1.5 3C1.5 1.3431 2.84315 0 4.5 0Z" fill="white"/></svg>
                    Tambah Truck
                </button>
            </div>
             <div class="document-table d-flex flex-column align-items-start align-self-stretch">
                <div class="box-title d-flex flex-column align-items-start align-self-stretch" style="padding: 15px;">
                    <span class="title-table">Tabel Master Trucks</span>
                </div>
                <table class="table">
                    <tr class="head d-flex align-items-center align-self-stretch">
                        <th class="number">No</th>
                        <th>Name</th>
                        <th class="medium">Plate Number</th>
                        <th>Description</th>
                        <th class="aksi">Aksi</th>
                    </tr>
                    @forelse($trucks as $truck)
                    <tr class="body d-flex align-items-center align-self-stretch">
                        <td class="number">{{ $trucks->firstItem() + $loop->index }}</td>
                        <td>{{ $truck->name }}</td>
                        <td class="medium">{{ $truck->plate_number }}</td>
                        <td>{{ $truck->description }}</td>
                        <td class="aksi">
                            <button class="btn-edit"
                                onclick="editTruck('{{ $truck->id }}', '{{ $truck->name }}', '{{ $truck->plate_number }}', '{{ $truck->description }}')"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditTruck">Edit</button>
                            <button class="btn-delete"
                                onclick="confirmDelete('{{ route('admin.trucks.destroy', $truck->id) }}')"
                                data-bs-toggle="modal"
                                data-bs-target="#modalDelete">Hapus</button>
                        </td>
                    </tr>
                    @empty
                     <tr class="body d-flex align-items-center align-self-stretch">
                        <td colspan="5" class="text-center p-3">Belum ada data truck.</td>
                    </tr>
                    @endforelse
                </table>
                <div class="p-3 pagination-wrapper">
                    {{ $trucks->appends(['units_page' => request('units_page'), 'inv_page' => request('inv_page'), 'emps_page' => request('emps_page')])->links() }}
                </div>
            </div>
        </div>

         <!-- CONTENT 3: MASTER INVENTORY -->
         <div id="inventory" class="tab-content">
            <div class="action-bar d-flex justify-content-between align-self-stretch mb-3">
                 <button class="btn-add" data-bs-toggle="modal" data-bs-target="#modalAddInventory">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M4.5 6.99902C6.98405 7.00177 8.99712 9.01493 9 11.499C9 11.7752 8.77613 11.999 8.5 11.999H0.5C0.223859 11.999 0 11.7752 0 11.499C0.00290797 9.01493 2.01595 7.00179 4.5 6.99902ZM10 3.99902C10.2761 3.99907 10.5 4.22293 10.5 4.49902V5.49902H11.5C11.7761 5.49907 11.9999 5.72294 12 5.99902C12 6.27515 11.7761 6.49898 11.5 6.49902H10.5V7.49902C10.5 7.77515 10.2761 7.99898 10 7.99902C9.72386 7.99902 9.5 7.77517 9.5 7.49902V6.49902H8.5C8.22386 6.49902 8 6.27517 8 5.99902C8.00005 5.72292 8.22389 5.49902 8.5 5.49902H9.5V4.49902C9.50003 4.2229 9.72388 3.99902 10 3.99902ZM4.5 0C6.15685 0 7.5 1.3431 7.5 3C7.49978 4.65672 6.15672 6 4.5 6C2.84328 6 1.50022 4.65672 1.5 3C1.5 1.3431 2.84315 0 4.5 0Z" fill="white"/></svg>
                    Tambah Item
                </button>
            </div>
             <div class="document-table d-flex flex-column align-items-start align-self-stretch">
                <div class="box-title d-flex flex-column align-items-start align-self-stretch" style="padding: 15px;">
                    <span class="title-table">Tabel Master Inventory Items</span>
                </div>
                <table class="table">
                    <tr class="head d-flex align-items-center align-self-stretch">
                        <th class="number">No</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th class="aksi">Aksi</th>
                    </tr>
                    @forelse($inventories as $item)
                    <tr class="body d-flex align-items-center align-self-stretch">
                        <td class="number">{{ $inventories->firstItem() + $loop->index }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category }}</td>
                        <td class="aksi">
                            <button class="btn-edit"
                                onclick="editInventory('{{ $item->id }}', '{{ $item->name }}', '{{ $item->category }}')"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditInventory">Edit</button>
                            <button class="btn-delete"
                                onclick="confirmDelete('{{ route('admin.inventories.destroy', $item->id) }}')"
                                data-bs-toggle="modal"
                                data-bs-target="#modalDelete">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr class="body d-flex align-items-center align-self-stretch">
                        <td colspan="4" class="text-center p-3">Belum ada data inventory.</td>
                    </tr>
                    @endforelse
                </table>
                <div class="p-3 pagination-wrapper">
                     {{ $inventories->appends(['units_page' => request('units_page'), 'trucks_page' => request('trucks_page'), 'emps_page' => request('emps_page')])->links() }}
                </div>
            </div>
        </div>

        <!-- CONTENT 4: MASTER EMPLOYEES -->
         <div id="employees" class="tab-content">
            <div class="action-bar d-flex justify-content-between align-self-stretch mb-3">
                 <button class="btn-add" data-bs-toggle="modal" data-bs-target="#modalAddEmployee">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M4.5 6.99902C6.98405 7.00177 8.99712 9.01493 9 11.499C9 11.7752 8.77613 11.999 8.5 11.999H0.5C0.223859 11.999 0 11.7752 0 11.499C0.00290797 9.01493 2.01595 7.00179 4.5 6.99902ZM10 3.99902C10.2761 3.99907 10.5 4.22293 10.5 4.49902V5.49902H11.5C11.7761 5.49907 11.9999 5.72294 12 5.99902C12 6.27515 11.7761 6.49898 11.5 6.49902H10.5V7.49902C10.5 7.77515 10.2761 7.99898 10 7.99902C9.72386 7.99902 9.5 7.77517 9.5 7.49902V6.49902H8.5C8.22386 6.49902 8 6.27517 8 5.99902C8.00005 5.72292 8.22389 5.49902 8.5 5.49902H9.5V4.49902C9.50003 4.2229 9.72388 3.99902 10 3.99902ZM4.5 0C6.15685 0 7.5 1.3431 7.5 3C7.49978 4.65672 6.15672 6 4.5 6C2.84328 6 1.50022 4.65672 1.5 3C1.5 1.3431 2.84315 0 4.5 0Z" fill="white"/></svg>
                    Tambah Karyawan
                </button>
            </div>
             <div class="document-table d-flex flex-column align-items-start align-self-stretch">
                <div class="box-title d-flex flex-column align-items-start align-self-stretch" style="padding: 15px;">
                    <span class="title-table">Tabel Master Employees</span>
                </div>
                <table class="table">
                    <tr class="head d-flex align-items-center align-self-stretch">
                        <th class="number">No</th>
                        <th class="medium">NPK</th>
                        <th>Name</th>
                        <th>Group</th>
                        <th>Position</th>
                        <th class="aksi">Aksi</th>
                    </tr>
                    @forelse($employees as $emp)
                    <tr class="body d-flex align-items-center align-self-stretch">
                        <td class="number">{{ $employees->firstItem() + $loop->index }}</td>
                        <td class="medium">{{ $emp->npk }}</td>
                        <td>{{ $emp->name }}</td>
                        <td>{{ $emp->group_name }}</td>
                        <td>{{ $emp->position }}</td>
                        <td class="aksi">
                            <button class="btn-edit"
                                onclick="editEmployee('{{ $emp->id }}', '{{ $emp->npk }}', '{{ $emp->name }}', '{{ $emp->group_name }}', '{{ $emp->position }}')"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditEmployee">Edit</button>
                            <button class="btn-delete"
                                onclick="confirmDelete('{{ route('admin.employees.destroy', $emp->id) }}')"
                                data-bs-toggle="modal"
                                data-bs-target="#modalDelete">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr class="body d-flex align-items-center align-self-stretch">
                        <td colspan="6" class="text-center p-3">Belum ada data karyawan.</td>
                    </tr>
                    @endforelse
                </table>
                 <div class="p-3 pagination-wrapper">
                     {{ $employees->appends(['units_page' => request('units_page'), 'trucks_page' => request('trucks_page'), 'inv_page' => request('inv_page')])->links() }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('modal')
    <!-- MODAL UNTUK TAMBAH MASTER DATA -->

    <!-- 1. Modal Add Unit -->
    <div class="modal fade" id="modalAddUnit" tabindex="-1" aria-labelledby="modalAddUnitLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddUnitLabel">Tambah Unit Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.units.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="unitName" class="form-label">Nama Unit (Kode)</label>
                            <input type="text" class="form-control" name="name" id="unitName" placeholder="Contoh: FL-01" required>
                        </div>
                        <div class="mb-3">
                            <label for="unitType" class="form-label">Tipe Unit</label>
                            <select class="form-select" name="type" id="unitType" required>
                                <option value="" disabled selected>Pilih Tipe...</option>
                                <option value="Forklift">Forklift</option>
                                <option value="Excavator">Excavator</option>
                                <option value="Dozer">Dozer</option>
                                <option value="Crane">Crane</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-submit-modal">Simpan Unit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Modal Add Truck -->
    <div class="modal fade" id="modalAddTruck" tabindex="-1" aria-labelledby="modalAddTruckLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddTruckLabel">Tambah Truck Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.trucks.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="truckName" class="form-label">Nama Truck</label>
                            <input type="text" class="form-control" name="name" id="truckName" placeholder="Contoh: Truck A" required>
                        </div>
                        <div class="mb-3">
                            <label for="plateNumber" class="form-label">Plat Nomor</label>
                            <input type="text" class="form-control" name="plate_number" id="plateNumber" placeholder="Contoh: KT 8888 AA">
                        </div>
                        <div class="mb-3">
                            <label for="truckDesc" class="form-label">Deskripsi</label>
                            <input type="text" class="form-control" name="description" id="truckDesc" placeholder="Contoh: Truck Hino 500">
                        </div>
                        <button type="submit" class="btn-submit-modal">Simpan Truck</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Modal Add Inventory -->
    <div class="modal fade" id="modalAddInventory" tabindex="-1" aria-labelledby="modalAddInventoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddInventoryLabel">Tambah Item Inventory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.inventories.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="itemName" class="form-label">Nama Item</label>
                            <input type="text" class="form-control" name="name" id="itemName" placeholder="Contoh: Terpal" required>
                        </div>
                        <div class="mb-3">
                            <label for="itemCategory" class="form-label">Kategori</label>
                            <select class="form-select" name="category" id="itemCategory" required>
                                <option value="" disabled selected>Pilih Kategori...</option>
                                <option value="Kebersihan">Kebersihan/Kerapian</option>
                                <option value="Keamanan">Keamanan</option>
                                <option value="Alat Tulis">Alat Tulis</option>
                                <option value="Elektronik">Elektronik</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-submit-modal">Simpan Item</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Modal Add Employee -->
    <div class="modal fade" id="modalAddEmployee" tabindex="-1" aria-labelledby="modalAddEmployeeLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddEmployeeLabel">Tambah Karyawan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.employees.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="empNPK" class="form-label">NPK</label>
                            <input type="text" class="form-control" name="npk" id="empNPK" placeholder="Contoh: 102938" required>
                        </div>
                        <div class="mb-3">
                            <label for="empName" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="name" id="empName" placeholder="Masukkan Nama Lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label for="empGroup" class="form-label">Group</label>
                            <input type="text" class="form-control" name="group_name" id="empGroup" placeholder="Contoh: Group A" required>
                        </div>
                        <div class="mb-3">
                            <label for="empPosition" class="form-label">Jabatan/Posisi</label>
                            <input type="text" class="form-control" name="position" id="empPosition" placeholder="Contoh: Driver" required>
                        </div>
                        <button type="submit" class="btn-submit-modal">Simpan Karyawan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL UNTUK EDIT MASTER DATA (EDIT MODALS) -->

    <!-- 1. Modal Edit Unit -->
    <div class="modal fade" id="modalEditUnit" tabindex="-1" aria-labelledby="modalEditUnitLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditUnitLabel">Edit Unit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditUnit" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="editUnitName" class="form-label">Nama Unit (Kode)</label>
                            <input type="text" class="form-control" name="name" id="editUnitName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUnitType" class="form-label">Tipe Unit</label>
                            <select class="form-select" name="type" id="editUnitType" required>
                                <option value="Forklift">Forklift</option>
                                <option value="Excavator">Excavator</option>
                                <option value="Dozer">Dozer</option>
                                <option value="Crane">Crane</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-submit-modal">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Modal Edit Truck -->
    <div class="modal fade" id="modalEditTruck" tabindex="-1" aria-labelledby="modalEditTruckLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditTruckLabel">Edit Truck</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditTruck" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="editTruckName" class="form-label">Nama Truck</label>
                            <input type="text" class="form-control" name="name" id="editTruckName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPlateNumber" class="form-label">Plat Nomor</label>
                            <input type="text" class="form-control" name="plate_number" id="editPlateNumber">
                        </div>
                        <div class="mb-3">
                            <label for="editTruckDesc" class="form-label">Deskripsi</label>
                            <input type="text" class="form-control" name="description" id="editTruckDesc">
                        </div>
                        <button type="submit" class="btn-submit-modal">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Modal Edit Inventory -->
    <div class="modal fade" id="modalEditInventory" tabindex="-1" aria-labelledby="modalEditInventoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditInventoryLabel">Edit Item Inventory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                     <form id="formEditInventory" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="editItemName" class="form-label">Nama Item</label>
                            <input type="text" class="form-control" name="name" id="editItemName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editItemCategory" class="form-label">Kategori</label>
                            <select class="form-select" name="category" id="editItemCategory" required>
                                <option value="Kebersihan">Kebersihan/Kerapian</option>
                                <option value="Keamanan">Keamanan</option>
                                <option value="Alat Tulis">Alat Tulis</option>
                                <option value="Elektronik">Elektronik</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-submit-modal">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Modal Edit Employee -->
    <div class="modal fade" id="modalEditEmployee" tabindex="-1" aria-labelledby="modalEditEmployeeLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditEmployeeLabel">Edit Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditEmployee" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="editEmpNPK" class="form-label">NPK</label>
                            <input type="text" class="form-control" name="npk" id="editEmpNPK" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmpName" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="name" id="editEmpName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmpGroup" class="form-label">Group</label>
                            <input type="text" class="form-control" name="group_name" id="editEmpGroup" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmpPosition" class="form-label">Jabatan/Posisi</label>
                            <input type="text" class="form-control" name="position" id="editEmpPosition" required>
                        </div>
                        <button type="submit" class="btn-submit-modal">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL HAPUS (DELETE CONFIRMATION) -->
    <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeleteLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
                    <form id="formDelete" method="POST" class="d-flex gap-2 justify-content-center">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn-submit-modal" style="background-color: var(--input-bg); color: var(--black-color); border: 1px solid var(--gray-border);" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-confirm-delete">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        // --- 1. Delete Logic ---
        function confirmDelete(url) {
            document.getElementById('formDelete').action = url;
        }

        // --- 2. Edit Logic Handlers ---
        function editUnit(id, name, type) {
            document.getElementById('editUnitName').value = name;
            document.getElementById('editUnitType').value = type;
            document.getElementById('formEditUnit').action = '/admin/units/' + id;
        }

        function editTruck(id, name, plate, desc) {
            document.getElementById('editTruckName').value = name;
            document.getElementById('editPlateNumber').value = plate;
            document.getElementById('editTruckDesc').value = desc;
            document.getElementById('formEditTruck').action = '/admin/trucks/' + id;
        }

        function editInventory(id, name, category) {
            document.getElementById('editItemName').value = name;
            document.getElementById('editItemCategory').value = category;
            document.getElementById('formEditInventory').action = '/admin/inventories/' + id;
        }

        function editEmployee(id, npk, name, group, position) {
            document.getElementById('editEmpNPK').value = npk;
            document.getElementById('editEmpName').value = name;
            document.getElementById('editEmpGroup').value = group;
            document.getElementById('editEmpPosition').value = position;
            document.getElementById('formEditEmployee').action = '/admin/employees/' + id;
        }

        // --- 3. Tab Switching Logic with Persistence (LocalStorage) ---
        document.addEventListener("DOMContentLoaded", function() {
            // Cek apakah ada tab yang tersimpan di LocalStorage
            let savedTab = localStorage.getItem('activeMasterTab');

            if (savedTab) {
                // Jika ada, buka tab tersebut
                let tabButton = document.querySelector(`button[onclick*="'${savedTab}'"]`);
                if (tabButton) {
                    tabButton.click();
                } else {
                    document.querySelector(`button[onclick*="'units'"]`).click();
                }
            } else {
                document.querySelector(`button[onclick*="'units'"]`).click();
            }

            // --- 4. Toast Notification Auto-Dismiss Logic ---
            const toasts = document.querySelectorAll('.toast-card');
            toasts.forEach(toast => {
                // Set timer to dismiss after 3 seconds (matching animation duration)
                setTimeout(() => {
                    // Start fade out animation
                    toast.style.animation = 'fadeOutRight 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards';

                    // Remove from DOM after animation completes
                    setTimeout(() => {
                        toast.remove();
                    }, 500);
                }, 3000); // 3000ms = 3 seconds
            });
        });

        // Function to manually close toast
        function closeToast(button) {
            const toast = button.closest('.toast-card');
            toast.style.animation = 'fadeOutRight 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards';
            setTimeout(() => {
                toast.remove();
            }, 400);
        }

        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].classList.remove("active-content");
                tabcontent[i].style.display = "none";
            }

            tablinks = document.getElementsByClassName("tab-btn");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("active");
            }

            document.getElementById(tabName).style.display = "block";
            setTimeout(() => {
                document.getElementById(tabName).classList.add("active-content");
            }, 10);

            if (evt) {
                evt.currentTarget.classList.add("active");
            } else {
                let btn = document.querySelector(`button[onclick*="'${tabName}'"]`);
                if(btn) btn.classList.add("active");
            }

            localStorage.setItem('activeMasterTab', tabName);
        }
    </script>
@endpush
