@extends('layouts.admin')

@section('title', 'Dokumentasi Proyek - SISMOKAP')
@section('page_title', 'Dokumentasi & Galeri Proyek')

@section('content')
<div class="row g-4">
    <!-- Form Upload (Hanya untuk Admin dan Operator) -->
    @if(auth()->user()->role !== 'pimpinan')
        <div class="col-12 col-lg-4">
            <div class="card p-4 bg-white">
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">Unggah Dokumentasi Baru</h5>
                
                <form action="{{ route('dokumentasi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="proyek_id" class="form-label fs-7 fw-semibold text-dark">Pilih Proyek <span class="text-danger">*</span></label>
                        <select name="proyek_id" id="proyek_id" class="form-select fs-7" required>
                            <option value="">Pilih Proyek</option>
                            @foreach($proyeks as $proyek)
                                <option value="{{ $proyek->id }}" {{ old('proyek_id') == $proyek->id ? 'selected' : '' }}>
                                    {{ $proyek->nama_proyek }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="file" class="form-label fs-7 fw-semibold text-dark">File Foto <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="file" class="form-control fs-7" accept="image/png, image/jpeg, image/jpg" required>
                        <div class="form-text fs-9 text-muted">Format: JPG, JPEG, PNG. Ukuran maksimal: 2MB.</div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label fs-7 fw-semibold text-dark">Keterangan Foto</label>
                        <textarea name="keterangan" id="keterangan" rows="3" class="form-control fs-7" placeholder="Tulis deskripsi atau progress fisik yang terdokumentasikan pada foto ini...">{{ old('keterangan') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fs-7 py-2 mt-2">
                        <i class="bi bi-cloud-arrow-up me-1"></i> Unggah Dokumentasi
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- Gallery Section -->
    <div class="col-12 {{ auth()->user()->role === 'pimpinan' ? 'col-lg-12' : 'col-lg-8' }}">
        <div class="card p-4 bg-white">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4 border-bottom pb-2">
                <h5 class="fw-bold text-dark mb-0">Galeri Foto per Proyek</h5>
                
                <!-- Filter by Project -->
                <form action="{{ route('dokumentasi.index') }}" method="GET" class="d-flex gap-2 align-items-center m-0">
                    <select name="proyek_id" class="form-select form-select-sm fs-7" style="max-width: 200px;">
                        <option value="">Semua Proyek</option>
                        @foreach($proyeks as $proyek)
                            <option value="{{ $proyek->id }}" {{ $proyekId == $proyek->id ? 'selected' : '' }}>{{ $proyek->nama_proyek }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-light border btn-sm fs-7">Filter</button>
                    @if($proyekId)
                        <a href="{{ route('dokumentasi.index') }}" class="btn btn-outline-secondary btn-sm fs-7">Reset</a>
                    @endif
                </form>
            </div>

            <!-- Galleries Grouped by Project -->
            @forelse($galleryProyeks as $gProyek)
                <div class="project-gallery-group mb-4 pb-3 border-bottom last-border-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-folder2-open me-2"></i>{{ $gProyek->nama_proyek }}</h6>
                        <span class="badge bg-light text-dark border fs-8">{{ $gProyek->dokumentasi->count() }} Foto</span>
                    </div>

                    <div class="row g-3">
                        @forelse($gProyek->dokumentasi as $foto)
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="card border rounded-3 overflow-hidden h-100 hover-shadow position-relative" style="transition: all 0.2s;">
                                    <img src="{{ asset('storage/' . $foto->file_path) }}" class="card-img-top object-fit-cover" style="height: 160px;" alt="Dokumentasi">
                                    <div class="card-body p-3">
                                        <p class="card-text fs-8 text-dark mb-2">{{ $foto->keterangan ?? 'Tidak ada keterangan.' }}</p>
                                        <div class="d-flex justify-content-between align-items-center border-top pt-2">
                                            <span class="fs-9 text-muted"><i class="bi bi-calendar3 me-1"></i>{{ $foto->tanggal_upload->format('d M Y') }}</span>
                                            
                                            <!-- Delete Button (Only for Admin and Operator) -->
                                            @if(auth()->user()->role !== 'pimpinan')
                                                <form action="{{ route('dokumentasi.destroy', $foto->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto dokumentasi ini?')" class="m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger p-0 border-0 fs-8" title="Hapus Foto">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted fs-8 italic mb-0 ps-3">Belum ada foto dokumentasi untuk proyek ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @empty
                <p class="text-center text-muted py-4 fs-7">Tidak ada data proyek ditemukan.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
