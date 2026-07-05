@extends('layouts.admin')

@section('title', 'Data Proyek - SISMOKAP')
@section('page_title', 'Data Proyek Konstruksi')

@section('content')
    <!-- Row: Filter & Table -->
    <div class="card p-4 bg-white">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <!-- Search and Filter Form -->
            <form action="{{ route('admin.proyek.index') }}" method="GET"
                class="d-flex flex-wrap gap-2 align-items-center m-0">
                <!-- Search Box -->
                <div class="input-group" style="max-width: 250px;">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 fs-7"
                        placeholder="Cari kode, nama, jenis, satuan..." value="{{ $search }}">
                </div>

                <!-- Status Dropdown -->
                <select name="status" class="form-select fs-7" style="min-width: 180px;" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="perencanaan" {{ $status === 'perencanaan' ? 'selected' : '' }}>Perencanaan</option>
                    <option value="berjalan" {{ $status === 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                    <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="terlambat" {{ $status === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                </select>

                @if($search || $status)
                    <a href="{{ route('admin.proyek.index') }}"
                        class="btn btn-outline-secondary btn-sm px-3 fs-7 py-2">Reset</a>
                @endif
            </form>

            <!-- Tambah Proyek Button -->
            <a href="{{ route('admin.proyek.create') }}" class="btn btn-primary rounded-3 fs-7 py-2 px-3">
                <i class="bi bi-plus-lg me-1"></i> Tambah Proyek
            </a>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle" style="min-width: 1300px;">
                <thead class="table-light">
                    <tr class="text-muted fs-7 text-nowrap">
                        <th scope="col">KODE PROYEK</th>
                        <th scope="col">NAMA PROYEK</th>
                        <th scope="col">JENIS PEKERJAAN</th>
                        <th scope="col">LOKASI</th>
                        <th scope="col">SATUAN PELAKSANA</th>
                        <th scope="col">NILAI KONTRAK</th>
                        <th scope="col">PERIODE</th>
                        <th scope="col" class="text-center">TARGET PROGRESS</th>
                        <th scope="col">STATUS PROYEK</th>
                        <th scope="col">KETERANGAN</th>
                        <th scope="col" class="text-center" style="width: 100px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proyeks as $proyek)
                        <tr class="text-nowrap">
                            <td class="fs-7 fw-bold text-primary">{{ $proyek->kode_proyek }}</td>
                            <td>
                                <div class="fw-semibold text-dark fs-7">{{ $proyek->nama_proyek }}</div>
                            </td>
                            <td class="fs-7 text-dark">{{ $proyek->jenis_pekerjaan ?? '-' }}</td>
                            <td class="fs-7 text-dark">{{ $proyek->lokasi?->nama_lokasi ?? '-' }}</td>
                            <td class="fs-7 text-muted">{{ $proyek->kontraktor?->nama_kontraktor ?? '-' }}</td>
                            <td class="fs-7 text-dark fw-semibold">
                                {{ $proyek->nilai_kontrak ? 'Rp ' . number_format($proyek->nilai_kontrak, 0, ',', '.') : '-' }}
                            </td>
                            <td class="fs-7 text-dark">
                                {{ $proyek->tanggal_mulai->format('d M Y') }} s/d
                                {{ $proyek->tanggal_selesai->format('d M Y') }}
                            </td>
                            <td class="fs-7 text-dark text-center fw-semibold">
                                {{ number_format($proyek->target_progress, 2) }}%
                            </td>
                            <td>
                                @if($proyek->status === 'perencanaan')
                                    <span
                                        class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 fs-8">PERENCANAAN</span>
                                @elseif($proyek->status === 'berjalan')
                                    <span
                                        class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-8">BERJALAN</span>
                                @elseif($proyek->status === 'selesai')
                                    <span
                                        class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-8">SELESAI</span>
                                @else
                                    <span
                                        class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-8">TERLAMBAT</span>
                                @endif
                            </td>
                            <td class="fs-7 text-muted text-wrap" style="min-width: 250px; max-width: 400px;">
                                {{ $proyek->keterangan ?? '-' }}
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('admin.proyek.edit', $proyek->id) }}"
                                        class="btn btn-sm btn-light border text-primary" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('admin.proyek.destroy', $proyek->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini?')"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4 fs-7">Tidak ada data proyek ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <p class="text-muted fs-7 mb-0">Menampilkan {{ $proyeks->firstItem() ?? 0 }} sampai
                {{ $proyeks->lastItem() ?? 0 }} dari {{ $proyeks->total() }} proyek</p>
            <div>
                {{ $proyeks->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection