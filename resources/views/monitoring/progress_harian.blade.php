@extends('layouts.admin')

@section('title', 'Progress Harian - SISMOKAP')
@section('page_title', 'Monitoring Progress Harian')

@section('content')
<div class="row g-4">
    <!-- Form Input (Hanya untuk Admin dan Operator) -->
    @if(auth()->user()->role !== 'pimpinan')
        <div class="col-12 col-lg-4">
            <div class="card p-4 bg-white h-100">
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">Input Progress Harian</h5>
                
                <form action="{{ route('progress-harian.store') }}" method="POST">
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

                    <div class="mb-3">
                        <label for="tanggal_pelaksanaan" class="form-label fs-7 fw-semibold text-dark">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_pelaksanaan" id="tanggal_pelaksanaan" class="form-control fs-7" value="{{ old('tanggal_pelaksanaan', date('Y-m-d')) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="uraian_pekerjaan" class="form-label fs-7 fw-semibold text-dark">Uraian Pekerjaan <span class="text-danger">*</span></label>
                        <input type="text" name="uraian_pekerjaan" id="uraian_pekerjaan" class="form-control fs-7" placeholder="Contoh: Pekerjaan Galian Tanah" value="{{ old('uraian_pekerjaan') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="volume_pekerjaan" class="form-label fs-7 fw-semibold text-dark">Volume Pekerjaan <span class="text-danger">*</span></label>
                        <input type="text" name="volume_pekerjaan" id="volume_pekerjaan" class="form-control fs-7" placeholder="Contoh: 150 m3 atau 20 meter" value="{{ old('volume_pekerjaan') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="persentase" class="form-label fs-7 fw-semibold text-dark">Bobot Pekerjaan (Akumulatif %) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" max="100" name="persentase" id="persentase" class="form-control fs-7" placeholder="Contoh: 12.50" value="{{ old('persentase') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="progres_harian" class="form-label fs-7 fw-semibold text-dark">Persentase Progres Harian (%) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" max="100" name="progres_harian" id="progres_harian" class="form-control fs-7" placeholder="Contoh: 1.25" value="{{ old('progres_harian') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="kendala" class="form-label fs-7 fw-semibold text-dark">Kendala Lapangan</label>
                        <textarea name="kendala" id="kendala" rows="2" class="form-control fs-7" placeholder="Deskripsikan kendala jika ada...">{{ old('kendala') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="solusi" class="form-label fs-7 fw-semibold text-dark">Solusi Yang Dilakukan</label>
                        <textarea name="solusi" id="solusi" rows="2" class="form-control fs-7" placeholder="Deskripsikan solusi yang dilakukan...">{{ old('solusi') }}</textarea>
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
                <h5 class="fw-bold text-dark mb-0">Riwayat Progress Harian</h5>
                
                <!-- Filter by Project -->
                <form action="{{ route('progress-harian.index') }}" method="GET" class="d-flex gap-2 align-items-center m-0">
                    <select name="proyek_id" class="form-select form-select-sm fs-7" style="max-width: 200px;">
                        <option value="">Semua Proyek</option>
                        @foreach($proyeks as $proyek)
                            <option value="{{ $proyek->id }}" {{ $proyekId == $proyek->id ? 'selected' : '' }}>{{ $proyek->nama_proyek }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-light border btn-sm fs-7">Filter</button>
                    @if($proyekId)
                        <a href="{{ route('progress-harian.index') }}" class="btn btn-outline-secondary btn-sm fs-7">Reset</a>
                    @endif
                </form>
            </div>

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
                        @forelse($progressHarians as $entry)
                            <tr class="text-nowrap fs-7">
                                <td class="fw-semibold text-dark">{{ $entry->tanggal_pelaksanaan->format('d M Y') }}</td>
                                <td class="fw-semibold text-primary">{{ $entry->proyek?->nama_proyek ?? '-' }}</td>
                                <td class="text-dark">{{ $entry->uraian_pekerjaan ?? '-' }}</td>
                                <td class="text-dark">{{ $entry->volume_pekerjaan ?? '-' }}</td>
                                <td class="fw-bold text-dark">{{ number_format($entry->persentase, 2) }}%</td>
                                <td class="fw-bold text-success">{{ number_format($entry->progres_harian, 2) }}%</td>
                                <td class="text-muted text-wrap" style="max-width: 150px;">{{ $entry->kendala ?? '-' }}</td>
                                <td class="text-muted text-wrap" style="max-width: 150px;">{{ $entry->solusi ?? '-' }}</td>
                                <td class="text-dark">{{ $entry->user->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4 fs-7">Belum ada riwayat progress harian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <p class="text-muted fs-7 mb-0">Menampilkan {{ $progressHarians->firstItem() ?? 0 }} sampai {{ $progressHarians->lastItem() ?? 0 }} dari {{ $progressHarians->total() }} entri</p>
                <div>
                    {{ $progressHarians->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
