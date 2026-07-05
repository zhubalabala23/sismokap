@extends('layouts.admin')

@section('title', 'Laporan Bulanan - SISMOKAP')
@section('page_title', 'Laporan Progress Bulanan')

@section('content')
<div class="card p-4 bg-white">
    <!-- Filter & Search Form -->
    <div class="border-bottom pb-3 mb-4">
        <h5 class="fw-bold text-dark mb-3">Filter & Cari Laporan Progress Bulanan</h5>
        <form action="{{ route('laporan.bulanan') }}" method="GET" class="row g-3 align-items-end">
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

            <!-- Bulan -->
            <div class="col-6 col-sm-3 col-md-2">
                <label for="bulan" class="form-label fs-8 fw-semibold text-muted mb-1 text-uppercase">Bulan</label>
                <select name="bulan" id="bulan" class="form-select fs-7">
                    @foreach($namaBulan as $num => $nama)
                        <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tahun -->
            <div class="col-6 col-sm-3 col-md-2">
                <label for="tahun" class="form-label fs-8 fw-semibold text-muted mb-1 text-uppercase">Tahun</label>
                <input type="number" name="tahun" id="tahun" min="2020" max="2100" class="form-control fs-7" value="{{ $tahun }}">
            </div>

            <!-- Buttons -->
            <div class="col-12 col-md-5 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 py-2 fs-7">
                    <i class="bi bi-funnel-fill me-1"></i> Saring
                </button>
                @if($proyekId)
                    <a href="{{ route('laporan.bulanan', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-outline-secondary py-2 px-3 fs-7">Reset</a>
                @endif
                <a href="{{ route('laporan.export-bulanan-pdf', ['proyek_id' => $proyekId, 'bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-danger py-2 px-3 fs-7 text-nowrap rounded-3">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak PDF
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr class="text-muted fs-7 text-nowrap">
                    <th scope="col" style="width: 120px;">KODE PROYEK</th>
                    <th scope="col">NAMA PROYEK</th>
                    <th scope="col">LOKASI</th>
                    <th scope="col">KONTRAKTOR</th>
                    <th scope="col" class="text-center" style="width: 130px;">PROGRES HARIAN (BULAN INI)</th>
                    <th scope="col" class="text-center" style="width: 130px;">PROGRES MINGGUAN (BULAN INI)</th>
                    <th scope="col" class="text-center" style="width: 130px;">AKUMULASI PROGRES</th>
                    <th scope="col" class="text-center" style="width: 100px;">TARGET</th>
                    <th scope="col" class="text-center" style="width: 100px;">SELISIH</th>
                    <th scope="col" class="text-center">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $item)
                    <tr class="fs-7">
                        <td class="fw-bold text-primary">{{ $item['kode_proyek'] }}</td>
                        <td class="fw-semibold text-dark">{{ $item['nama_proyek'] }}</td>
                        <td class="text-dark">{{ $item['lokasi'] }}</td>
                        <td class="text-muted">{{ $item['kontraktor'] }}</td>
                        <td class="text-center fw-semibold text-dark bg-light">{{ number_format($item['daily_gain'], 2) }}%</td>
                        <td class="text-center fw-semibold text-dark bg-light">{{ number_format($item['weekly_gain'], 2) }}%</td>
                        <td class="text-center fw-bold text-primary">{{ number_format($item['actual'], 2) }}%</td>
                        <td class="text-center text-dark fw-semibold">{{ number_format($item['target'], 2) }}%</td>
                        <td class="text-center fw-bold {{ $item['selisih'] >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $item['selisih'] >= 0 ? '+' : '' }}{{ number_format($item['selisih'], 2) }}%
                        </td>
                        <td class="text-center">
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
                        <td colspan="10" class="text-center text-muted py-4 fs-7">Tidak ada data progress proyek pada bulan ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
