<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Task Management')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" />
    
    <style>
        body {
            font-family: 'Inter', sans-serif !important;
            background-color: #f4f6f9;
        }

        .navbar-custom {
             background: linear-gradient(to right, 
        #004C8C,   /* warna gelap di atas */
        #0074CC 50%, /* warna terang di tengah */
        #0074CC   /* warna gelap di bawah */
    ) !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 1030 !important;
        }

        .wrapper {
            padding-top: 60px; /* Kompensasi untuk navbar fixed */
        }

        

        .main-sidebar {
             background: linear-gradient(to bottom, 
        #004C8C,   /* warna gelap di atas */
        #0074CC 50%, /* warna terang di tengah */
        #0074CC    /* warna gelap di bawah */
    ) !important;
        }

        .nav-link, .brand-text, .nav p, .nav-icon, .brand-link {
            color: #ffffff !important;
        }

.brand-link {
    display: flex;
    align-items: center; /* sejajarkan vertikal */
    gap: 8px; /* jarak antara ikon dan teks */
}

.brand-link .brand-image {
    height: 30px;           /* sesuaikan ukuran ikon */
    width: 30px;
    line-height: 1;
    margin-top: 0 !important; /* hilangkan offset bawaan */
}



        .brand-link:hover {
            color: #ffffff !important;
        }

       
.pagination {
    background-color: #004C8C;  /* Ganti dengan warna yang diinginkan */
    border-radius: 0.25rem;  /* Optional: Memberikan border radius pada pagination */
}

/* Mengubah warna link pagination */
.pagination .page-link {
    color: white;  /* Warna teks saat tidak aktif */
    background-color: #00518d;  /* Warna background link */
    border: 1px solid #00518d;  /* Warna border link */
}

/* Mengubah warna saat hover pada link */
.pagination .page-link:hover {
    background-color: #0074CC;  /* Warna hover saat link ditekan */
    color: white;  /* Warna teks saat hover */
}

/* Mengubah warna aktif pagination */
.pagination .active .page-link {
    background-color: #0074CC;  /* Warna latar belakang saat aktif */
    color: white;  /* Warna teks saat aktif */
    border-color: #0074CC;  /* Warna border saat aktif */
}

/* Mengubah warna untuk tombol disabled */
.pagination .disabled .page-link {
    background-color: #e0e0e0;  /* Warna latar belakang saat disabled */
    color: #b0b0b0;  /* Warna teks saat disabled */
    border-color: #e0e0e0;  /* Warna border saat disabled */
}

.card-bordered-blue {
        border: 2px solid #0074CC !important;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 116, 204, 0.18) !important;
        transition: all 0.3s ease;
        background: #ffffff;
    }

    .card-bordered-blue:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(0, 116, 204, 0.28) !important;
    }

    .card-bordered-blue .card-header {
        
        border-bottom: 1px solid #0074CC;
        border-radius: 10px 10px 0 0 !important;
    }

    .card-bordered-blue .card-footer {
        
        border-top: 1px solid #0074CC;
        border-radius: 0 0 10px 10px !important;
    }

    .choices__inner {
    min-height: 38px; /* default-nya sekitar 44px, kamu bisa ubah ke 30-35px */
    padding: 4px 8px;
   
  }

  .choices__list--single {
    padding: 0; /* mengurangi tinggi */
  }

  .choices__input {
    
    padding: 4px 6px;
  }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">

    <!-- Navbar Fixed -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light navbar-custom fixed-top">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item d-sm-none">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-sm-none">
                <a href="#" class="nav-link">
                    <i class="fas fa-ship"></i>
                    <span class="ml-2">CrewKapal</span>
                </a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
             <li class="nav-item d-none d-sm-block">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <!-- Profile Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item" style="color: #004C8C;">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form></li>
                </ul>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

<div class="wrapper">
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4 ">
        <!-- Brand Logo -->
        <a href="#" class="brand-link">
            <i class="fas fa-ship brand-image "></i>
            <span class="brand-text font-weight-light">CrewKapal</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    @if(auth()->user()->role == 'admin')
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="/admin" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard Admin</p>
                        </a>
                    </li>
                    @endif
                     @if(auth()->user()->role == 'crew')
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="/crew" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard Crew</p>
                        </a>
                    </li>
                    @endif

                    <!-- Profil -->
                    <li class="nav-item">
                        <a href="/profile" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Profil</p>
                        </a>
                    </li>
                     @if(auth()->user()->role == 'admin')
                    <li class="nav-item">
                        <a href="/user" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>User Management</p>
                        </a>
                    </li>
                    @endif
                    <!-- Task Dropdown -->
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tasks"></i>
                            <p>
                                Task Management
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if(auth()->user()->role == 'admin')
                            <li class="nav-item">
                                <a href="/task/create" class="nav-link">
                                    <i class="fas fa-plus-circle nav-icon"></i>
                                    <p>Tambah Tugas Crew</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/task" class="nav-link">
                                    <i class="fas fa-list-alt nav-icon"></i>
                                    <p>Laporan Tugas Crew</p>
                                </a>
                            </li>
                            @endif
                            @if(auth()->user()->role == 'crew')
                            <li class="nav-item">
                                <a href="/task/my" class="nav-link">
                                    <i class="fas fa-list-alt nav-icon"></i>
                                    <p>Laporan Tugas Saya</p>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>

                    <!-- Laporan Dropdown -->
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-exclamation-triangle"></i>
                            <p>
                                Hazard
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if(auth()->user()->role == 'admin')
                            <li class="nav-item">
                                <a href="/hazard" class="nav-link">
                                    <i class="fas fa-list-alt nav-icon"></i>
                                    <p>Laporan Hazard</p>
                                </a>
                            </li>
                            @endif
                            @if(auth()->user()->role == 'crew')
                            <li class="nav-item">
                                <a href="/hazard/create" class="nav-link">
                                    <i class="fas fa-plus-circle nav-icon"></i>
                                    <p>Tambah Laporan Hazard</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/hazard/my" class="nav-link">
                                    <i class="fas fa-list-alt nav-icon"></i>
                                    <p>Laporan Hazard Saya</p>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content')
    </div>

</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<!-- Choices.js -->
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<!-- Auto Init SEMUA <select> -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selects = document.querySelectorAll('select');
        selects.forEach(select => {
            const instance = new Choices(select, {
                searchEnabled: true,
                itemSelectText: '',
                position: 'bottom',
            });
        });

        // Auto dismiss alert
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    new Litepicker({
        element: document.getElementById('daterange'),
        singleMode: false,
        format: 'DD/MM/YYYY',
        lang: 'id',
        autoApply: true,
        dropdowns: { minYear: 2020, months: true, years: true },
        setup: (picker) => {
            picker.on('selected', (start, end) => {
                const formatted = start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY');
                document.getElementById('daterange').value = formatted;
            });
        }
    });
});
</script>
@yield('scripts')
</body>
</html>