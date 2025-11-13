@extends('master')

@section('title', 'Edit Profil')

@section('content')
<div class="container mt-2">
  <div class="card card-primary">
            <div class="card-header" style="background-color: #0074CC;">
                <h3 class="card-title"><i class="fas fa-user-edit mr-2"></i>Form Edit Profil Anda</h3>
            </div>


                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <!-- Nama -->
                        <div class="mb-3">
                            <label class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Username -->
                        <div class="mb-3">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                                   value="{{ old('username', $user->username) }}" required>
                            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                      <!-- Password -->
                <div class="mb-3">
                    <label class="form-label">Password Baru 
                        <small class="text-muted">(kosongkan jika tidak diganti)</small>
                    </label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" 
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Minimal 8 karakter">
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                        @error('password') 
                            <div class="invalid-feedback d-block">{{ $message }}</div> 
                        @enderror
                    </div>
                </div>

                <!-- Konfirmasi Password -->
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password
                         <small class="text-muted">(kosongkan jika tidak diganti)</small>
                    </label>
                    <div class="input-group">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="form-control" placeholder="Ulangi password">
                        <button type="button" class="btn btn-outline-secondary" id="toggleConfirm">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-footer" style="background-color: #ffffff;">
                <button type="submit" class="btn btn-outline-success">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

{{-- JavaScript untuk Toggle Password --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle Password
    document.getElementById('togglePassword').addEventListener('click', function () {
        const password = document.getElementById('password');
        const icon = this.querySelector('i');

        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    // Toggle Konfirmasi Password
    document.getElementById('toggleConfirm').addEventListener('click', function () {
        const confirm = document.getElementById('password_confirmation');
        const icon = this.querySelector('i');

        if (confirm.type === 'password') {
            confirm.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            confirm.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
});
</script>
@endsection