@extends('layouts.admin')

@section('title', 'Data User - SISMOKAP')
@section('page_title', 'Master Data User')

@section('content')
<div class="card p-4 bg-white">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <!-- Search -->
        <form action="{{ route('admin.user.index') }}" method="GET" class="d-flex gap-2 align-items-center m-0">
            <div class="input-group" style="max-width: 250px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 fs-7" placeholder="Cari user..." value="{{ $search }}">
            </div>
            <button type="submit" class="btn btn-light border btn-sm px-3 fs-7 py-2">Cari</button>
            @if($search)
                <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary btn-sm px-3 fs-7 py-2">Reset</a>
            @endif
        </form>

        <!-- Tambah User Button -->
        <a href="{{ route('admin.user.create') }}" class="btn btn-primary rounded-3 fs-7 py-2 px-3">
            <i class="bi bi-plus-lg me-1"></i> Tambah User
        </a>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr class="text-muted fs-7">
                    <th scope="col" style="width: 80px;">NO</th>
                    <th scope="col">NAMA</th>
                    <th scope="col">EMAIL</th>
                    <th scope="col">ROLE</th>
                    <th scope="col" class="text-center" style="width: 180px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $userItem)
                    <tr>
                        <td class="fs-7">{{ $users->firstItem() + $index }}</td>
                        <td class="fs-7 fw-semibold text-dark">{{ $userItem->name }}</td>
                        <td class="fs-7 text-dark">{{ $userItem->email }}</td>
                        <td>
                            @if($userItem->role === 'admin')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-8">ADMIN</span>
                            @elseif($userItem->role === 'operator')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-8">OPERATOR</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 fs-8">PIMPINAN</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.user.edit', $userItem->id) }}" class="btn btn-sm btn-light border text-primary" title="Edit">
                                    <i class="bi bi-pencil-fill"></i> Edit
                                </a>

                                @if($userItem->id === auth()->id())
                                    <button class="btn btn-sm btn-light border text-muted" disabled title="Anda tidak bisa menghapus akun sendiri">
                                        <i class="bi bi-slash-circle"></i> Self
                                    </button>
                                @else
                                    <form action="{{ route('admin.user.destroy', $userItem->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus">
                                            <i class="bi bi-trash-fill"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4 fs-7">Tidak ada data user ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <p class="text-muted fs-7 mb-0">Menampilkan {{ $users->firstItem() ?? 0 }} sampai {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} user</p>
        <div>
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
