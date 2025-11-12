@extends('master')

@section('title', 'Daftar Laporan Hazard - Kapal App')

@section('content')
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle mr-1" style="color:#e74c3c;"></i>
                        Daftar Laporan Hazard
                    </h3>
                    <a href="{{ route('hazard.create') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-plus"></i> Lapor Hazard
                    </a>
                </div>

                <!-- Filter & Search -->
                <div class="card-body border-bottom">
                    <form method="GET" action="{{ route('hazard.index') }}" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Cari judul..."
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
                            </div>
                            <div class="col-md-5 d-flex gap-2">
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <a href="{{ route('hazard.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-sync"></i> Reset
                                </a>
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
                                    <th width="5%">#</th>
                                    <th>Tanggal</th>
                                    <th>Pelapor</th>
                                    <th>Judul</th>
                                    <th>Dibuat</th>
                                    <th width="12%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hazards as $hazard)
                                    <tr>
                                        <td>{{ $loop->iteration + ($hazards->currentPage() - 1) * $hazards->perPage() }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($hazard->tanggal_laporan)->format('d/m/Y H:i') }}
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $hazard->user->name }}</span>
                                        </td>
                                        <td>
                                            {{ $hazard->judul_laporan ?: '<em class="text-muted">Tanpa judul</em>' }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($hazard->created_at)->format('d/m/Y H:i') }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if($hazard->user_id === Auth::id())
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
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
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
                            <p class="text-muted">Belum ada laporan hazard.</p>
                            <a href="{{ route('hazard.create') }}" class="btn btn-danger">
                                <i class="fas fa-plus"></i> Lapor Sekarang
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Paginasi -->
                @if($hazards->hasPages())
                     <div class="d-flex justify-content-end mt-3">
                        {{ $hazards->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection