@extends('master')

@section('title', 'Dashboard Crew')

@section('content')
<div class="container mt-2">
<div class="row">

    <!-- CARD TASK -->
    <div class="col-12 col-md-6">
        <div class="card card-bordered-blue" style="height: 400px; display: flex; flex-direction: column;">
            <div class="card-header border-0">
                <h3 class="card-title">
                    <i class="fas fa-tasks" style="color: #0074CC;"></i>
                    Riwayat Task 7 Hari Terakhir
                </h3>
              
            </div>

            <div class="card-body p-0 flex-grow-1 overflow-auto">
                <div class="table-responsive" style="max-height: 100%;">
                    @if($tasks->isNotEmpty())
                        <table class="table table-sm table-hover table-bordered text-nowrap">
                            <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Tugas</th>
                                    <th>Judul Tugas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $index => $task)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ \Carbon\Carbon::parse($task->tanggal_mulai)->format('d M H:i') }}</td>
                                        <td>{{ Str::limit($task->judul, 25) }}</td>
                                        <td>
                                            @if($task->status == 'belum')
                                                <span class="badge bg-secondary">Belum</span>
                                            @elseif($task->status == 'proses')
                                                <span class="badge bg-warning">Proses</span>
                                            @else
                                                <span class="badge bg-success">Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p class="mb-0">Tidak ada task</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-footer text-end">
                <a href="{{ route('task.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua Task
                </a>
            </div>
        </div>
    </div>

    <!-- CARD HAZARD -->
    <div class="col-12 col-md-6">
        <div class="card card-bordered-blue" style="height: 400px; display: flex; flex-direction: column;">
            <div class="card-header border-0">
                <h3 class="card-title">
                    <i class="fas fa-exclamation-triangle" style="color: #0074CC;"></i>
                    Riwayat Hazard 7 Hari Tarakhir
                </h3>
              
            </div>

            <div class="card-body p-0 flex-grow-1 overflow-auto">
                <div class="table-responsive" style="max-height: 100%;">
                    @if($tasks->isNotEmpty())
                        <table class="table table-sm table-hover table-bordered text-nowrap">
                            <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Laporan</th>
                                    <th>Nama Pelapor</th>
                                    <th>Judul Laporan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hazards as $index => $hazard)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ \Carbon\Carbon::parse($hazard->tanggal_laporan)->format('d M H:i') }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $hazard->user->name }}</span>
                                        </td>
                                        <td>{{ $hazard->judul_laporan ?: '<em class="text-muted">Tanpa judul</em>' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-shield-alt fa-2x mb-2"></i>
                            <p class="mb-0">Tidak ada riwayat hazard</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-footer text-end">
                <a href="{{ route('hazard.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua Hazard
                </a>
            </div>
        </div>
    </div>

</div>
</div>
@endsection