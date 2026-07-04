@extends('layouts.admin')

@section('title', 'Data Proyek - SISMOKAP')
@section('page_title', 'Data Proyek Konstruksi')

@section('content')
<!-- Row 1: Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card p-3 border-0 bg-white">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary-subtle p-3 text-primary me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-wrench-adjustable fs-4"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ \App\Models\Proyek::count() }}</h5>
                    <p class="text-muted mb-0 fs-8 fw-semibold text-uppercase">Total Proyek</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card p-3 border-0 bg-white">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-success-subtle p-3 text-success me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-check-circle-fill fs-4"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ \App\Models\Proyek::where('status', 'selesai')->count() }}</h5>
                    <p class="text-muted mb-0 fs-8 fw-semibold text-uppercase">Selesai</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card p-3 border-0 bg-white">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary-subtle p-3 text-primary me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-play-circle-fill fs-4"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ \App\Models\Proyek::where('status', 'berjalan')->count() }}</h5>
                    <p class="text-muted mb-0 fs-8 fw-semibold text-uppercase">Berjalan</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card p-3 border-0 bg-white">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-danger-subtle p-3 text-danger me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-exclamation-octagon-fill fs-4"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ \App\Models\Proyek::where('status', 'terlambat')->count() }}</h5>
                    <p class="text-muted mb-0 fs-8 fw-semibold text-uppercase">Terlambat</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Filter & Table -->
<div class="card p-4 bg-white">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <!-- Search and Filter Form -->
        <form action="{{ route('admin.proyek.index') }}" method="GET" class="d-flex flex-wrap gap-2 align-items-center m-0">
            <div class="input-group" style="max-width: 250px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 fs-7" placeholder="Cari nama/kode..." value="{{ $search }}">
            </div>

            <select name="status" class="form-select fs-7" style="max-width: 150px;">
                <option value="">Semua Status</option>
                <option value="berjalan" {{ $status === 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="terlambat" {{ $status === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
            </select>

            <button type="submit" class="btn btn-light border btn-sm px-3 fs-7 py-2"><i class="bi bi-funnel me-1"></i> Filter</button>
            @if($search || $status)
                <a href="{{ route('admin.proyek.index') }}" class="btn btn-outline-secondary btn-sm px-3 fs-7 py-2">Reset</a>
            @endif
        </form>

        <!-- Tambah Proyek Button -->
        <a href="{{ route('admin.proyek.create') }}" class="btn btn-primary rounded-3 fs-7 py-2 px-3">
            <i class="bi bi-plus-lg me-1"></i> Tambah Proyek
        </a>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr class="text-muted fs-7">
                    <th scope="col" style="width: 120px;">KODE PROYEK</th>
                    <th scope="col">NAMA PROYEK</th>
                    <th scope="col">LOKASI</th>
                    <th scope="col">KONTRAKTOR</th>
                    <th scope="col">PERIODE</th>
                    <th scope="col" style="width: 160px;">PROGRESS</th>
                    <th scope="col">STATUS</th>
                    <th scope="col" class="text-center" style="width: 100px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($proyeks as $proyek)
                    <tr>
                        <td class="fs-7 fw-bold text-primary">{{ $proyek->kode_proyek }}</td>
                        <td>
                            <div class="fw-semibold text-dark fs-7">{{ $proyek->nama_proyek }}</div>
                        </td>
                        <td class="fs-7 text-dark">{{ $proyek->lokasi->nama_lokasi ?? '-' }}</td>
                        <td class="fs-7 text-muted">{{ $proyek->kontraktor->nama_kontraktor ?? '-' }}</td>
                        <td class="fs-7">
                            <div class="text-dark"><span class="text-muted">Mulai:</span> {{ $proyek->tanggal_mulai->format('d M Y') }}</div>
                            <div class="text-dark"><span class="text-muted">Selesai:</span> {{ $proyek->tanggal_selesai->format('d M Y') }}</div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center justify-content-between mb-1 fs-8 text-muted">
                                <span>Act: {{ number_format($proyek->actual_progress, 2) }}%</span>
                                <span>Tgt: {{ number_format($proyek->target_progress, 2) }}%</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar rounded" role="progressbar" style="width: {{ $proyek->actual_progress }}%" aria-valuenow="{{ $proyek->actual_progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td>
                            @if($proyek->status === 'berjalan')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-8">BERJALAN</span>
                            @elseif($proyek->status === 'selesai')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-8">SELESAI</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-8">TERLAMBAT</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('admin.proyek.edit', $proyek->id) }}" class="btn btn-sm btn-light border text-primary" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('admin.proyek.destroy', $proyek->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4 fs-7">Tidak ada data proyek ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <p class="text-muted fs-7 mb-0">Menampilkan {{ $proyeks->firstItem() ?? 0 }} sampai {{ $proyeks->lastItem() ?? 0 }} dari {{ $proyeks->total() }} proyek</p>
        <div>
            {{ $proyeks->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
