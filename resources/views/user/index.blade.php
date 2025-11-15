@extends('master')

@section('title', 'User Management')

@section('content')
<div class="container mt-2">
    @if(session('success'))
<div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-end align-items-center">
                <h3 class="card-title mb-0 mr-auto">
                    <i class="fas fa-users mr-1" style="color: #0074CC;"></i>User Management
                </h3>
                <a href="{{ route('user.create') }}" class="btn-sm" style="background-color: #0074CC; color: #ffffff;" >
                    <i class="fas fa-plus-circle mr-2" style="color: #ffffff;"></i>Tambah
                </a>
            </div>

                <!-- Filter & Search -->
                <div class="card-body border-bottom">
    <form method="GET" action="{{ route('user.index') }}" id="filterForm">
        <div class="row g-2 align-items-center">
            <!-- Input Cari -->
            <div class="col-lg-4 col-md-4 col-sm-12">
                <input type="text" name="search" class="form-control" placeholder="Cari nama..."
                       value="{{ request('search') }}">
            </div>

            <!-- Dropdown Role (disamakan tinggi dengan input) -->
            <div class="col-lg-4 col-md-4 col-sm-12">
                <select name="role" class="form-select form-select-sm" style="height: 38px;">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="crew" {{ request('role') == 'crew' ? 'selected' : '' }}>Crew</option>
                </select>
            </div>

            <!-- Tombol Cari -->
            <div class="col-lg-2 col-md-2 col-sm-6 d-grid">
                <button type="submit" class="btn btn-outline-primary" style="height: 38px;">
                    <i class="fas fa-search"></i> Cari
                </button>
            </div>

            <!-- Tombol Reset -->
            <div class="col-lg-2 col-md-2 col-sm-6 d-grid">
                <a href="{{ route('user.index') }}" class="btn btn-outline-secondary" style="height: 38px;">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </div>
        </div>
    </form>
</div>


                <!-- Tabel -->
                <div class="card-body table-responsive p-0">
                    @if($users->count() > 0)
                        <table class="table table-hover table-bordered text-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>
                                        Nama
                                        <a href="{{ route('user.index', array_merge(request()->query(), ['sort' => $sort == 'asc' ? 'desc' : 'asc'])) }}"
                                           class="text-dark ml-1">
                                            <i class="fas fa-sort{{ $sort == 'asc' ? '-up' : '-down' }}"></i>
                                        </a>
                                    </th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Dibuat</th>
                                    <th width="5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>
                                            <span class="badge bg-{{ $user->role == 'admin' ? 'warning' : 'info' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <button type="button"
                                                    class="btn btn-danger btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $user->id }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Hapus -->
                                    <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-exclamation-triangle"></i> Konfirmasi
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <p class="mb-0">Hapus user <strong>{{ $user->name }}</strong>?</p>
                                                    <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
                                                </div>
                                                <div class="modal-footer justify-content-center">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Batal
                                                    </button>
                                                    <form action="{{ route('user.destroy', $user) }}" method="POST" class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Tidak ada user ditemukan.</p>
                            <a href="{{ route('user.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah User
                            </a>
                        </div>
                    @endif
                </div>

  
 
<div class="d-flex justify-content-end mt-3">
    {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>
            </div>
        </div>
    </div>
</div>
@endsection