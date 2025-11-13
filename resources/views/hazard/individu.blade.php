@extends('master')

@section('title', 'Laporan Hazard Saya')

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
                    <i class="fas fa-exclamation-triangle mr-1" style="color: #0074CC;"></i>Laporan Hazard Saya
                </h3>
                <a href="{{ route('hazard.create') }}" class="btn-sm" style="background-color: #0074CC; color: #ffffff;" >
                    <i class="fas fa-plus-circle mr-2" style="color: #ffffff;"></i>Tambah
                </a>
            </div>

                <!-- Filter & Search -->
<div class="card-body border-bottom">
    <form method="GET" action="{{ route('hazard.my') }}" id="filterForm">
        <div class="row g-3">
            <!-- Cari Judul: Laptop 4, HP 12 -->
            <div class="col-12 col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari judul..."
                       value="{{ request('search') }}">
            </div>

            <!-- Tanggal: Laptop 4, HP 12 -->
            <div class="col-12 col-md-4">
                <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
            </div>

            <!-- Tombol Cari: Laptop 2, HP 6 -->
            <div class="col-6 col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="fas fa-search"></i> Cari
                </button>
            </div>

            <!-- Tombol Reset: Laptop 2, HP 6 -->
            <div class="col-6 col-md-2">
                <button type="button" class="btn btn-outline-secondary w-100" onclick="window.location.href='{{ route('hazard.my') }}'">
                    <i class="fas fa-sync"></i> Reset
                </button>
            </div>
        </div>
    </form>
</div>
                <!-- Tabel -->
                <div class="card-body table-responsive p-0">
                    @if($hazards->count() > 0)
                        <table class="table table-hover table-bordered text-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Pelaporan</th>
                                    <th>Judul Pelaporan</th>
                                    <th>Deskripsi Pelaporan</th>
                                    <th width="12%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hazards as $hazard)
                                    <tr>
                                        <td>{{ $loop->iteration + ($hazards->currentPage() - 1) * $hazards->perPage() }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($hazard->tanggal_laporan)->format('d M Y H:i') }}
                                        </td>
                                        <td>
                                            {{ $hazard->judul_laporan ?: 'Tanpa judul' }}
                                        </td>
                                       <td>
                                            {{ $hazard->deskripsi_laporan ?: 'Tanpa Deskripsi' }}
                                        </td>
                                        <td>
                                            <div>
                                                <!-- Selalu tampil karena pasti milik sendiri -->
                                                <a href="{{ route('hazard.edit', $hazard) }}"
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $hazard->id }}"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Hapus -->
                                    <div class="modal fade" id="deleteModal{{ $hazard->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-exclamation-triangle"></i> Konfirmasi
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <p class="mb-0">Hapus laporan ini?</p>
                                                    <small class="text-muted">Tindakan tidak dapat dibatalkan.</small>
                                                </div>
                                                <div class="modal-footer justify-content-center">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Batal
                                                    </button>
                                                    <form action="{{ route('hazard.destroy', $hazard) }}" method="POST" class="d-inline">
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
                            <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada laporan hazard dari Anda.</p>
                            <a href="{{ route('hazard.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Lapor Sekarang
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Paginasi -->
                @if($hazards->hasPages())
                    <div class="card-footer d-flex justify-content-end">
                        {{ $hazards->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection