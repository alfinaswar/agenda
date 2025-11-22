@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Edit Booking Meeting</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('meeting.index') }}">Booking Meeting</a></li>
                    <li class="breadcrumb-item active">Edit Booking</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col text-end">
            <a href="{{ route('meeting.index') }}" class="btn btn-secondary">
                Kembali ke Daftar Booking
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0 text-white">Form Edit Booking Meeting</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form id="meetingForm" enctype="multipart/form-data"
                        action="{{ route('meeting.update', $meeting->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <label for="Ruangan" class="form-label">Ruangan<span class="text-danger">*</span></label>
                                <select name="Ruangan" id="Ruangan" class="form-control select2" required>
                                    <option value="" disabled
                                        {{ old('Ruangan', $meeting->Ruangan) ? '' : 'selected' }}>Pilih Ruangan</option>
                                    @foreach ($MasterRuangan as $ruangan)
                                        <option value="{{ $ruangan->id }}"
                                            {{ old('Ruangan', $meeting->Ruangan) == $ruangan->id ? 'selected' : '' }}>
                                            {{ $ruangan->NamaRuangan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="JudulMeeting" class="form-label">Judul Meeting<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="JudulMeeting" id="JudulMeeting" class="form-control" required
                                    placeholder="Masukkan judul meeting"
                                    value="{{ old('JudulMeeting', $meeting->JudulMeeting) }}">
                            </div>
                            <div class="col-md-12">
                                <label for="DeskripsiMeeting" class="form-label">Deskripsi Meeting</label>
                                <textarea name="DeskripsiMeeting" id="DeskripsiMeeting" class="form-control" rows="2"
                                    placeholder="Masukkan deskripsi meeting">{{ old('DeskripsiMeeting', $meeting->DeskripsiMeeting) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="Tanggal" class="form-label">Tanggal<span class="text-danger">*</span></label>
                                <input type="date" name="Tanggal" id="Tanggal" class="form-control" required
                                    placeholder="Pilih tanggal meeting" value="{{ old('Tanggal', $meeting->Tanggal) }}">
                            </div>
                            <div class="col-md-3">
                                <label for="JamMulai" class="form-label">Jam Mulai<span class="text-danger">*</span></label>
                                <input type="time" name="JamMulai" id="JamMulai" class="form-control" required
                                    placeholder="Pilih jam mulai" onchange="hitungDurasi()"
                                    value="{{ old('JamMulai', $meeting->JamMulai) }}">
                            </div>
                            <div class="col-md-3">
                                <label for="JamSelesai" class="form-label">Jam Selesai<span
                                        class="text-danger">*</span></label>
                                <input type="time" name="JamSelesai" id="JamSelesai" class="form-control" required
                                    placeholder="Pilih jam selesai" onchange="hitungDurasi()"
                                    value="{{ old('JamSelesai', $meeting->JamSelesai) }}">
                            </div>
                            <div class="col-md-4">
                                <label for="DurasiMenit" class="form-label">Durasi (Menit)</label>
                                <input type="number" name="DurasiMenit" id="DurasiMenit" min="1"
                                    class="form-control" placeholder="Hitung otomatis" readonly
                                    value="{{ old('DurasiMenit', $meeting->DurasiMenit) }}">
                            </div>
                            <div class="col-md-4">
                                <label for="TautanMeeting" class="form-label">Tautan Meeting</label>
                                <input type="text" name="TautanMeeting" id="TautanMeeting" class="form-control"
                                    placeholder="Masukkan tautan (opsional)"
                                    value="{{ old('TautanMeeting', $meeting->TautanMeeting) }}">
                            </div>
                            <div class="col-md-4">
                                <label for="LampiranAgenda" class="form-label">Lampiran Meeting</label>
                                <input type="file" name="LampiranAgenda" id="LampiranAgenda" class="form-control"
                                    placeholder="Pilih lampiran (opsional)">
                                @if ($meeting->LampiranAgenda)
                                    <div class="mt-1">
                                        <a href="{{ asset('storage/lampiran_meeting/' . $meeting->LampiranAgenda) }}"
                                            target="_blank">
                                            Lampiran Terlampir (Klik untuk lihat)
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer mt-3">
                            <button type="submit" class="btn btn-primary">
                                Update Booking
                            </button>
                            <a href="{{ route('meeting.index') }}" class="btn btn-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            $(document).ready(function() {
                $('#Ruangan').select2({
                    placeholder: "Pilih Ruangan",
                    width: '100%'
                });
            });

            function hitungDurasi() {
                const jamMulai = document.getElementById('JamMulai').value;
                const jamSelesai = document.getElementById('JamSelesai').value;
                const inputDurasi = document.getElementById('DurasiMenit');
                if (jamMulai && jamSelesai) {
                    const [mulaiJam, mulaiMenit] = jamMulai.split(':').map(Number);
                    const [selesaiJam, selesaiMenit] = jamSelesai.split(':').map(Number);
                    let menitMulai = mulaiJam * 60 + mulaiMenit;
                    let menitSelesai = selesaiJam * 60 + selesaiMenit;
                    let durasi = menitSelesai - menitMulai;
                    if (durasi < 0) {
                        durasi = 0;
                    }
                    inputDurasi.value = durasi;
                } else {
                    inputDurasi.value = '';
                }
            }
        </script>
    @endpush
@endsection
