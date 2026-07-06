@extends('layouts.admin')

@section('title', 'Persentase Progress - SISMOKAP')
@section('page_title', 'Persentase Progress Proyek')

@section('content')
<div class="row g-4">
    <!-- Chart Card -->
    <div class="col-12">
        <div class="card p-4 bg-white">
            <h5 class="fw-bold text-dark mb-3 text-center text-md-start">Grafik Perkembangan Proyek (Target vs Realisasi)</h5>
            <div class="chart-container" style="position: relative; height: 320px; width: 100%;">
                <canvas id="progressComparisonChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Filter & Table Card -->
    <div class="col-12">
        <div class="card p-4 bg-white">
            <!-- Filter & Search Form -->
            <div class="border-bottom pb-3 mb-4">
                <h5 class="fw-bold text-dark mb-3">Filter & Cari Data Progress</h5>
                <form action="{{ route('monitoring.persentase-progress') }}" method="GET" class="row g-3 align-items-end">
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
                        <input type="date" name="start_date" id="start_date" class="form-control fs-7" value="{{ $startDate }}">
                    </div>
                    
                    <!-- End Date -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label for="end_date" class="form-label fs-8 fw-semibold text-muted mb-1 text-uppercase">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end_date" class="form-control fs-7" value="{{ $endDate }}">
                    </div>
                    
                    <!-- Actions -->
                    <div class="col-12 col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 py-2 fs-7"><i class="bi bi-funnel-fill me-1"></i> Saring</button>
                        @if($startDate || $endDate || $search)
                            <a href="{{ route('monitoring.persentase-progress') }}" class="btn btn-outline-secondary py-2 px-3 fs-7">Reset</a>
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
                            <th scope="col" class="text-center" style="width: 130px;">PERSENTASE CAPAIAN</th>
                            <th scope="col" class="text-center" style="width: 120px;">SELISIH TARGET</th>
                            <th scope="col" class="text-center" style="width: 130px;">STATUS PROYEK</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($progressData as $index => $item)
                            <tr class="text-nowrap">
                                <td class="text-center fs-7">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fs-7 fw-bold text-primary">{{ $item['kode_proyek'] }}</div>
                                    <div class="fs-7 fw-semibold text-dark">{{ $item['nama_proyek'] }}</div>
                                </td>
                                <td class="fs-7 text-dark text-wrap" style="max-width: 180px;">{{ $item['lokasi'] }}</td>
                                <td class="fs-7 text-muted text-wrap" style="max-width: 180px;">{{ $item['kontraktor'] }}</td>
                                <td class="fs-7 text-dark fw-bold text-center">{{ number_format($item['target'], 2) }}%</td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-between mb-1 fs-8 text-muted">
                                        <span>Progres: <strong>{{ number_format($item['actual'], 2) }}%</strong></span>
                                    </div>
                                    <div class="progress" style="height: 10px; border-radius: 6px;">
                                        <div class="progress-bar bg-primary rounded-3" role="progressbar" style="width: {{ $item['actual'] }}%" aria-valuenow="{{ $item['actual'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                                <!-- Bobot Pekerjaan & Persentase Capaian (Fisik Aktual) -->
                                <td class="fs-7 text-dark fw-bold text-center bg-light">{{ number_format($item['actual'], 2) }}%</td>
                                <td class="fs-7 text-primary fw-bold text-center">{{ number_format($item['actual'], 2) }}%</td>
                                <td class="fs-7 text-center fw-bold @if($item['selisih'] < 0) text-danger @elseif($item['selisih'] > 0) text-success @else text-muted @endif">
                                    {{ $item['selisih'] > 0 ? '+' : '' }}{{ number_format($item['selisih'], 2) }}%
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
                                <td colspan="10" class="text-center text-muted py-4 fs-7">Tidak ada data progress ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3 p-3 bg-light rounded-3">
                <h6 class="fw-bold text-dark fs-7 mb-1"><i class="bi bi-info-circle-fill text-primary me-1"></i> Penjelasan Bobot Pekerjaan & Capaian:</h6>
                <p class="text-muted fs-8 mb-0">Bobot Pekerjaan (Akumulatif) adalah akumulasi bobot fisik pekerjaan konstruksi yang telah selesai dikerjakan di lapangan. Nilai ini sama dengan Persentase Capaian (Realisasi) saat ini. Selisih Target bernilai positif (+) jika capaian melebihi target dan negatif (-) jika capaian terlambat dari target.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('progressComparisonChart').getContext('2d');
        
        const labels = {!! json_encode($chartLabels) !!};
        const targets = {!! json_encode($chartTargets) !!};
        const actuals = {!! json_encode($chartActuals) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Target Progress (%)',
                        data: targets,
                        backgroundColor: 'rgba(13, 110, 253, 0.2)',
                        borderColor: '#0d6efd',
                        borderWidth: 2,
                        borderRadius: 5,
                    },
                    {
                        label: 'Realisasi / Bobot Pekerjaan (%)',
                        data: actuals,
                        backgroundColor: 'rgba(25, 135, 84, 0.8)',
                        borderColor: '#198754',
                        borderWidth: 2,
                        borderRadius: 5,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                family: 'Inter, sans-serif',
                                size: 11
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
