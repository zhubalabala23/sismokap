@extends('layouts.admin')

@section('title', 'Laporan Harian - SISMOKAP')
@section('page_title', 'Laporan Progress Harian')

@section('content')
<div class="card p-4 bg-white">
    <!-- Filter & Search Form -->
    <div class="border-bottom pb-3 mb-4">
        <h5 class="fw-bold text-dark mb-3">Filter & Cari Laporan Progress Harian</h5>
        <form action="{{ route('laporan.harian') }}" method="GET" class="row g-3 align-items-end">
            <!-- Project Dropdown (Optional for filtering specific project) -->
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

            <!-- Buttons -->
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 py-2 fs-7">
                    <i class="bi bi-funnel-fill me-1"></i> Saring
                </button>
                @if($proyekId || $startDate || $endDate)
                    <a href="{{ route('laporan.harian') }}" class="btn btn-outline-secondary py-2 px-3 fs-7">Reset</a>
                @endif
                <a href="{{ route('laporan.export-harian-pdf', ['proyek_id' => $proyekId, 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-danger py-2 px-3 fs-7 text-nowrap rounded-3">
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
                    <th scope="col">TANGGAL PELAKSANAAN</th>
                    <th scope="col">PROYEK</th>
                    <th scope="col">URAIAN PEKERJAAN</th>
                    <th scope="col">VOLUME</th>
                    <th scope="col">BOBOT (AKUMULATIF)</th>
                    <th scope="col">PROGRES HARIAN</th>
                    <th scope="col">KENDALA</th>
                    <th scope="col">SOLUSI</th>
                    <th scope="col">PETUGAS INPUT</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $entry)
                    <tr class="text-nowrap fs-7">
                        <td class="fw-semibold text-dark">{{ $entry->tanggal_pelaksanaan->format('d M Y') }}</td>
                        <td class="fw-semibold text-primary">
                            {{ $entry->proyek?->nama_proyek ?? 'Proyek Terhapus' }}
                            <div class="text-muted fs-8 fw-normal">[{{ $entry->proyek?->kode_proyek ?? '-' }}]</div>
                        </td>
                        <td class="text-dark text-wrap" style="max-width: 250px;">{{ $entry->uraian_pekerjaan ?? '-' }}</td>
                        <td class="text-dark">{{ $entry->volume_pekerjaan ?? '-' }}</td>
                        <td class="fw-bold text-dark">{{ number_format($entry->persentase, 2) }}%</td>
                        <td class="fw-bold text-success">{{ number_format($entry->progres_harian, 2) }}%</td>
                        <td class="text-muted text-wrap" style="max-width: 150px;">{{ $entry->kendala ?? '-' }}</td>
                        <td class="text-muted text-wrap" style="max-width: 150px;">{{ $entry->solusi ?? '-' }}</td>
                        <td class="text-dark">{{ $entry->user->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4 fs-7">Tidak ada data progress harian ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-3 border-top pt-3">
        <p class="text-muted fs-7 mb-0">Menampilkan {{ $reportData->firstItem() ?? 0 }} sampai {{ $reportData->lastItem() ?? 0 }} dari {{ $reportData->total() }} entri</p>
        <div>
            {{ $reportData->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
