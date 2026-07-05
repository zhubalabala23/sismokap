@extends('layouts.admin')

@section('title', 'Data Kontraktor - SISMOKAP')
@section('page_title', 'Master Data Kontraktor')

@section('content')
<div class="card p-4 bg-white">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <!-- Search -->
        <form action="{{ route('admin.kontraktor.index') }}" method="GET" class="d-flex gap-2 align-items-center m-0">
            <div class="input-group" style="max-width: 250px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 fs-7" placeholder="Cari kontraktor..." value="{{ $search }}">
            </div>
            <button type="submit" class="btn btn-light border btn-sm px-3 fs-7 py-2">Cari</button>
            @if($search)
                <a href="{{ route('admin.kontraktor.index') }}" class="btn btn-outline-secondary btn-sm px-3 fs-7 py-2">Reset</a>
            @endif
        </form>

        <!-- Tambah Kontraktor Button -->
        <button type="button" class="btn btn-primary rounded-3 fs-7 py-2 px-3" data-bs-toggle="modal" data-bs-target="#addKontraktorModal">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kontraktor
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
                    <th scope="col">NAMA PERUSAHAAN</th>
                    <th scope="col">PROYEK</th>
                    <th scope="col">PENANGGUNG JAWAB</th>
                    <th scope="col">TELEPON</th>
                    <th scope="col">EMAIL</th>
                    <th scope="col">NOMOR KONTRAK</th>
                    <th scope="col">MASA BERLAKU</th>
                    <th scope="col">ALAMAT</th>
                    <th scope="col" class="text-center" style="width: 150px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kontraktors as $index => $kontraktor)
                    <tr class="text-nowrap fs-7">
                        <td>{{ $kontraktors->firstItem() + $index }}</td>
                        <td class="fw-semibold text-dark">{{ $kontraktor->nama_kontraktor }}</td>
                        <td class="text-primary fw-semibold">{{ $kontraktor->proyekAssociated?->nama_proyek ?? '-' }}</td>
                        <td class="text-dark">{{ $kontraktor->nama_penanggung_jawab ?? '-' }}</td>
                        <td class="text-dark">{{ $kontraktor->no_telp ?? '-' }}</td>
                        <td class="text-dark">{{ $kontraktor->email ?? '-' }}</td>
                        <td class="text-dark">{{ $kontraktor->no_kontrak ?? '-' }}</td>
                        <td class="text-dark">
                            {{ $kontraktor->masa_berlaku_kontrak ? $kontraktor->masa_berlaku_kontrak->format('d M Y') : '-' }}
                        </td>
                        <td class="text-muted text-wrap" style="max-width: 250px;">{{ $kontraktor->alamat }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-sm btn-light border text-primary edit-button" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editKontraktorModal" 
                                    data-id="{{ $kontraktor->id }}" 
                                    data-nama="{{ $kontraktor->nama_kontraktor }}" 
                                    data-alamat="{{ $kontraktor->alamat }}"
                                    data-proyek="{{ $kontraktor->proyek_id }}"
                                    data-pj="{{ $kontraktor->nama_penanggung_jawab }}"
                                    data-telp="{{ $kontraktor->no_telp }}"
                                    data-email="{{ $kontraktor->email }}"
                                    data-nokontrak="{{ $kontraktor->no_kontrak }}"
                                    data-berlaku="{{ $kontraktor->masa_berlaku_kontrak ? $kontraktor->masa_berlaku_kontrak->format('Y-m-d') : '' }}"
                                    title="Edit">
                                    <i class="bi bi-pencil-fill"></i> Edit
                                </button>
                                <form action="{{ route('admin.kontraktor.destroy', $kontraktor->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kontraktor ini?')" class="d-inline">
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
                        <td colspan="10" class="text-center text-muted py-4 fs-7">Tidak ada data kontraktor ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <p class="text-muted fs-7 mb-0">Menampilkan {{ $kontraktors->firstItem() ?? 0 }} sampai {{ $kontraktors->lastItem() ?? 0 }} dari {{ $kontraktors->total() }} kontraktor</p>
        <div>
            {{ $kontraktors->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Tambah Kontraktor -->
<div class="modal fade" id="addKontraktorModal" tabindex="-1" aria-labelledby="addKontraktorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="addKontraktorModalLabel">Tambah Kontraktor Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.kontraktor.store') }}" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label for="proyek_id" class="form-label fs-7 fw-semibold text-dark">Pilih Proyek (Opsional)</label>
                        <select name="proyek_id" id="proyek_id" class="form-select fs-7">
                            <option value="">-- Pilih Proyek --</option>
                            @foreach($proyeks as $proyek)
                                <option value="{{ $proyek->id }}">{{ $proyek->nama_proyek }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nama_kontraktor" class="form-label fs-7 fw-semibold text-dark">Nama Perusahaan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kontraktor" id="nama_kontraktor" class="form-control fs-7" placeholder="Contoh: PT Madiun Bangun Nusantara" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label fs-7 fw-semibold text-dark">Alamat Perusahaan <span class="text-danger">*</span></label>
                        <textarea name="alamat" id="alamat" rows="2" class="form-control fs-7" placeholder="Masukkan alamat lengkap kantor..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="nama_penanggung_jawab" class="form-label fs-7 fw-semibold text-dark">Nama Penanggung Jawab</label>
                        <input type="text" name="nama_penanggung_jawab" id="nama_penanggung_jawab" class="form-control fs-7" placeholder="Contoh: Ir. H. Ahmad Fauzi">
                    </div>
                    <div class="mb-3">
                        <label for="no_telp" class="form-label fs-7 fw-semibold text-dark">Nomor Telepon</label>
                        <input type="text" name="no_telp" id="no_telp" class="form-control fs-7" placeholder="Contoh: 08123456789">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fs-7 fw-semibold text-dark">Email</label>
                        <input type="email" name="email" id="email" class="form-control fs-7" placeholder="Contoh: admin@perusahaan.com">
                    </div>
                    <div class="mb-3">
                        <label for="no_kontrak" class="form-label fs-7 fw-semibold text-dark">Nomor Kontrak</label>
                        <input type="text" name="no_kontrak" id="no_kontrak" class="form-control fs-7" placeholder="Contoh: SPK/02/VIII/2025">
                    </div>
                    <div class="mb-0">
                        <label for="masa_berlaku_kontrak" class="form-label fs-7 fw-semibold text-dark">Masa Berlaku Kontrak</label>
                        <input type="date" name="masa_berlaku_kontrak" id="masa_berlaku_kontrak" class="form-control fs-7">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light border fs-7" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fs-7">Simpan Kontraktor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Kontraktor -->
<div class="modal fade" id="editKontraktorModal" tabindex="-1" aria-labelledby="editKontraktorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="editKontraktorModalLabel">Edit Kontraktor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="editKontraktorForm">
                @csrf
                @method('PUT')
                <div class="modal-body py-4">
                    <div class="mb-3">
                        <label for="edit_proyek_id" class="form-label fs-7 fw-semibold text-dark">Pilih Proyek (Opsional)</label>
                        <select name="proyek_id" id="edit_proyek_id" class="form-select fs-7">
                            <option value="">-- Pilih Proyek --</option>
                            @foreach($proyeks as $proyek)
                                <option value="{{ $proyek->id }}">{{ $proyek->nama_proyek }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nama_kontraktor" class="form-label fs-7 fw-semibold text-dark">Nama Perusahaan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kontraktor" id="edit_nama_kontraktor" class="form-control fs-7" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_alamat" class="form-label fs-7 fw-semibold text-dark">Alamat Perusahaan <span class="text-danger">*</span></label>
                        <textarea name="alamat" id="edit_alamat" rows="2" class="form-control fs-7" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nama_penanggung_jawab" class="form-label fs-7 fw-semibold text-dark">Nama Penanggung Jawab</label>
                        <input type="text" name="nama_penanggung_jawab" id="edit_nama_penanggung_jawab" class="form-control fs-7">
                    </div>
                    <div class="mb-3">
                        <label for="edit_no_telp" class="form-label fs-7 fw-semibold text-dark">Nomor Telepon</label>
                        <input type="text" name="no_telp" id="edit_no_telp" class="form-control fs-7">
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label fs-7 fw-semibold text-dark">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control fs-7">
                    </div>
                    <div class="mb-3">
                        <label for="edit_no_kontrak" class="form-label fs-7 fw-semibold text-dark">Nomor Kontrak</label>
                        <input type="text" name="no_kontrak" id="edit_no_kontrak" class="form-control fs-7">
                    </div>
                    <div class="mb-0">
                        <label for="edit_masa_berlaku_kontrak" class="form-label fs-7 fw-semibold text-dark">Masa Berlaku Kontrak</label>
                        <input type="date" name="masa_berlaku_kontrak" id="edit_masa_berlaku_kontrak" class="form-control fs-7">
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
        const editModal = document.getElementById('editKontraktorModal');
        if (editModal) {
            editModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const nama = button.getAttribute('data-nama');
                const alamat = button.getAttribute('data-alamat');
                const proyekId = button.getAttribute('data-proyek');
                const pj = button.getAttribute('data-pj');
                const telp = button.getAttribute('data-telp');
                const email = button.getAttribute('data-email');
                const nokontrak = button.getAttribute('data-nokontrak');
                const berlaku = button.getAttribute('data-berlaku');

                const form = document.getElementById('editKontraktorForm');
                form.action = `/admin/kontraktor/${id}`;
                
                document.getElementById('edit_nama_kontraktor').value = nama || '';
                document.getElementById('edit_alamat').value = alamat || '';
                document.getElementById('edit_proyek_id').value = proyekId || '';
                document.getElementById('edit_nama_penanggung_jawab').value = pj || '';
                document.getElementById('edit_no_telp').value = telp || '';
                document.getElementById('edit_email').value = email || '';
                document.getElementById('edit_no_kontrak').value = nokontrak || '';
                document.getElementById('edit_masa_berlaku_kontrak').value = berlaku || '';
            });
        }
    });
</script>
@endsection
