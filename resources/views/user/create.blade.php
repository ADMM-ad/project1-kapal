@extends('master')

@section('title', 'Tambah User')

@section('content')
<div class="container mt-2">
        <div class="card card-primary">
            <div class="card-header" style="background-color: #0074CC;">
                <h3 class="card-title"><i class="fas fa-user-plus mr-2"></i>Form Tambah User Baru</h3>
            </div>

            <form action="{{ route('user.store') }}" method="POST">
                @csrf

                <div class="card-body">
                   <!-- Notifikasi Sukses -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        <button type="button" class="close" onclick="this.parentElement.remove();">
            ×
        </button>
    </div>
@endif

<!-- Notifikasi Error -->
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-times-circle mr-2"></i>{{ session('error') }}
        <button type="button" class="close" onclick="this.parentElement.remove();">
            ×
        </button>
    </div>
@endif
                    <!-- Nama -->
                    <div class="form-group">
                        <label for="name">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="Masukkan nama" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div class="form-group">
                        <label for="username">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror"
                               value="{{ old('username') }}" placeholder="Masukkan username" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                   <!-- Password -->
<div class="form-group">
    <label>Password <span class="text-danger">*</span></label>
    <div class="input-group">
        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
               placeholder="Minimal 8 karakter" required>
        <div class="input-group-append">
            <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                <i class="fas fa-eye"></i>
            </button>
        </div>
        @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
</div>

<!-- Konfirmasi Password -->
<div class="form-group">
    <label>Konfirmasi Password <span class="text-danger">*</span></label>
    <div class="input-group">
        <input type="password" name="password_confirmation" id="password_confirmation"
               class="form-control" placeholder="Ulangi password" required>
        <div class="input-group-append">
            <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">
                <i class="fas fa-eye"></i>
            </button>
        </div>
    </div>
</div>
                    <!-- Role (Otomatis Choices.js via master.blade.php) -->
                    <div class="form-group">
                        <label for="role">Role <span class="text-danger">*</span></label>
                        <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="crew" {{ old('role') == 'crew' ? 'selected' : '' }}>Crew</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card-footer" style="background-color: #ffffff;">
                    <button type="submit" class="btn btn-outline-success">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('user.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo"></i> Kembali
                    </a>
                </div>
            </form>
      
</div>
  @section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle Password
        const passwordField = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        togglePassword.addEventListener('click', function () {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        // Toggle Konfirmasi Password
        const confirmField = document.getElementById('password_confirmation');
        const toggleConfirm = document.getElementById('toggleConfirmPassword');

        toggleConfirm.addEventListener('click', function () {
            const type = confirmField.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmField.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });
</script>
@endsection
@endsection