@extends('master')

@section('title', 'Daftar Task - Kapal App')

@section('content')
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-tasks mr-1" style="color:#00518d;"></i>Daftar Task
                    </h3>
                    <a href="{{ route('task.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Buat Task
                    </a>
                </div>

                <!-- Filter & Search -->
                <div class="card-body border-bottom">
                    <form method="GET" action="{{ route('task.index') }}" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <input type="text" name="search" class="form-control" placeholder="Cari judul task..."
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
                                <select name="status" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum</option>
                                    <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <a href="{{ route('task.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-sync"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Tabel -->
                <div class="card-body table-responsive p-0">
                    @if($tasks->count() > 0)
                        <table class="table table-hover table-bordered text-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Judul</th>
                                    <th>Deskripsi</th>
                                    <th>Mulai</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                    <th>Crew</th>
                                    <th width="12%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                    <tr>
                                        <td>{{ $loop->iteration + ($tasks->currentPage() - 1) * $tasks->perPage() }}</td>
                                        <td>
                                            <strong>{{ Str::limit($task->judul, 30) }}</strong>
                                        </td>
                                        <td>
                                            {{ Str::limit($task->deskripsi, 40) }}
                                        </td>
                                        <td>
                                            
                                            {{ \Carbon\Carbon::parse($task->tanggal_mulai)->format('d M Y H:i') }}</>
                                        </td>
                                        <td>
                                            @if($task->deadline)
                                                {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y H:i') }}</>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($task->status == 'belum')
                                                <span class="badge bg-secondary">Belum</span>
                                            @elseif($task->status == 'proses')
                                                <span class="badge bg-warning">Proses</span>
                                            @elseif($task->status == 'selesai')
                                                <span class="badge bg-success">Selesai</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($task->allcrew === 'ya')
                                                <span class="badge bg-success">Semua Crew</span>
                                            @else
                                                @php $crewNames = $task->detailTasks->pluck('user.name')->take(3)->implode(', '); @endphp
                                                @if($task->detailTasks->count() > 3)
                                                    {{ $crewNames }}, <em>+{{ $task->detailTasks->count() - 3 }} lainnya</em>
                                                @else
                                                    {{ $crewNames ?: '<em class="text-muted">Tidak ada</em>' }}
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                
                                                <a href="{{ route('task.edit', $task) }}" class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $task->id }}" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Hapus -->
                                    <div class="modal fade" id="deleteModal{{ $task->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-exclamation-triangle"></i> Konfirmasi
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <p class="mb-0">Hapus task <strong>{{ $task->judul }}</strong>?</p>
                                                    <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
                                                </div>
                                                <div class="modal-footer justify-content-center">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Batal
                                                    </button>
                                                    <form action="{{ route('task.destroy', $task) }}" method="POST" class="d-inline">
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
                            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada task.</p>
                            <a href="{{ route('task.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Buat Task Pertama
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Paginasi -->
                @if($tasks->hasPages())
                    <div class="d-flex justify-content-end mt-3">
    {{ $tasks->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>
                    
                @endif
            </div>
        </div>
    </div>
</div>
@endsection