@extends('master')

@section('title', 'Profil Saya')

@section('content')
<div class="container mt-2">
    
            <div class="card">
               <div class="card-header" style="background-color: #0074CC; color: #ffffff;">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-user-circle"></i> Profil Saya
                    </h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="bg-light rounded-circle mx-auto mb-3" style="width:120px;height:120px;">
                                <i class="fas fa-user fa-5x text-muted pt-3"></i>
                            </div>
                            <h5>{{ $user->name }}</h5>
                            <span class="badge bg-{{ $user->role === 'admin' ? 'warning' : 'info' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Nama</th>
                                    <td>: {{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Username</th>
                                    <td>: {{ $user->username }}</td>
                                </tr>
                                <tr>
                                    <th>Role</th>
                                    <td>: <strong>{{ ucfirst($user->role) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Bergabung Pada</th>
                                    <td>: {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end" style="background-color: #ffffff;">
                    <a href="{{ route('profile.edit') }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Profil
                    </a>
                </div>
            </div>
        
</div>
@endsection