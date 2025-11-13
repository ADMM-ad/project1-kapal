@extends('master')

@section('title', 'Task Saya')

@section('content')
<style>
     @media (min-width: 992px) {
        .col-lg-1-5 {
            flex: 0 0 12.5%;
            max-width: 12.5%;
        }
    }
</style>
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
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-tasks mr-1" style="color: #0074CC;"></i>
                        Task Saya
                    </h3>
                </div>

                <!-- Filter & Search -->
                <div class="card-body border-bottom">
                    <form method="GET" action="{{ route('task.my') }}" id="filterForm">
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-lg-3">
                <input type="text" id="daterange" name="daterange" class="form-control"
                       placeholder="Pilih rentang tanggal" value="{{ request('daterange') }}">
            </div>
                            <div class="col-12 col-lg-3">
                                <input type="text" name="search" class="form-control" placeholder="Cari judul task..."
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-12 col-lg-3">
                                <select name="status" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum</option>
                                    <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                            <div class="col-6 col-lg-1-5">
                                <button type="submit" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                            <div class="col-6 col-lg-1-5">
                                <a href="{{ route('task.my') }}" class="btn btn-outline-secondary w-100">
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
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Deskripsi</th>
                                    <th>Mulai</th>
                                    <th>Batas Waktu</th>
                                    <th>Tanggal Dikerjakan</th>
                                    <th>Tanggal Selesai</th>
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
                                            {{ \Carbon\Carbon::parse($task->tanggal_mulai)->format('d M Y H:i') }}
                                        </td>
                                        <td>
                                            @if($task->deadline)
                                                {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y H:i') }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($task->tanggal_dikerjakan)
                                                {{ \Carbon\Carbon::parse($task->tanggal_dikerjakan)->format('d M Y H:i') }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($task->tanggal_selesai)
                                                {{ \Carbon\Carbon::parse($task->tanggal_selesai)->format('d M Y H:i') }}
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
                                                <a>Semua Crew</a>
                                            @else
                                                @php 
                                                    $crewNames = $task->detailTasks->pluck('user.name')->take(3)->implode(', ');
                                                @endphp
                                                @if($task->detailTasks->count() > 3)
                                                    {{ $crewNames }}, <em>+{{ $task->detailTasks->count() - 3 }} lainnya</em>
                                                @else
                                                    {{ $crewNames ?: '<em class="text-muted">Hanya saya</em>' }}
                                                @endif
                                            @endif
                                        </td>
                                        <td>
    <div>
        @if($task->status === 'belum')
            <!-- MULAI -->
            <button type="button" class="btn btn-success btn-sm" title="Mulai Task"
                    data-bs-toggle="modal" data-bs-target="#startModal{{ $task->id }}">
                <i class="fas fa-play"></i>
            </button>

        @elseif($task->status === 'proses')
            <!-- BATAL -->
            <button type="button" class="btn btn-warning btn-sm" title="Batalkan"
                    data-bs-toggle="modal" data-bs-target="#cancelModal{{ $task->id }}">
                <i class="fas fa-undo"></i>
            </button>

            <!-- SELESAI -->
            <button type="button" class="btn btn-primary btn-sm" title="Selesaikan"
                    data-bs-toggle="modal" data-bs-target="#finishModal{{ $task->id }}">
                <i class="fas fa-check"></i>
            </button>

        @else
            <span class="badge">-</span>
        @endif
    </div>
</td>
                                    </tr>

                                    <!-- Modal MULAI -->
<div class="modal fade" id="startModal{{ $task->id }}" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form action="{{ route('task.start', $task) }}" method="POST">
            @csrf @method('PATCH')
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-play"></i> Mulai Task?
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-0"><strong>{{ $task->judul }}</strong></p>
                    <small class="text-muted">Status akan berubah menjadi <strong>Proses</strong></small>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Ya, Mulai</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal BATAL -->
<div class="modal fade" id="cancelModal{{ $task->id }}" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form action="{{ route('task.cancel', $task) }}" method="POST">
            @csrf @method('PATCH')
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="fas fa-undo"></i> Batalkan Task?
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-0"><strong>{{ $task->judul }}</strong></p>
                    <small class="text-muted">Status akan kembali ke <strong>Belum</strong></small>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-warning">Ya, Batalkan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal SELESAI -->
<div class="modal fade" id="finishModal{{ $task->id }}" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form action="{{ route('task.finish', $task) }}" method="POST">
            @csrf @method('PATCH')
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-check"></i> Selesaikan Task?
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-0"><strong>{{ $task->judul }}</strong></p>
                    <small class="text-muted">Status akan menjadi <strong>Selesai</strong></small>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ya, Selesai</button>
                </div>
            </div>
        </form>
    </div>
</div>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada task untuk Anda.</p>
                        </div>
                    @endif
                </div>

                <!-- Paginasi -->
                @if($tasks->hasPages())
                    <div class="card-footer d-flex justify-content-end">
                        {{ $tasks->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection