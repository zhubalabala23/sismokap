@extends('layouts.admin')

@section('title', 'Timeline Proyek - SISMOKAP')
@section('page_title', 'Timeline Pelaksanaan Proyek')

@section('content')
<div class="card p-4 bg-white">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-1">Timeline & Jadwal Pelaksanaan Proyek</h5>
            <p class="text-muted mb-0 fs-7">Membandingkan jadwal rencana dan status realisasi seluruh proyek</p>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <!-- Search Box -->
            <form action="{{ route('monitoring.timeline') }}" method="GET" class="d-flex gap-2 align-items-center m-0">
                <div class="input-group" style="max-width: 250px;">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 fs-7" placeholder="Cari kode, nama, tahapan..." value="{{ $search ?? '' }}">
                </div>
                @if($search ?? false)
                    <a href="{{ route('monitoring.timeline') }}" class="btn btn-outline-secondary btn-sm px-3 fs-7 py-2">Reset</a>
                @endif
            </form>
            <button type="button" onclick="window.print()" class="btn btn-outline-secondary rounded-3 fs-7 py-2 px-3">
                <i class="bi bi-printer-fill me-1"></i> Cetak Halaman
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr class="text-muted fs-7">
                    <th scope="col" class="text-center" style="width: 50px;">NO</th>
                    <th scope="col">KODE & NAMA PROYEK</th>
                    <th scope="col">TAHAPAN PEKERJAAN</th>
                    <th scope="col" style="width: 130px;">TANGGAL MULAI</th>
                    <th scope="col" style="width: 130px;">TANGGAL SELESAI</th>
                    <th scope="col" style="width: 180px;">STATUS PEKERJAAN</th>
                    <th scope="col">KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse($proyeks as $index => $proyek)
                    <tr>
                        <td class="text-center fs-7">{{ $index + 1 }}</td>
                        <td>
                            <div class="fs-7 fw-bold text-primary">{{ $proyek->kode_proyek }}</div>
                            <div class="fs-7 fw-semibold text-dark">{{ $proyek->nama_proyek }}</div>
                            <div class="fs-8 text-muted mt-1">
                                <i class="bi bi-geo-alt-fill me-1"></i>{{ $proyek->lokasi?->nama_lokasi ?? '-' }}
                            </div>
                        </td>
                        <td class="fs-7 fw-semibold text-dark">
                            {{ $proyek->tahapan_pekerjaan ?? 'Belum Ditentukan' }}
                        </td>
                        <td class="fs-7 text-dark text-nowrap">
                            <i class="bi bi-calendar-event text-primary me-1"></i>
                            {{ $proyek->tanggal_mulai ? $proyek->tanggal_mulai->format('d M Y') : '-' }}
                        </td>
                        <td class="fs-7 text-dark text-nowrap">
                            <i class="bi bi-calendar-check text-success me-1"></i>
                            {{ $proyek->tanggal_selesai ? $proyek->tanggal_selesai->format('d M Y') : '-' }}
                        </td>
                        <td>
                            @if($proyek->status === 'perencanaan')
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 fs-8">PERENCANAAN</span>
                            @elseif($proyek->status === 'berjalan')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-8">BERJALAN</span>
                            @elseif($proyek->status === 'selesai')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-8">SELESAI PENGERJAAN</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-8">TERLAMBAT</span>
                            @endif
                        </td>
                        <td class="fs-7 text-muted">
                            {{ $proyek->keterangan ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4 fs-7">Tidak ada data proyek ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
