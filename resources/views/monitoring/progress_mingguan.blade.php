@extends('layouts.admin')

@section('title', 'Progress Mingguan - SISMOKAP')
@section('page_title', 'Monitoring Progress Mingguan')

@section('content')
<div class="row g-4">
    <!-- Form Input (Hanya untuk Admin dan Operator) -->
    @if(auth()->user()->role !== 'pimpinan')
        <div class="col-12 col-lg-4">
            <div class="card p-4 bg-white h-100">
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">Input Progress Mingguan</h5>
                
                <form action="{{ route('progress-mingguan.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="proyek_id" class="form-label fs-7 fw-semibold text-dark">Pilih Proyek <span class="text-danger">*</span></label>
                        <select name="proyek_id" id="proyek_id" class="form-select fs-7" required>
                            <option value="">Pilih Proyek</option>
                            @foreach($proyeks as $proyek)
                                <option value="{{ $proyek->id }}" {{ old('proyek_id') == $proyek->id ? 'selected' : '' }}>
                                    {{ $proyek->nama_proyek }} (Target: {{ $proyek->target_progress }}%)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="minggu_ke" class="form-label fs-7 fw-semibold text-dark">Minggu Ke- <span class="text-danger">*</span></label>
                            <input type="number" min="1" max="53" name="minggu_ke" id="minggu_ke" class="form-control fs-7" value="{{ old('minggu_ke', date('W')) }}" required>
                        </div>
                        <div class="col-6">
                            <label for="tahun" class="form-label fs-7 fw-semibold text-dark">Tahun <span class="text-danger">*</span></label>
                            <input type="number" min="2020" max="2100" name="tahun" id="tahun" class="form-control fs-7" value="{{ old('tahun', date('Y')) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="persentase" class="form-label fs-7 fw-semibold text-dark">Persentase Akumulatif (%) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" max="100" name="persentase" id="persentase" class="form-control fs-7" placeholder="Contoh: 25.00" value="{{ old('persentase') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label fs-7 fw-semibold text-dark">Keterangan / Catatan</label>
                        <textarea name="keterangan" id="keterangan" rows="3" class="form-control fs-7" placeholder="Deskripsikan rekap pekerjaan minggu ini...">{{ old('keterangan') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fs-7 py-2 mt-2">
                        <i class="bi bi-save me-1"></i> Simpan Progress
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- History List -->
    <div class="col-12 {{ auth()->user()->role === 'pimpinan' ? 'col-lg-12' : 'col-lg-8' }}">
        <div class="card p-4 bg-white h-100">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-3 border-bottom pb-2">
                <h5 class="fw-bold text-dark mb-0">Riwayat Progress Mingguan</h5>
                
                <!-- Filter by Project -->
                <form action="{{ route('progress-mingguan.index') }}" method="GET" class="d-flex gap-2 align-items-center m-0">
                    <select name="proyek_id" class="form-select form-select-sm fs-7" style="max-width: 200px;">
                        <option value="">Semua Proyek</option>
                        @foreach($proyeks as $proyek)
                            <option value="{{ $proyek->id }}" {{ $proyekId == $proyek->id ? 'selected' : '' }}>{{ $proyek->nama_proyek }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-light border btn-sm fs-7">Filter</button>
                    @if($proyekId)
                        <a href="{{ route('progress-mingguan.index') }}" class="btn btn-outline-secondary btn-sm fs-7">Reset</a>
                    @endif
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr class="text-muted fs-7">
                            <th scope="col">MINGGU / TAHUN</th>
                            <th scope="col">PROYEK</th>
                            <th scope="col" style="width: 100px;">PROGRESS</th>
                            <th scope="col">KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($progressMingguans as $entry)
                            <tr>
                                <td class="fs-7 fw-semibold text-dark">Minggu ke-{{ $entry->minggu_ke }} ({{ $entry->tahun }})</td>
                                <td class="fs-7 fw-semibold text-primary">{{ $entry->proyek->nama_proyek }}</td>
                                <td class="fs-7 fw-bold text-dark">{{ number_format($entry->persentase, 2) }}%</td>
                                <td class="fs-7 text-muted">{{ $entry->keterangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4 fs-7">Belum ada riwayat progress mingguan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <p class="text-muted fs-7 mb-0">Menampilkan {{ $progressMingguans->firstItem() ?? 0 }} sampai {{ $progressMingguans->lastItem() ?? 0 }} dari {{ $progressMingguans->total() }} entri</p>
                <div>
                    {{ $progressMingguans->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
