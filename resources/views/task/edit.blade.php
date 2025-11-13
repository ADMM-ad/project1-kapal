@extends('master')

@section('title', 'Edit Task - Kapal App')

@section('content')
<div class="container mt-2">
    <div class="card card-primary">
        <div class="card-header" style="background-color: #0074CC;">
            <h3 class="card-title"><i class="fas fa-tasks mr-2"></i>Form Edit Task</h3>
        </div>
                <form action="{{ route('task.update', $task) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <!-- Notifikasi -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                                <button type="button" class="close" onclick="this.parentElement.remove();">x</button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-times-circle mr-2"></i>{{ session('error') }}
                                <button type="button" class="close" onclick="this.parentElement.remove();">x</button>
                            </div>
                        @endif

                        <!-- Judul -->
                        <div class="form-group">
                            <label>Judul Task <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                                   value="{{ old('judul', $task->judul) }}" required>
                            @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-group">
                            <label>Deskripsi Task</label>
                            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $task->deskripsi) }}</textarea>
                        </div>

                        <!-- 4 Tanggal: 6+6 di laptop, 12+12 di HP -->
                        <div class="row">
                            <!-- Tanggal Mulai -->
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                           value="{{ old('tanggal_mulai', $task->tanggal_mulai ? \Carbon\Carbon::parse($task->tanggal_mulai)->format('Y-m-d\TH:i') : '') }}" required>
                                    @error('tanggal_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Deadline -->
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>Deadline</label>
                                    <input type="datetime-local" name="deadline" class="form-control @error('deadline') is-invalid @enderror"
                                           value="{{ old('deadline', $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('Y-m-d\TH:i') : '') }}">
                                    @error('deadline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Tanggal Dikerjakan -->
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Pengerjaan</label>
                                    <input type="datetime-local" name="tanggal_dikerjakan" class="form-control @error('tanggal_dikerjakan') is-invalid @enderror"
                                           value="{{ old('tanggal_dikerjakan', $task->tanggal_dikerjakan ? \Carbon\Carbon::parse($task->tanggal_dikerjakan)->format('Y-m-d\TH:i') : '') }}">
                                    @error('tanggal_dikerjakan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Tanggal Selesai -->
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Selesai</label>
                                    <input type="datetime-local" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                           value="{{ old('tanggal_selesai', $task->tanggal_selesai ? \Carbon\Carbon::parse($task->tanggal_selesai)->format('Y-m-d\TH:i') : '') }}">
                                    @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="belum" {{ old('status', $task->status) == 'belum' ? 'selected' : '' }}>Belum</option>
                                <option value="proses" {{ old('status', $task->status) == 'proses' ? 'selected' : '' }}>Proses</option>
                                <option value="selesai" {{ old('status', $task->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Pilihan Crew -->
                        <div class="form-group">
                            <label>Pilih Crew <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="allcrew" value="ya" id="allcrew_ya"
                                           {{ old('allcrew', $task->allcrew) === 'ya' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="allcrew_ya">Semua Crew</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="allcrew" value="tidak" id="allcrew_tidak"
                                           {{ old('allcrew', $task->allcrew) === 'tidak' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="allcrew_tidak">Pilih Crew</label>
                                </div>
                            </div>
                        </div>

                        <!-- Dropdown + Tambah Crew (4+2 di laptop, 10+12 di HP) -->
                        <div id="crewSelection" style="{{ old('allcrew', $task->allcrew) === 'tidak' ? '' : 'display: none;' }}">
                            <div class="form-group">
                                <label>Pilih Crew</label>
                                <div class="row align-items-center">
                                    <div class="col-10 col-md-4">
                                        <select class="form-control" id="crewSelect">
                                            <option value="">-- Pilih Crew --</option>
                                            @foreach($crewUsers as $id => $name)
                                                <option value="{{ $id }}" {{ in_array($id, $selectedCrew) ? 'disabled' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2 mt-2 mt-md-0">
                                        <button type="button" class="btn btn-outline-primary w-100 h-100 d-flex align-items-center justify-content-center" id="addCrewBtn">
                                            <i class="fas fa-plus"></i> Tambah
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Daftar Crew Terpilih -->
                            <div id="selectedCrewList" class="mt-2">
                                @foreach($task->detailTasks as $dt)
                                    @if($dt->user)
                                        <span class="badge bg-info mr-1 mb-1 p-2">
                                            {{ $dt->user->name }}
                                            <input type="hidden" name="crew_ids[]" value="{{ $dt->user_id }}">
                                            <a href="#" class="text-white ml-1 remove-crew">x</a>
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                            @error('crew_ids') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="card-footer" style="background-color: #ffffff;">
                        <button type="submit" class="btn btn-outline-success">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('task.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
          
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const allcrewYa = document.getElementById('allcrew_ya');
        const allcrewTidak = document.getElementById('allcrew_tidak');
        const crewSelection = document.getElementById('crewSelection');
        const addCrewBtn = document.getElementById('addCrewBtn');
        const crewSelect = document.getElementById('crewSelect');
        const selectedCrewList = document.getElementById('selectedCrewList');

        function toggleCrewSelection() {
            crewSelection.style.display = allcrewTidak.checked ? 'block' : 'none';
        }
        allcrewYa.addEventListener('change', toggleCrewSelection);
        allcrewTidak.addEventListener('change', toggleCrewSelection);
        toggleCrewSelection();

        addCrewBtn.addEventListener('click', function () {
            const selectedOption = crewSelect.options[crewSelect.selectedIndex];
            if (!selectedOption || !selectedOption.value) return;

            const userId = selectedOption.value;
            const userName = selectedOption.text;

            if (document.querySelector(`input[name="crew_ids[]"][value="${userId}"]`)) {
                alert('Crew sudah dipilih!');
                return;
            }

            const badge = document.createElement('span');
            badge.className = 'badge bg-info mr-1 mb-1 p-2';
            badge.innerHTML = `
                ${userName}
                <input type="hidden" name="crew_ids[]" value="${userId}">
                <a href="#" class="text-white ml-1 remove-crew">x</a>
            `;
            selectedCrewList.appendChild(badge);
            selectedOption.disabled = true;
            crewSelect.value = '';
        });

        selectedCrewList.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-crew')) {
                e.preventDefault();
                const badge = e.target.parentElement;
                const userId = badge.querySelector('input').value;
                const option = crewSelect.querySelector(`option[value="${userId}"]`);
                if (option) option.disabled = false;
                badge.remove();
            }
        });
    });
</script>
@endsection