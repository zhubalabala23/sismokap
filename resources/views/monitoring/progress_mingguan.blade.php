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

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="progress_sebelumnya" class="form-label fs-7 fw-semibold text-dark">Progress Sebelumnya (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="100" name="progress_sebelumnya" id="progress_sebelumnya" class="form-control fs-7" placeholder="Contoh: 15.00" value="{{ old('progress_sebelumnya') }}" required>
                        </div>
                        <div class="col-6">
                            <label for="progress_berjalan" class="form-label fs-7 fw-semibold text-dark">Progress Berjalan (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="100" name="progress_berjalan" id="progress_berjalan" class="form-control fs-7" placeholder="Contoh: 5.00" value="{{ old('progress_berjalan') }}" required>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="persentase" class="form-label fs-7 fw-semibold text-dark">Progress Kumulatif (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="100" name="persentase" id="persentase" class="form-control fs-7 bg-light" placeholder="0.00" value="{{ old('persentase') }}" readonly required>
                        </div>
                        <div class="col-6">
                            <label for="target_mingguan" class="form-label fs-7 fw-semibold text-dark">Target Mingguan (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="100" name="target_mingguan" id="target_mingguan" class="form-control fs-7" placeholder="Contoh: 20.00" value="{{ old('target_mingguan') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="selisih_capaian" class="form-label fs-7 fw-semibold text-dark">Selisih Capaian (%) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="-100" max="100" name="selisih_capaian" id="selisih_capaian" class="form-control fs-7 bg-light" placeholder="0.00" value="{{ old('selisih_capaian') }}" readonly required>
                    </div>

                    <div class="mb-3">
                        <label for="kendala" class="form-label fs-7 fw-semibold text-dark">Kendala</label>
                        <textarea name="kendala" id="kendala" rows="2" class="form-control fs-7" placeholder="Deskripsikan kendala jika ada...">{{ old('kendala') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="rencana_berikutnya" class="form-label fs-7 fw-semibold text-dark">Rencana Minggu Berikutnya</label>
                        <textarea name="rencana_berikutnya" id="rencana_berikutnya" rows="2" class="form-control fs-7" placeholder="Rencana pekerjaan untuk minggu depan...">{{ old('rencana_berikutnya') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label fs-7 fw-semibold text-dark">Keterangan / Catatan Tambahan</label>
                        <textarea name="keterangan" id="keterangan" rows="2" class="form-control fs-7" placeholder="Keterangan tambahan...">{{ old('keterangan') }}</textarea>
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
                        @forelse($progressMingguans as $entry)
                            <tr class="text-nowrap fs-7">
                                <td class="fw-semibold text-dark">Minggu ke-{{ $entry->minggu_ke }} ({{ $entry->tahun }})</td>
                                <td class="fw-semibold text-primary">{{ $entry->proyek?->nama_proyek ?? '-' }}</td>
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
                                <td colspan="10" class="text-center text-muted py-4 fs-7">Belum ada riwayat progress mingguan.</td>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputSebelumnya = document.getElementById('progress_sebelumnya');
    const inputBerjalan = document.getElementById('progress_berjalan');
    const inputKumulatif = document.getElementById('persentase');
    const inputTarget = document.getElementById('target_mingguan');
    const inputSelisih = document.getElementById('selisih_capaian');

    function calculateValues() {
        const sebelumnya = parseFloat(inputSebelumnya.value) || 0;
        const berjalan = parseFloat(inputBerjalan.value) || 0;
        
        // Progress Kumulatif = Sebelumnya + Berjalan
        const kumulatif = sebelumnya + berjalan;
        inputKumulatif.value = kumulatif.toFixed(2);

        const target = parseFloat(inputTarget.value) || 0;
        
        // Selisih Capaian = Kumulatif - Target
        const selisih = kumulatif - target;
        inputSelisih.value = selisih.toFixed(2);
    }

    if (inputSebelumnya && inputBerjalan && inputTarget) {
        inputSebelumnya.addEventListener('input', calculateValues);
        inputBerjalan.addEventListener('input', calculateValues);
        inputTarget.addEventListener('input', calculateValues);
    }
});
</script>
@endsection
