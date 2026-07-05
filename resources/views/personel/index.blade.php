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
                <tr class="text-muted fs-7 text-nowrap">
                    <th scope="col" style="width: 80px;">NO</th>
                    <th scope="col">NRP / NIP</th>
                    <th scope="col">NAMA PERSONEL</th>
                    <th scope="col">PANGKAT / GOLONGAN</th>
                    <th scope="col">JABATAN</th>
                    <th scope="col">NOMOR HP</th>
                    <th scope="col">EMAIL</th>
                    <th scope="col">UNIT KERJA</th>
                    <th scope="col">HAK AKSES</th>
                    <th scope="col">PASSWORD</th>
                    <th scope="col" class="text-center" style="width: 150px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($personels as $index => $personel)
                    <tr class="text-nowrap fs-7">
                        <td>{{ $personels->firstItem() + $index }}</td>
                        <td class="text-dark">{{ $personel->nrp_nip ?? '-' }}</td>
                        <td class="fw-semibold text-dark">{{ $personel->nama }}</td>
                        <td class="text-dark">{{ $personel->pangkat_golongan ?? '-' }}</td>
                        <td class="text-dark">{{ $personel->jabatan }}</td>
                        <td class="text-dark">{{ $personel->no_hp ?? '-' }}</td>
                        <td class="text-dark">{{ $personel->email ?? '-' }}</td>
                        <td class="text-dark">{{ $personel->unit_kerja ?? '-' }}</td>
                        <td>
                            @if($personel->hak_akses)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-8 text-uppercase">{{ $personel->hak_akses }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-dark">
                            @if($personel->password)
                                <div class="d-flex align-items-center gap-2">
                                    <span class="password-text" data-password="{{ $personel->password }}">{{ str_repeat('*', strlen($personel->password)) }}</span>
                                    <button type="button" class="btn btn-sm btn-link p-0 text-muted toggle-password-visibility" style="text-decoration: none;">
                                        <i class="bi bi-eye-slash-fill"></i>
                                    </button>
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-sm btn-light border text-primary edit-button" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editPersonelModal" 
                                    data-id="{{ $personel->id }}" 
                                    data-nip="{{ $personel->nrp_nip }}"
                                    data-nama="{{ $personel->nama }}" 
                                    data-pangkat="{{ $personel->pangkat_golongan }}"
                                    data-jabatan="{{ $personel->jabatan }}"
                                    data-hp="{{ $personel->no_hp }}"
                                    data-email="{{ $personel->email }}"
                                    data-unit="{{ $personel->unit_kerja }}"
                                    data-akses="{{ $personel->hak_akses }}"
                                    data-password="{{ $personel->password }}"
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
                        <td colspan="10" class="text-center text-muted py-4 fs-7">Tidak ada data personel ditemukan.</td>
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
                        <label for="nrp_nip" class="form-label fs-7 fw-semibold text-dark">NRP / NIP</label>
                        <input type="text" name="nrp_nip" id="nrp_nip" class="form-control fs-7" placeholder="Masukkan NRP atau NIP">
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label fs-7 fw-semibold text-dark">Nama Personel <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control fs-7" placeholder="Contoh: Mayor Inf. Adi Wijaya" required>
                    </div>
                    <div class="mb-3">
                        <label for="pangkat_golongan" class="form-label fs-7 fw-semibold text-dark">Pangkat / Golongan</label>
                        <input type="text" name="pangkat_golongan" id="pangkat_golongan" class="form-control fs-7" placeholder="Contoh: Mayor / IV-a">
                    </div>
                    <div class="mb-3">
                        <label for="jabatan" class="form-label fs-7 fw-semibold text-dark">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" name="jabatan" id="jabatan" class="form-control fs-7" placeholder="Contoh: Pengawas Lapangan" required>
                    </div>
                    <div class="mb-3">
                        <label for="no_hp" class="form-label fs-7 fw-semibold text-dark">Nomor HP</label>
                        <input type="text" name="no_hp" id="no_hp" class="form-control fs-7" placeholder="Contoh: 08123456789">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fs-7 fw-semibold text-dark">Email</label>
                        <input type="email" name="email" id="email" class="form-control fs-7" placeholder="Contoh: personel@sismokap.com">
                    </div>
                    <div class="mb-3">
                        <label for="unit_kerja" class="form-label fs-7 fw-semibold text-dark">Unit Kerja</label>
                        <input type="text" name="unit_kerja" id="unit_kerja" class="form-control fs-7" placeholder="Contoh: Kodim 0803/Madiun">
                    </div>
                    <div class="mb-3">
                        <label for="hak_akses" class="form-label fs-7 fw-semibold text-dark">Hak Akses</label>
                        <select name="hak_akses" id="hak_akses" class="form-select fs-7">
                            <option value="">-- Pilih Hak Akses --</option>
                            <option value="admin">Admin</option>
                            <option value="operator">Operator</option>
                            <option value="pengawas">Pengawas</option>
                            <option value="pimpinan">Pimpinan</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label for="password" class="form-label fs-7 fw-semibold text-dark">Password <span class="text-muted">(diperlukan untuk login)</span></label>
                        <input type="password" name="password" id="password" class="form-control fs-7" placeholder="Masukkan password untuk akun login">
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
                        <label for="edit_nrp_nip" class="form-label fs-7 fw-semibold text-dark">NRP / NIP</label>
                        <input type="text" name="nrp_nip" id="edit_nrp_nip" class="form-control fs-7">
                    </div>
                    <div class="mb-3">
                        <label for="edit_nama" class="form-label fs-7 fw-semibold text-dark">Nama Personel <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="edit_nama" class="form-control fs-7" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_pangkat_golongan" class="form-label fs-7 fw-semibold text-dark">Pangkat / Golongan</label>
                        <input type="text" name="pangkat_golongan" id="edit_pangkat_golongan" class="form-control fs-7">
                    </div>
                    <div class="mb-3">
                        <label for="edit_jabatan" class="form-label fs-7 fw-semibold text-dark">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" name="jabatan" id="edit_jabatan" class="form-control fs-7" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_no_hp" class="form-label fs-7 fw-semibold text-dark">Nomor HP</label>
                        <input type="text" name="no_hp" id="edit_no_hp" class="form-control fs-7">
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label fs-7 fw-semibold text-dark">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control fs-7">
                    </div>
                    <div class="mb-3">
                        <label for="edit_unit_kerja" class="form-label fs-7 fw-semibold text-dark">Unit Kerja</label>
                        <input type="text" name="unit_kerja" id="edit_unit_kerja" class="form-control fs-7">
                    </div>
                    <div class="mb-3">
                        <label for="edit_hak_akses" class="form-label fs-7 fw-semibold text-dark">Hak Akses</label>
                        <select name="hak_akses" id="edit_hak_akses" class="form-select fs-7">
                            <option value="">-- Pilih Hak Akses --</option>
                            <option value="admin">Admin</option>
                            <option value="operator">Operator</option>
                            <option value="pengawas">Pengawas</option>
                            <option value="pimpinan">Pimpinan</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label for="edit_password" class="form-label fs-7 fw-semibold text-dark">Password <span class="text-muted">(Kosongkan jika tidak ingin diubah)</span></label>
                        <input type="password" name="password" id="edit_password" class="form-control fs-7" placeholder="Masukkan password baru untuk login">
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
                const nip = button.getAttribute('data-nip');
                const nama = button.getAttribute('data-nama');
                const pangkat = button.getAttribute('data-pangkat');
                const jabatan = button.getAttribute('data-jabatan');
                const hp = button.getAttribute('data-hp');
                const email = button.getAttribute('data-email');
                const unit = button.getAttribute('data-unit');
                const akses = button.getAttribute('data-akses');
                const password = button.getAttribute('data-password');

                const form = document.getElementById('editPersonelForm');
                form.action = `/admin/personel/${id}`;
                
                document.getElementById('edit_nrp_nip').value = nip || '';
                document.getElementById('edit_nama').value = nama || '';
                document.getElementById('edit_pangkat_golongan').value = pangkat || '';
                document.getElementById('edit_jabatan').value = jabatan || '';
                document.getElementById('edit_no_hp').value = hp || '';
                document.getElementById('edit_email').value = email || '';
                document.getElementById('edit_unit_kerja').value = unit || '';
                document.getElementById('edit_hak_akses').value = akses || '';
                document.getElementById('edit_password').value = password || '';
            });
        }

        // Toggle password visibility in the table list
        document.querySelectorAll('.toggle-password-visibility').forEach(button => {
            button.addEventListener('click', function() {
                const container = this.closest('div');
                const textSpan = container.querySelector('.password-text');
                const icon = this.querySelector('i');
                const plainPassword = textSpan.getAttribute('data-password');
                const maskedPassword = '*'.repeat(plainPassword.length);

                if (textSpan.textContent === maskedPassword) {
                    textSpan.textContent = plainPassword;
                    icon.className = 'bi bi-eye-fill';
                } else {
                    textSpan.textContent = maskedPassword;
                    icon.className = 'bi bi-eye-slash-fill';
                }
            });
        });
    });
</script>
@endsection
