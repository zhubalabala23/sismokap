@extends('layouts.admin')

@section('title', 'Backup Database - SISMOKAP')
@section('page_title', 'Backup Database')

@section('content')
<div class="row g-4">
    <!-- Action Card -->
    <div class="col-12">
        <div class="card p-4 bg-white shadow-sm border-0 rounded-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center">
                    <div class="bg-warning-subtle text-warning p-3 rounded-4 me-3">
                        <i class="bi bi-database-fill-gear fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Cadangkan Database Sistem</h5>
                        <p class="text-muted mb-0 fs-7">Amankan data proyek, progress harian, dan dokumentasi ke berkas SQL local</p>
                    </div>
                </div>

                <form action="{{ route('admin.setting.backup.run') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-warning rounded-3 px-4 py-2.5 fs-7 fw-semibold shadow-sm text-dark">
                        <i class="bi bi-arrow-repeat me-1 animate-spin-hover"></i> Backup Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Backup Logs List -->
    <div class="col-12">
        <div class="card p-4 bg-white shadow-sm border-0 rounded-4">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                <h5 class="fw-bold text-dark mb-0">Riwayat Berkas Cadangan</h5>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr class="text-muted fs-7">
                            <th scope="col" style="width: 60px;" class="text-center">NO</th>
                            <th scope="col">NAMA BERKAS</th>
                            <th scope="col" style="width: 200px;">TANGGAL BACKUP</th>
                            <th scope="col" style="width: 150px;" class="text-end">UKURAN BERKAS</th>
                            <th scope="col" style="width: 100px;" class="text-center">STATUS</th>
                            <th scope="col" style="width: 200px;" class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($backups as $index => $backup)
                            @php
                                $filePath = storage_path('app/backups/' . $backup->filename);
                                $exists = file_exists($filePath);
                                $sizeText = '-';
                                if ($exists) {
                                    $bytes = filesize($filePath);
                                    if ($bytes >= 1048576) {
                                        $sizeText = number_format($bytes / 1048576, 2) . ' MB';
                                    } elseif ($bytes >= 1024) {
                                        $sizeText = number_format($bytes / 1024, 2) . ' KB';
                                    } else {
                                        $sizeText = $bytes . ' B';
                                    }
                                }
                            @endphp
                            <tr>
                                <td class="fs-7 text-center">{{ $backups->firstItem() + $index }}</td>
                                <td class="fs-7 fw-semibold text-primary">
                                    <i class="bi bi-file-earmark-code-fill me-1"></i> {{ $backup->filename }}
                                </td>
                                <td class="fs-7 text-dark">{{ $backup->created_at->format('d M Y - H:i:s') }}</td>
                                <td class="fs-7 text-dark text-end fw-semibold">{{ $sizeText }}</td>
                                <td class="text-center">
                                    @if($exists)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-8">TERSEDIA</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-8">TERHAPUS</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        @if($exists)
                                            <a href="{{ route('admin.setting.backup.download', $backup->id) }}" class="btn btn-sm btn-primary fs-8 px-2 py-1">
                                                <i class="bi bi-cloud-arrow-down-fill"></i> Unduh
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-secondary fs-8 px-2 py-1" disabled>
                                                <i class="bi bi-cloud-arrow-down-fill"></i> Unduh
                                            </button>
                                        @endif
                                        <form action="{{ route('admin.setting.backup.delete', $backup->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berkas backup ini?')" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger fs-8 px-2 py-1">
                                                <i class="bi bi-trash-fill"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4 fs-7">Belum ada riwayat backup database.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $backups->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
