@extends('master')

@section('title', 'Lapor Hazard - Kapal App')

@section('content')
<div class="container-fluid mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle mr-1" style="color:#e74c3c;"></i>
                        Lapor Hazard
                    </h3>
                </div>

                <form action="{{ route('hazard.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <!-- Notifikasi -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                                <button type="button" class="close" onclick="this.parentElement.remove();">×</button>
                            </div>
                        @endif

                        <!-- User (otomatis, tidak ditampilkan) -->
                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">

                        <!-- Tanggal Laporan -->
                        <div class="form-group">
                            <label>Tanggal Laporan <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="tanggal_laporan" class="form-control @error('tanggal_laporan') is-invalid @enderror"
                                   value="{{ old('tanggal_laporan', \Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}" required>
                            @error('tanggal_laporan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Judul Laporan -->
                        <div class="form-group">
                            <label>Judul Laporan</label>
                            <input type="text" name="judul_laporan" class="form-control @error('judul_laporan') is-invalid @enderror"
                                   value="{{ old('judul_laporan') }}" placeholder="Contoh: Kabel terbuka di dek">
                            @error('judul_laporan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Deskripsi Laporan -->
                        <div class="form-group">
                            <label>Deskripsi Laporan</label>
                            <textarea name="deskripsi_laporan" class="form-control" rows="5"
                                      placeholder="Jelaskan detail hazard yang ditemukan...">{{ old('deskripsi_laporan') }}</textarea>
                            @error('deskripsi_laporan') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-paper-plane"></i> Kirim Laporan
                        </button>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection