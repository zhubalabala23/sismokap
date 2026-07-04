@extends('layouts.admin')

@section('title', 'Persentase Progress - SISMOKAP')
@section('page_title', 'Persentase Progress Proyek')

@section('content')
<div class="card p-4 bg-white">
    <!-- Filter Date Range -->
    <div class="border-bottom pb-3 mb-4">
        <h5 class="fw-bold text-dark mb-3">Filter Rentang Waktu Progress</h5>
        <form action="{{ route('monitoring.persentase-progress') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-sm-4 col-md-3">
                <label for="start_date" class="form-label fs-8 fw-semibold text-muted mb-1 text-uppercase">Tanggal Awal</label>
                <input type="date" name="start_date" id="start_date" class="form-control form-control-sm fs-7" value="{{ $startDate }}">
            </div>
            <div class="col-12 col-sm-4 col-md-3">
                <label for="end_date" class="form-label fs-8 fw-semibold text-muted mb-1 text-uppercase">Tanggal Akhir</label>
                <input type="date" name="end_date" id="end_date" class="form-control form-control-sm fs-7" value="{{ $endDate }}">
            </div>
            <div class="col-12 col-sm-4 col-md-6 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm px-4 py-2 fs-7"><i class="bi bi-funnel-fill me-1"></i> Saring Data</button>
                @if($startDate || $endDate)
                    <a href="{{ route('monitoring.persentase-progress') }}" class="btn btn-outline-secondary btn-sm px-3 py-2 fs-7">Reset</a>
                @endif
            </div>
        </form>
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
                    <th scope="col" style="width: 180px;">PROGRESS BAR</th>
                    <th scope="col" style="width: 100px;">TARGET</th>
                    <th scope="col" style="width: 100px;">SELISIH</th>
                    <th scope="col">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($progressData as $item)
                    <tr>
                        <td class="fs-7 fw-bold text-primary">{{ $item['kode_proyek'] }}</td>
                        <td>
                            <div class="fw-semibold text-dark fs-7">{{ $item['nama_proyek'] }}</div>
                        </td>
                        <td class="fs-7 text-dark">{{ $item['lokasi'] }}</td>
                        <td class="fs-7 text-muted">{{ $item['kontraktor'] }}</td>
                        <td>
                            <div class="d-flex align-items-center justify-content-between mb-1 fs-8 text-muted">
                                <span>Pencapaian: <strong>{{ number_format($item['actual'], 2) }}%</strong></span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar rounded" role="progressbar" style="width: {{ $item['actual'] }}%" aria-valuenow="{{ $item['actual'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td class="fs-7 text-dark fw-semibold">{{ number_format($item['target'], 2) }}%</td>
                        <td class="fs-7 @if($item['selisih'] > 0) text-danger @else text-success @endif fw-semibold">
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
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4 fs-7">Tidak ada data proyek ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
