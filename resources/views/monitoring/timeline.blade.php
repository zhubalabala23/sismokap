@extends('layouts.admin')

@section('title', 'Timeline Progress - SISMOKAP')
@section('page_title', 'Timeline Riwayat Progress')

@section('styles')
<style>
    /* Timeline styling */
    .timeline {
        position: relative;
        padding-left: 3rem;
        margin-bottom: 2rem;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 15px;
        width: 2px;
        background: #e2e8f0;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 2.5rem;
    }
    
    .timeline-marker {
        position: absolute;
        left: -33px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #ffffff;
        border: 4px solid #2d60ff;
        z-index: 1;
        transition: all 0.2s ease-in-out;
    }

    .timeline-marker.Harian {
        border-color: #0d6efd;
    }

    .timeline-marker.Mingguan {
        border-color: #fd7e14;
    }
    
    .timeline-item:hover .timeline-marker {
        transform: scale(1.3);
    }
    
    .timeline-content {
        background: #ffffff;
        border-radius: 12px;
        padding: 18px 24px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        border: 1px solid rgba(0, 0, 0, 0.04);
    }
</style>
@endsection

@section('content')
<div class="row g-4">
    <!-- Project Selector Card -->
    <div class="col-12">
        <div class="card p-4 bg-white">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                <div>
                    <h5 class="fw-bold text-dark mb-1">Pilih Proyek untuk Timeline</h5>
                    <p class="text-muted mb-0 fs-7">Tampilkan riwayat log progress harian dan mingguan secara vertikal</p>
                </div>
                
                <form action="{{ route('monitoring.timeline') }}" method="GET" class="d-flex gap-2 align-items-center m-0">
                    <select name="proyek_id" class="form-select fs-7" style="min-width: 250px;" onchange="this.form.submit()">
                        <option value="">Pilih Proyek...</option>
                        @foreach($proyeks as $proyek)
                            <option value="{{ $proyek->id }}" {{ $proyekId == $proyek->id ? 'selected' : '' }}>{{ $proyek->nama_proyek }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>

    <!-- Timeline Render -->
    <div class="col-12">
        @if($selectedProyek)
            <div class="card p-4 bg-white">
                <div class="border-bottom pb-3 mb-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">{{ $selectedProyek->nama_proyek }}</h4>
                        <p class="text-muted mb-0 fs-7">
                            <i class="bi bi-tag-fill me-1"></i> Kode: <strong class="text-primary">{{ $selectedProyek->kode_proyek }}</strong> | 
                            <i class="bi bi-geo-alt-fill me-1"></i> Lokasi: <strong>{{ $selectedProyek->lokasi->nama_lokasi ?? '-' }}</strong> | 
                            <i class="bi bi-building-fill me-1"></i> Kontraktor: <strong>{{ $selectedProyek->kontraktor->nama_kontraktor ?? '-' }}</strong>
                        </p>
                    </div>
                    <div>
                        @if($selectedProyek->status === 'berjalan')
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fs-7">BERJALAN</span>
                        @elseif($selectedProyek->status === 'selesai')
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fs-7">SELESAI</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 fs-7">TERLAMBAT</span>
                        @endif
                    </div>
                </div>

                @if($timelineData->count() > 0)
                    <div class="timeline mt-3">
                        @foreach($timelineData as $item)
                            <div class="timeline-item">
                                <div class="timeline-marker {{ $item['tipe'] }}"></div>
                                <div class="timeline-content">
                                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge {{ $item['badge_color'] }} px-2 py-1 fs-8 text-uppercase">{{ $item['tipe'] }}</span>
                                            <h6 class="mb-0 fw-bold text-dark fs-7">{{ $item['tanggal_formatted'] }}</h6>
                                        </div>
                                        <div class="fs-6 fw-extrabold text-primary">{{ number_format($item['persentase'], 2) }}%</div>
                                    </div>
                                    
                                    <p class="text-dark fs-7 mb-2">{{ $item['keterangan'] ?? 'Tidak ada catatan progress.' }}</p>
                                    
                                    <div class="d-flex align-items-center justify-content-between border-top pt-2 mt-2 fs-8 text-muted">
                                        <span><i class="bi bi-person-fill me-1"></i> Diinput oleh: <strong>{{ $item['input_by'] }}</strong></span>
                                        <span>Target Akhir Proyek: <strong>{{ number_format($selectedProyek->target_progress, 2) }}%</strong></span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x fs-1 text-muted mb-3 d-block"></i>
                        <p class="text-muted fs-6 mb-0">Belum ada riwayat progress harian atau mingguan yang tercatat untuk proyek ini.</p>
                    </div>
                @endif
            </div>
        @else
            <div class="card p-5 bg-white text-center">
                <i class="bi bi-folder-symlink fs-1 text-muted mb-3"></i>
                <h5>Silakan Pilih Proyek Terlebih Dahulu</h5>
                <p class="text-muted fs-7 mb-0">Pilih proyek pada dropdown di atas untuk melihat timeline riwayat progress secara rinci.</p>
            </div>
        @endif
    </div>
</div>
@endsection
