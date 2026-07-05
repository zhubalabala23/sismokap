@extends('layouts.admin')

@section('title', 'Laporan Mingguan - SISMOKAP')
@section('page_title', 'Laporan Progress Mingguan')

@section('content')
<div class="card p-4 bg-white">
    <!-- Filter & Search Form -->
    <div class="border-bottom pb-3 mb-4">
        <h5 class="fw-bold text-dark mb-3">Filter & Cari Laporan Progress Mingguan</h5>
        <form action="{{ route('laporan.mingguan') }}" method="GET" class="row g-3 align-items-end">
            <!-- Project Dropdown (Optional) -->
            <div class="col-12 col-md-3">
                <label for="proyek_id" class="form-label fs-8 fw-semibold text-muted mb-1 text-uppercase">Pilih Proyek (Opsional)</label>
                <select name="proyek_id" id="proyek_id" class="form-select fs-7">
                    <option value="">-- Semua Proyek --</option>
                    @foreach($allProyeks as $proy)
                        <option value="{{ $proy->id }}" {{ $proyekId == $proy->id ? 'selected' : '' }}>
                            {{ $proy->nama_proyek }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Week -->
            <div class="col-6 col-sm-3 col-md-2">
                <label for="minggu_ke" class="form-label fs-8 fw-semibold text-muted mb-1 text-uppercase">Minggu Ke-</label>
                <input type="number" name="minggu_ke" id="minggu_ke" min="1" max="53" class="form-control fs-7" value="{{ $mingguKe }}" placeholder="Contoh: 1">
            </div>

            <!-- Year (Periode) -->
            <div class="col-6 col-sm-3 col-md-2">
                <label for="tahun" class="form-label fs-8 fw-semibold text-muted mb-1 text-uppercase">Tahun / Periode</label>
                <input type="number" name="tahun" id="tahun" min="2020" max="2100" class="form-control fs-7" value="{{ $tahun }}" placeholder="Contoh: 2026">
            </div>

            <!-- Buttons -->
            <div class="col-12 col-md-5 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 py-2 fs-7">
                    <i class="bi bi-funnel-fill me-1"></i> Saring
                </button>
                @if($proyekId || $mingguKe || $tahun)
                    <a href="{{ route('laporan.mingguan') }}" class="btn btn-outline-secondary py-2 px-3 fs-7">Reset</a>
                @endif
                <a href="{{ route('laporan.export-mingguan-pdf', ['proyek_id' => $proyekId, 'minggu_ke' => $mingguKe, 'tahun' => $tahun]) }}" class="btn btn-danger py-2 px-3 fs-7 text-nowrap rounded-3">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak PDF
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr class="text-muted fs-7 text-nowrap">
                    <th scope="col">MINGGU / TAHUN</th>
                    <th scope="col">PROYEK</th>
                    <th scope="col">SEBELUMNYA</th>
                    <th scope="col">MINGGU INI</th>
                    <th scope="col">KUMULATIF</th>
                    <th scope="col">TARGET</th>
                    <th scope="col">SELISIH</th>
                    <th scope="col">KENDALA</th>
                    <th scope="col">RENCANA DEPAN</th>
                    <th scope="col">KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $entry)
                    <tr class="text-nowrap fs-7">
                        <td class="fw-semibold text-dark">Minggu ke-{{ $entry->minggu_ke }} ({{ $entry->tahun }})</td>
                        <td class="fw-semibold text-primary">
                            {{ $entry->proyek->nama_proyek }}
                            <div class="text-muted fs-8 fw-normal">[{{ $entry->proyek->kode_proyek }}]</div>
                        </td>
                        <td class="text-dark">{{ number_format($entry->progress_sebelumnya, 2) }}%</td>
                        <td class="text-dark">{{ number_format($entry->progress_berjalan, 2) }}%</td>
                        <td class="fw-bold text-dark">{{ number_format($entry->persentase, 2) }}%</td>
                        <td class="text-dark">{{ number_format($entry->target_mingguan, 2) }}%</td>
                        <td class="fw-bold {{ $entry->selisih_capaian >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $entry->selisih_capaian >= 0 ? '+' : '' }}{{ number_format($entry->selisih_capaian, 2) }}%
                        </td>
                        <td class="text-muted text-wrap" style="max-width: 150px;">{{ $entry->kendala ?? '-' }}</td>
                        <td class="text-muted text-wrap" style="max-width: 150px;">{{ $entry->rencana_berikutnya ?? '-' }}</td>
                        <td class="text-muted text-wrap" style="max-width: 150px;">{{ $entry->keterangan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4 fs-7">Tidak ada data progress mingguan ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
