@extends('layouts.admin')

@section('title', 'Laporan Mingguan - SISMOKAP')
@section('page_title', 'Laporan Progress Mingguan')

@section('content')
<div class="card p-4 bg-white">
    <!-- Header/Filter -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 border-bottom pb-3 mb-4">
        <form action="{{ route('laporan.mingguan') }}" method="GET" class="row g-2 align-items-end m-0 w-100" style="max-width: 600px;">
            <div class="col-6 col-sm-4">
                <label for="minggu_ke" class="form-label fs-8 fw-semibold text-muted mb-1 text-uppercase">Minggu Ke-</label>
                <input type="number" name="minggu_ke" id="minggu_ke" min="1" max="53" class="form-control fs-7" value="{{ $mingguKe }}">
            </div>
            <div class="col-6 col-sm-4">
                <label for="tahun" class="form-label fs-8 fw-semibold text-muted mb-1 text-uppercase">Tahun</label>
                <input type="number" name="tahun" id="tahun" min="2020" max="2100" class="form-control fs-7" value="{{ $tahun }}">
            </div>
            <div class="col-12 col-sm-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm w-100 py-2 fs-7"><i class="bi bi-funnel-fill me-1"></i> Filter</button>
            </div>
        </form>

        <button type="button" onclick="window.print()" class="btn btn-outline-secondary rounded-3 fs-7 py-2 px-3 align-self-md-end">
            <i class="bi bi-printer-fill me-1"></i> Cetak Halaman
        </button>
    </div>

    <!-- Title inside printable area -->
    <div class="text-center mb-4 d-none d-print-block">
        <h3 class="fw-bold">LAPORAN PROGRESS MINGGUAN PROYEK</h3>
        <p class="text-muted">Minggu Ke-{{ $mingguKe }} Tahun {{ $tahun }}</p>
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
                    <th scope="col" style="width: 100px;">PROGRESS</th>
                    <th scope="col" style="width: 100px;">TARGET</th>
                    <th scope="col" style="width: 100px;">SELISIH</th>
                    <th scope="col">STATUS</th>
                    <th scope="col">REKAP MINGGUAN</th>
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
                        <td class="fs-7 fw-bold text-dark text-end">{{ number_format($item['actual'], 2) }}%</td>
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
                        <td class="fs-7 text-muted">{{ $item['keterangan'] }}</td>
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
                        <td colspan="10" class="text-center text-muted py-4 fs-7">Tidak ada data rekap mingguan pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
