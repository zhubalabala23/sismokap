@extends('layouts.admin')

@section('title', 'Dashboard - SISMOKAP')
@section('page_title', 'Dashboard')

@section('content')
<!-- Row 1: Summary Cards -->
<div class="row g-4 mb-4">
    <!-- Total Proyek -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card p-3 border-0 bg-white h-100 position-relative overflow-hidden">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary-subtle p-3 text-primary me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                    <i class="bi bi-file-earmark-text-fill fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 12px; letter-spacing: 0.5px;">Total Proyek</p>
                    <h3 class="mb-0 fw-bold text-dark">{{ $totalProyek }}</h3>
                </div>
            </div>
            <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                <span class="text-muted fs-7">Selengkapnya</span>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.proyek.index') }}" class="text-primary text-decoration-none fs-7 fw-semibold">
                        Detail <i class="bi bi-arrow-right-short"></i>
                    </a>
                @else
                    <a href="{{ route('monitoring.persentase-progress') }}" class="text-primary text-decoration-none fs-7 fw-semibold">
                        Detail <i class="bi bi-arrow-right-short"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Proyek Berjalan -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card p-3 border-0 bg-white h-100">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-success-subtle p-3 text-success me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                    <i class="bi bi-play-circle-fill fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 12px; letter-spacing: 0.5px;">Proyek Berjalan</p>
                    <h3 class="mb-0 fw-bold text-dark">{{ $proyekBerjalan }}</h3>
                </div>
            </div>
            <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                <span class="text-muted fs-7">Active</span>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.proyek.index', ['status' => 'berjalan']) }}" class="text-success text-decoration-none fs-7 fw-semibold">
                        Detail <i class="bi bi-arrow-right-short"></i>
                    </a>
                @else
                    <a href="{{ route('monitoring.persentase-progress') }}" class="text-success text-decoration-none fs-7 fw-semibold">
                        Detail <i class="bi bi-arrow-right-short"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Proyek Selesai -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card p-3 border-0 bg-white h-100">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-warning-subtle p-3 text-warning me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                    <i class="bi bi-check-circle-fill fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 12px; letter-spacing: 0.5px;">Proyek Selesai</p>
                    <h3 class="mb-0 fw-bold text-dark">{{ $proyekSelesai }}</h3>
                </div>
            </div>
            <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                <span class="text-muted fs-7">Closed</span>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.proyek.index', ['status' => 'selesai']) }}" class="text-warning text-decoration-none fs-7 fw-semibold">
                        Detail <i class="bi bi-arrow-right-short"></i>
                    </a>
                @else
                    <a href="{{ route('monitoring.persentase-progress') }}" class="text-warning text-decoration-none fs-7 fw-semibold">
                        Detail <i class="bi bi-arrow-right-short"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Proyek Terlambat -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card p-3 border-0 bg-white h-100">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-danger-subtle p-3 text-danger me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                    <i class="bi bi-exclamation-octagon-fill fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 12px; letter-spacing: 0.5px;">Proyek Terlambat</p>
                    <h3 class="mb-0 fw-bold text-dark">{{ $proyekTerlambat }}</h3>
                </div>
            </div>
            <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                <span class="text-muted fs-7">Alert</span>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.proyek.index', ['status' => 'terlambat']) }}" class="text-danger text-decoration-none fs-7 fw-semibold">
                        Detail <i class="bi bi-arrow-right-short"></i>
                    </a>
                @else
                    <a href="{{ route('monitoring.persentase-progress') }}" class="text-danger text-decoration-none fs-7 fw-semibold">
                        Detail <i class="bi bi-arrow-right-short"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Chart & Progress Table -->
<div class="row g-4 mb-4">
    <!-- Line Chart -->
    <div class="col-12 col-lg-7">
        <div class="card p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-1" style="color: #1b2559;">Grafik Progress Proyek</h5>
                    <p class="text-muted mb-0 fs-7">Monitoring real-time pencapaian target fisik bulanan</p>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge bg-light text-dark border p-2">2024</span>
                    <button class="btn btn-light btn-sm border"><i class="bi bi-funnel"></i></button>
                </div>
            </div>
            <div style="position: relative; height: 320px;">
                <canvas id="progressChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Progress Proyek Table -->
    <div class="col-12 col-lg-5">
        <div class="card p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0" style="color: #1b2559;">Progress Proyek</h5>
                <a href="{{ route('monitoring.persentase-progress') }}" class="text-primary text-decoration-none fs-7 fw-semibold">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-muted fs-7">
                            <th scope="col">PROYEK</th>
                            <th scope="col" style="width: 120px;">PROGRESS</th>
                            <th scope="col">TARGET</th>
                            <th scope="col">SELISIH</th>
                            <th scope="col">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($progressProyek as $item)
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark fs-7">{{ $item['nama_proyek'] }}</div>
                                </td>
                                <td>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar rounded" role="progressbar" style="width: {{ $item['actual'] }}%" aria-valuenow="{{ $item['actual'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="fs-8 text-muted mt-1 d-block">{{ number_format($item['actual'], 2) }}%</span>
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
                                <td colspan="5" class="text-center text-muted py-4 fs-7">Belum ada data proyek.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Row 3: Proyek Terbaru Table & Dokumentasi Terbaru -->
<div class="row g-4">
    <!-- Proyek Terbaru Table -->
    <div class="col-12 col-lg-8">
        <div class="card p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0" style="color: #1b2559;">Proyek Terbaru</h5>
                @if(auth()->user()->role !== 'pimpinan' && auth()->user()->role === 'admin')
                    <a href="{{ route('admin.proyek.create') }}" class="btn btn-primary btn-sm rounded-3">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Proyek
                    </a>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr class="text-muted fs-7">
                            <th scope="col">NO</th>
                            <th scope="col">KODE PROYEK</th>
                            <th scope="col">NAMA PROYEK</th>
                            <th scope="col">LOKASI</th>
                            <th scope="col">PROGRESS</th>
                            <th scope="col">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proyekTerbaru as $index => $proyek)
                            <tr>
                                <td class="fs-7">{{ $index + 1 }}</td>
                                <td class="fs-7 fw-semibold text-primary">{{ $proyek->kode_proyek }}</td>
                                <td>
                                    <span class="fw-semibold text-dark fs-7">{{ $proyek->nama_proyek }}</span>
                                </td>
                                <td class="fs-7 text-muted">{{ $proyek->lokasi->nama_lokasi ?? '-' }}</td>
                                <td>
                                    <div class="progress" style="height: 6px; width: 120px;">
                                        <div class="progress-bar rounded" role="progressbar" style="width: {{ $proyek->actual_progress }}%" aria-valuenow="{{ $proyek->actual_progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="fs-8 text-muted mt-1 d-block">{{ number_format($proyek->actual_progress, 2) }}%</span>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4 fs-7">Belum ada proyek terbaru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Dokumentasi Terbaru Gallery -->
    <div class="col-12 col-lg-4">
        <div class="card p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0" style="color: #1b2559;">Dokumentasi Terbaru</h5>
                <a href="{{ route('dokumentasi.index') }}" class="text-primary text-decoration-none fs-7 fw-semibold">Lihat Semua</a>
            </div>
            <div class="row g-2">
                @foreach($dokumentasiTerbaru as $foto)
                    <div class="col-6">
                        <div class="card border p-1 rounded-3 h-100 hover-shadow" style="transition: all 0.2s;">
                            <img src="{{ $foto['file_path'] }}" class="card-img-top rounded-3 object-fit-cover" style="height: 100px;" alt="Dokumentasi">
                            <div class="card-body p-2">
                                <h6 class="card-title fw-bold fs-8 mb-1 text-truncate" style="color: #1b2559;">{{ $foto['nama_proyek'] }}</h6>
                                <p class="card-text fs-9 text-muted mb-0">{{ $foto['tanggal'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('progressChart').getContext('2d');
    
    // Gradient configuration
    const datasetConfigs = @json($chartData['datasets']);
    
    const datasets = datasetConfigs.map((ds) => {
        return {
            label: ds.label,
            data: ds.data,
            borderColor: ds.borderColor,
            backgroundColor: ds.backgroundColor,
            tension: ds.tension,
            borderWidth: 3,
            pointRadius: 4,
            pointHoverRadius: 6
        };
    });

    const progressChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels']),
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                        font: {
                            family: 'Inter',
                            size: 12,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    padding: 12,
                    cornerRadius: 8,
                    bodyFont: {
                        family: 'Inter'
                    },
                    titleFont: {
                        family: 'Inter',
                        weight: 'bold'
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            family: 'Inter',
                            size: 11
                        }
                    }
                },
                y: {
                    min: 0,
                    max: 100,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        },
                        font: {
                            family: 'Inter',
                            size: 11
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
