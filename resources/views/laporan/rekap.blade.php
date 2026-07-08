@extends('layouts.admin')

@section('title', 'Rekap Progress - SISMOKAP')
@section('page_title', 'Rekapitulasi Progress Proyek')

@section('content')
<div class="card p-4 bg-white">
    <!-- Header/Filter -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-1">Seluruh Proyek Konstruksi</h5>
            <p class="text-muted mb-0 fs-7">Rangkuman komprehensif pencapaian target vs realisasi saat ini</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('laporan.export-rekap-pdf', ['search' => $search ?? '', 'start_date' => $startDate ?? '', 'end_date' => $endDate ?? '']) }}" class="btn btn-danger rounded-3 fs-7 py-2 px-3">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak PDF
            </a>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('laporan.export-excel') }}" class="btn btn-success rounded-3 fs-7 py-2 px-3">
                    <i class="bi bi-file-earmark-excel-fill me-1"></i> Export Excel
                </a>
            @endif
        </div>
    </div>

    <!-- Filter & Search Form -->
    <div class="border-bottom pb-3 mb-4">
        <h5 class="fw-bold text-dark mb-3">Filter & Cari Data Progress</h5>
        <form action="{{ route('laporan.rekap') }}" method="GET" class="row g-3 align-items-end">
            <!-- Search Box -->
            <div class="col-12 col-md-3">
                <label for="search" class="form-label fs-8 fw-semibold text-muted mb-1 text-uppercase">Cari Proyek</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" id="search" class="form-control fs-7" placeholder="Kode atau nama proyek..." value="{{ $search ?? '' }}">
                </div>
            </div>
            
            <!-- Start Date -->
            <div class="col-12 col-sm-6 col-md-3">
                <label for="start_date" class="form-label fs-8 fw-semibold text-muted mb-1 text-uppercase">Tanggal Awal</label>
                <input type="date" name="start_date" id="start_date" class="form-control fs-7" value="{{ $startDate ?? '' }}">
            </div>
            
            <!-- End Date -->
            <div class="col-12 col-sm-6 col-md-3">
                <label for="end_date" class="form-label fs-8 fw-semibold text-muted mb-1 text-uppercase">Tanggal Akhir</label>
                <input type="date" name="end_date" id="end_date" class="form-control fs-7" value="{{ $endDate ?? '' }}">
            </div>
            
            <!-- Actions -->
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 py-2 fs-7"><i class="bi bi-funnel-fill me-1"></i> Saring</button>
                @if(($startDate ?? '') || ($endDate ?? '') || ($search ?? ''))
                    <a href="{{ route('laporan.rekap') }}" class="btn btn-outline-secondary py-2 px-3 fs-7">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <table class="table table-bordered table-hover align-middle" style="min-width: 1200px;">
            <thead class="table-light">
                <tr class="text-muted fs-7 text-nowrap">
                    <th scope="col" class="text-center" style="width: 50px;">NO</th>
                    <th scope="col" style="width: 250px;">KODE & NAMA PROYEK</th>
                    <th scope="col">LOKASI</th>
                    <th scope="col">PELAKSANA</th>
                    <th scope="col" class="text-center" style="width: 100px;">TARGET PROGRESS</th>
                    <th scope="col" style="width: 200px;">REALISASI PROGRESS</th>
                    <th scope="col" class="text-center" style="width: 130px;">BOBOT PEKERJAAN (AKUMULATIF)</th>
                    <th scope="col" class="text-center" style="width: 130px;">PERSENTASE PENYELESAIAN</th>
                    <th scope="col" class="text-center" style="width: 120px;">SELISIH TARGET</th>
                    <th scope="col" class="text-center" style="width: 100px;">GAMBAR PROYEK</th>
                    <th scope="col" class="text-center" style="width: 130px;">STATUS PROYEK</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $index => $item)
                    <tr class="text-nowrap fs-7">
                        <td class="text-center">{{ $reportData->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-bold text-primary">{{ $item['kode_proyek'] }}</div>
                            <div class="fw-semibold text-dark">{{ $item['nama_proyek'] }}</div>
                        </td>
                        <td class="text-dark text-wrap" style="max-width: 180px;">{{ $item['lokasi'] }}</td>
                        <td class="text-muted text-wrap" style="max-width: 180px;">{{ $item['kontraktor'] }}</td>
                        <td class="text-dark fw-bold text-center">{{ number_format($item['target'], 2) }}%</td>
                        <td>
                            <div class="d-flex align-items-center justify-content-between mb-1 fs-8 text-muted">
                                <span>Progres: <strong>{{ number_format($item['actual'], 2) }}%</strong></span>
                            </div>
                            <div class="progress" style="height: 10px; border-radius: 6px;">
                                <div class="progress-bar bg-primary rounded-3" role="progressbar" style="width: {{ $item['actual'] }}%" aria-valuenow="{{ $item['actual'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <!-- Bobot Pekerjaan & Persentase Penyelesaian (Fisik Aktual) -->
                        <td class="text-dark fw-bold text-center bg-light">{{ number_format($item['actual'], 2) }}%</td>
                        <td class="text-primary fw-bold text-center">{{ number_format($item['actual'], 2) }}%</td>
                        <td class="text-center fw-bold @if($item['selisih'] < 0) text-danger @elseif($item['selisih'] > 0) text-success @else text-muted @endif">
                            {{ $item['selisih'] > 0 ? '+' : '' }}{{ number_format($item['selisih'], 2) }}%
                        </td>
                        <td class="text-center">
                            @if($item['gambar_proyek_url'])
                                <img src="{{ $item['gambar_proyek_url'] }}" alt="Proyek" class="rounded border" style="height: 40px; width: 60px; object-fit: cover;">
                            @else
                                <span class="text-muted fs-8">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item['status'] === 'perencanaan')
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 fs-8">PERENCANAAN</span>
                            @elseif($item['status'] === 'berjalan')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-8">BERJALAN</span>
                            @elseif($item['status'] === 'selesai')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-8">SELESAI</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-8">TERLAMBAT</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted py-4 fs-7">Tidak ada data progress ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-3 border-top pt-3">
        <p class="text-muted fs-7 mb-0">Menampilkan {{ $reportData->firstItem() ?? 0 }} sampai {{ $reportData->lastItem() ?? 0 }} dari {{ $reportData->total() }} proyek</p>
        <div>
            {{ $reportData->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <div class="mt-3 p-3 bg-light rounded-3">
        <h6 class="fw-bold text-dark fs-7 mb-1"><i class="bi bi-info-circle-fill text-primary me-1"></i> Penjelasan Rekapitulasi:</h6>
        <p class="text-muted fs-8 mb-0">Persentase Penyelesaian & Bobot Pekerjaan (Akumulatif) adalah capaian realisasi fisik proyek saat ini yang diakumulasi secara otomatis dari seluruh input progres harian dan mingguan. Selisih Target bernilai positif (+) jika capaian melebihi target dan negatif (-) jika capaian terlambat dari target.</p>
    </div>
</div>
@endsection
