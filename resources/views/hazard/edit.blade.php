@extends('master')

@section('title', 'Edit Laporan Hazard - Kapal App')

@section('content')
<div class="container-fluid mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-1"></i>
                        Edit Laporan Hazard
                    </h3>
                </div>

                <form action="{{ route('hazard.update', $hazard) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <!-- Notifikasi -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                                <button type="button" class="close" onclick="this.parentElement.remove();">×</button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-times-circle mr-2"></i>{{ session('error') }}
                                <button type="button" class="close" onclick="this.parentElement.remove();">×</button>
                            </div>
                        @endif

                        <!-- Tanggal Laporan -->
                        <div class="form-group">
                            <label>Tanggal Laporan <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="tanggal_laporan" 
                                   class="form-control @error('tanggal_laporan') is-invalid @enderror"
                                   value="{{ old('tanggal_laporan', \Carbon\Carbon::parse($hazard->tanggal_laporan)->format('Y-m-d\TH:i')) }}" 
                                   required>
                            @error('tanggal_laporan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Judul Laporan -->
                        <div class="form-group">
                            <label>Judul Laporan</label>
                            <input type="text" name="judul_laporan" 
                                   class="form-control @error('judul_laporan') is-invalid @enderror"
                                   value="{{ old('judul_laporan', $hazard->judul_laporan) }}"
                                   placeholder="Contoh: Kabel terbuka di dek">
                            @error('judul_laporan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Deskripsi Laporan -->
                        <div class="form-group">
                            <label>Deskripsi Laporan</label>
                            <textarea name="deskripsi_laporan" class="form-control" rows="5"
                                      placeholder="Jelaskan detail hazard yang ditemukan...">{{ old('deskripsi_laporan', $hazard->deskripsi_laporan) }}</textarea>
                            @error('deskripsi_laporan') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Update Laporan
                        </button>
                        <a href="{{ route('hazard.create') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection