<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0074CC, #00CED1);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 1rem;
        }

        .login-container {
            width: 100%;
            max-width: 900px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
        }

        .btn-forgot {
            color: #fff;
        }
.btn-forgot i {
    color: #fff; /* ✅ pastikan ikon juga putih */
}
.btn-forgot:hover {
    color: #fff; /* ✅ pastikan ikon juga putih */
}

        /* LAPTOP: Landscape & Card lebih kecil */
        @media (min-width: 768px) {
            .login-container {
                max-width: 750px;
            }
            .login-card {
                flex-direction: row;
                min-height: 300px;
            }
            .login-left {
                flex: 1;
                padding: 2rem 2.5rem;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            .login-right {
                background: #0074CC;
                color: white;
                padding: 2rem;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                text-align: center;
                border-radius: 0 20px 20px 0;
                flex: 0 0 280px;
            }
            .login-right i {
                font-size: 3rem;
                margin-bottom: 1rem;
            }
            .login-right h4 {
                font-size: 1.6rem;
                margin-bottom: 0.4rem;
            }
            .login-right p {
                font-size: 0.95rem;
                opacity: 0.9;
            }
        }

        /* HP: Portrait (Sama seperti login) */
        @media (max-width: 767px) {
            .login-right {
                background: #0074CC;
                color: white;
                padding: 2rem;
                text-align: center;
                border-radius: 20px 20px 0 0;
            }
            .login-right i {
                font-size: 3rem;
                margin-bottom: 0.8rem;
            }
            .login-right h4 {
                font-size: 1.6rem;
            }
            .login-left {
                padding: 2rem;
            }
        }

        .form-control {
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
        }

        .input-group .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .input-group .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            background: #f8f9fa;
            border: 1px solid #ced4da;
            color: #6c757d;
        }

        .input-group .btn:hover {
            background: #e9ecef;
        }

        .btn-forgot {
            background: #0074CC;
            border: none;
            border-radius: 12px;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
           
        }
        .btn-forgot:hover {
            background: #0A6ABF;
        }

        .text-link {
            color: #0074CC;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .text-link:hover {
            text-decoration: underline;
            color: #003d73;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">

            <!-- BAGIAN KIRI: FORM -->
            <div class="login-left">
               

                <form action="{{ route('forgot') }}" method="POST">
                    @csrf

                    <!-- Username -->
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                               value="{{ old('username') }}" required autofocus placeholder="Masukan username anda">
                        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Password Baru + Mata -->
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukan password baru anda" required>
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Konfirmasi Password + Mata -->
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password baru anda" required>
                            <button type="button" class="btn btn-outline-secondary" id="toggleConfirm">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tombol Ubah -->
                    <button type="submit" class="btn btn-forgot w-100">
                        <i class="fas fa-save"></i> Ubah Password
                    </button>

                    <!-- Kembali ke Login -->
                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="text-link">
                            <i class="fas fa-arrow-left"></i> Kembali ke Login
                        </a>
                    </div>
                </form>
            </div>

            <!-- BAGIAN KANAN: BRANDING (Sama seperti login) -->
            <div class="login-right">
                <i class="fas fa-ship"></i>
                <h4>CrewKapal</h4>
                <p>Sistem Manajemen Task dan Laporan Crew Kapal</p>
            </div>

        </div>
    </div>

    <!-- JavaScript: Toggle Password & Konfirmasi -->
    <script>
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
    </script>
</body>
</html>