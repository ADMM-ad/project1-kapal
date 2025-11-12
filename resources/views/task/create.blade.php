@extends('master')

@section('title', 'Buat Task Baru - Kapal App')

@section('content')
<div class="container mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tasks mr-1"></i> Buat Task Baru
                    </h3>
                </div>

                <form action="{{ route('task.store') }}" method="POST">
                    @csrf
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

                        <!-- Judul -->
                        <div class="form-group">
                            <label>Judul Task <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                                   value="{{ old('judul') }}" required>
                            @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
                        </div>

                        <!-- Tanggal Mulai (default: sekarang) -->
                        <div class="form-group">
                            <label>Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                   value="{{ old('tanggal_mulai', now()->format('Y-m-d\TH:i')) }}" required>
                            @error('tanggal_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Deadline -->
                        <div class="form-group">
                            <label>Deadline</label>
                            <input type="datetime-local" name="deadline" class="form-control @error('deadline') is-invalid @enderror"
                                   value="{{ old('deadline') }}">
                            @error('deadline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Pilihan Crew -->
                        <div class="form-group">
                            <label>Pilih Crew <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="allcrew" value="ya" id="allcrew_ya"
                                           {{ old('allcrew', 'ya') === 'ya' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="allcrew_ya">Semua Crew</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="allcrew" value="tidak" id="allcrew_tidak"
                                           {{ old('allcrew') === 'tidak' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="allcrew_tidak">Pilih Crew</label>
                                </div>
                            </div>
                            @error('allcrew') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Dropdown + Tambah Crew (hanya muncul jika pilih manual) -->
                        <div id="crewSelection" style="{{ old('allcrew') === 'tidak' ? '' : 'display: none;' }}">
                            <div class="form-group">
                                <label>Pilih Crew</label>
                                <div class="input-group mb-2">
                                    <select class="form-control" id="crewSelect">
                                        <option value="">-- Pilih Crew --</option>
                                        @foreach($crewUsers as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-primary" id="addCrewBtn">
                                            <i class="fas fa-plus"></i> Tambah
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Daftar Crew Terpilih -->
                            <div id="selectedCrewList" class="mt-2">
                                @if(old('crew_ids'))
                                    @foreach(old('crew_ids') as $userId)
                                        @if(isset($crewUsers[$userId]))
                                            <span class="badge bg-info mr-1 mb-1 p-2">
                                                {{ $crewUsers[$userId] }}
                                                <input type="hidden" name="crew_ids[]" value="{{ $userId }}">
                                                <a href="#" class="text-white ml-1 remove-crew">×</a>
                                            </span>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            @error('crew_ids') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Simpan Task
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const allcrewYa = document.getElementById('allcrew_ya');
        const allcrewTidak = document.getElementById('allcrew_tidak');
        const crewSelection = document.getElementById('crewSelection');
        const addCrewBtn = document.getElementById('addCrewBtn');
        const crewSelect = document.getElementById('crewSelect');
        const selectedCrewList = document.getElementById('selectedCrewList');

        // Toggle visibility
        function toggleCrewSelection() {
            crewSelection.style.display = allcrewTidak.checked ? 'block' : 'none';
        }
        allcrewYa.addEventListener('change', toggleCrewSelection);
        allcrewTidak.addEventListener('change', toggleCrewSelection);
        toggleCrewSelection();

        // Tambah crew
        addCrewBtn.addEventListener('click', function () {
            const selectedOption = crewSelect.options[crewSelect.selectedIndex];
            if (!selectedOption || !selectedOption.value) return;

            const userId = selectedOption.value;
            const userName = selectedOption.text;

            // Cek duplikat
            if (document.querySelector(`input[name="crew_ids[]"][value="${userId}"]`)) {
                alert('Crew sudah dipilih!');
                return;
            }

            // Tambah badge
            const badge = document.createElement('span');
            badge.className = 'badge bg-info mr-1 mb-1 p-2';
            badge.innerHTML = `
                ${userName}
                <input type="hidden" name="crew_ids[]" value="${userId}">
                <a href="#" class="text-white ml-1 remove-crew">×</a>
            `;
            selectedCrewList.appendChild(badge);

            // Reset select
            crewSelect.value = '';
        });

        // Hapus crew
        selectedCrewList.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-crew')) {
                e.preventDefault();
                e.target.parentElement.remove();
            }
        });
    });
</script>
@endsection