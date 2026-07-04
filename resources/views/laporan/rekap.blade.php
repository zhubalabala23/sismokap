@extends('layouts.admin')

@section('title', 'Rekap Progress - SISMOKAP')
@section('page_title', 'Rekapitulasi Progress Proyek')

@section('content')
<div class="card p-4 bg-white">
    <!-- Header/Filter -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-1">Seluruh Proyek Konstruksi</h5>
            <p class="text-muted mb-0 fs-7">Rangkuman komprehensif pencapaian awal vs pencapaian terkini</p>
        </div>

        <div class="d-flex gap-2">
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('laporan.export-excel') }}" class="btn btn-success rounded-3 fs-7 py-2 px-3">
                    <i class="bi bi-file-earmark-excel-fill me-1"></i> Export Excel
                </a>
            @endif
            <button type="button" onclick="window.print()" class="btn btn-outline-secondary rounded-3 fs-7 py-2 px-3">
                <i class="bi bi-printer-fill me-1"></i> Cetak Halaman
            </button>
        </div>
    </div>

    <!-- Title inside printable area -->
    <div class="text-center mb-4 d-none d-print-block">
        <h3 class="fw-bold">REKAPITULASI PROGRESS PROYEK KESELURUHAN</h3>
        <p class="text-muted">Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr class="text-muted fs-7">
                    <th scope="col" style="width: 120px;">KODE PROYEK</th>
                    <th scope="col">NAMA PROYEK</th>
                    <th scope="col">LOKASI</th>
                    <th scope="col">KONTRAKTOR</th>
                    <th scope="col" style="width: 100px;">PROGRESS AWAL</th>
                    <th scope="col" style="width: 100px;">PROGRESS KINI</th>
                    <th scope="col" style="width: 100px;">TARGET</th>
                    <th scope="col" style="width: 100px;">SELISIH KINI</th>
                    <th scope="col">STATUS</th>
                    <th scope="col" class="text-center d-print-none" style="width: 120px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $item)
                    <tr>
                        <td class="fs-7 fw-bold text-primary">{{ $item['kode_proyek'] }}</td>
                        <td class="fs-7 fw-semibold text-dark">{{ $item['nama_proyek'] }}</td>
                        <td class="fs-7 text-dark">{{ $item['lokasi'] }}</td>
                        <td class="fs-7 text-muted">{{ $item['kontraktor'] }}</td>
                        <td class="fs-7 fw-bold text-muted text-end">{{ number_format($item['awal'], 2) }}%</td>
                        <td class="fs-7 fw-bold text-dark text-end">{{ number_format($item['terkini'], 2) }}%</td>
                        <td class="fs-7 text-dark fw-semibold text-end">{{ number_format($item['target'], 2) }}%</td>
                        <td class="fs-7 @if($item['selisih'] > 0) text-danger @else text-success @endif fw-semibold text-end">
                            {{ $item['selisih'] > 0 ? '+' : '' }}{{ number_format($item['selisih'], 2) }}%
                        </td>
                        <td>
                            @if($item['status'] === 'berjalan')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-8">BERJALAN</span>
                            @elseif($item['status'] === 'selesai')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-8">SELESAI</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-8">TERLAMBAT</span>
                            @endif
                        </td>
                        <td class="text-center d-print-none">
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('laporan.export-pdf', $item['id']) }}" class="btn btn-sm btn-danger fs-8 px-2 py-1">
                                    <i class="bi bi-file-earmark-pdf-fill"></i> Cetak PDF
                                </a>
                            @else
                                <span class="text-muted fs-8">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4 fs-7">Tidak ada data rekap proyek.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
