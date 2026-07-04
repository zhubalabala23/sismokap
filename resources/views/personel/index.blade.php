@extends('layouts.admin')

@section('title', 'Data Personel - SISMOKAP')
@section('page_title', 'Master Data Personel')

@section('content')
<div class="card p-4 bg-white">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <!-- Search -->
        <form action="{{ route('admin.personel.index') }}" method="GET" class="d-flex gap-2 align-items-center m-0">
            <div class="input-group" style="max-width: 250px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 fs-7" placeholder="Cari personel..." value="{{ $search }}">
            </div>
            <button type="submit" class="btn btn-light border btn-sm px-3 fs-7 py-2">Cari</button>
            @if($search)
                <a href="{{ route('admin.personel.index') }}" class="btn btn-outline-secondary btn-sm px-3 fs-7 py-2">Reset</a>
            @endif
        </form>

        <!-- Tambah Personel Button -->
        <button type="button" class="btn btn-primary rounded-3 fs-7 py-2 px-3" data-bs-toggle="modal" data-bs-target="#addPersonelModal">
            <i class="bi bi-plus-lg me-1"></i> Tambah Personel
        </button>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
            <ul class="mb-0 fs-7">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr class="text-muted fs-7">
                    <th scope="col" style="width: 80px;">NO</th>
                    <th scope="col">NAMA PERSONEL</th>
                    <th scope="col">JABATAN</th>
                    <th scope="col">KONTAK</th>
                    <th scope="col" class="text-center" style="width: 150px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($personels as $index => $personel)
                    <tr>
                        <td class="fs-7">{{ $personels->firstItem() + $index }}</td>
                        <td class="fs-7 fw-semibold text-dark">{{ $personel->nama }}</td>
                        <td class="fs-7 text-dark">{{ $personel->jabatan }}</td>
                        <td class="fs-7 text-muted">{{ $personel->kontak }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-sm btn-light border text-primary edit-button" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editPersonelModal" 
                                    data-id="{{ $personel->id }}" 
                                    data-nama="{{ $personel->nama }}" 
                                    data-jabatan="{{ $personel->jabatan }}"
                                    data-kontak="{{ $personel->kontak }}"
                                    title="Edit">
                                    <i class="bi bi-pencil-fill"></i> Edit
                                </button>
                                <form action="{{ route('admin.personel.destroy', $personel->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus personel ini?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus">
                                        <i class="bi bi-trash-fill"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4 fs-7">Tidak ada data personel ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <p class="text-muted fs-7 mb-0">Menampilkan {{ $personels->firstItem() ?? 0 }} sampai {{ $personels->lastItem() ?? 0 }} dari {{ $personels->total() }} personel</p>
        <div>
            {{ $personels->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Tambah Personel -->
<div class="modal fade" id="addPersonelModal" tabindex="-1" aria-labelledby="addPersonelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="addPersonelModalLabel">Tambah Personel Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.personel.store') }}" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label for="nama" class="form-label fs-7 fw-semibold text-dark">Nama Personel <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control fs-7" placeholder="Contoh: Adi Wijaya" required>
                    </div>
                    <div class="mb-3">
                        <label for="jabatan" class="form-label fs-7 fw-semibold text-dark">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" name="jabatan" id="jabatan" class="form-control fs-7" placeholder="Contoh: Project Manager" required>
                    </div>
                    <div class="mb-0">
                        <label for="kontak" class="form-label fs-7 fw-semibold text-dark">Kontak/Telepon <span class="text-danger">*</span></label>
                        <input type="text" name="kontak" id="kontak" class="form-control fs-7" placeholder="Contoh: 08123456789" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light border fs-7" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fs-7">Simpan Personel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Personel -->
<div class="modal fade" id="editPersonelModal" tabindex="-1" aria-labelledby="editPersonelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="editPersonelModalLabel">Edit Personel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="editPersonelForm">
                @csrf
                @method('PUT')
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label for="edit_nama" class="form-label fs-7 fw-semibold text-dark">Nama Personel <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="edit_nama" class="form-control fs-7" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_jabatan" class="form-label fs-7 fw-semibold text-dark">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" name="jabatan" id="edit_jabatan" class="form-control fs-7" required>
                    </div>
                    <div class="mb-0">
                        <label for="edit_kontak" class="form-label fs-7 fw-semibold text-dark">Kontak/Telepon <span class="text-danger">*</span></label>
                        <input type="text" name="kontak" id="edit_kontak" class="form-control fs-7" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light border fs-7" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fs-7">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editModal = document.getElementById('editPersonelModal');
        if (editModal) {
            editModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const nama = button.getAttribute('data-nama');
                const jabatan = button.getAttribute('data-jabatan');
                const kontak = button.getAttribute('data-kontak');

                const form = document.getElementById('editPersonelForm');
                form.action = `/admin/personel/${id}`;
                
                document.getElementById('edit_nama').value = nama;
                document.getElementById('edit_jabatan').value = jabatan;
                document.getElementById('edit_kontak').value = kontak;
            });
        }
    });
</script>
@endsection
