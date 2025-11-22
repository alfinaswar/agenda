@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Edit Agenda</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('agenda.index') }}">Agenda</a></li>
                    <li class="breadcrumb-item active">Edit Agenda</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col text-end">
            <a href="{{ route('agenda.index') }}" class="btn btn-secondary">
                Kembali ke Daftar Agenda
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0 text-white">Form Edit Agenda</h4>
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

                    <form id="agendaForm" enctype="multipart/form-data" action="{{ route('agenda.update', $data->id) }}"
                        method="post">
                        @csrf
                        @method('PUT')

                        <div class="modal-content border-0 p-0 bg-transparent">
                            <div class="modal-header bg-transparent p-0 border-0">
                                <!-- Title only for mobile -->
                                <h5 class="modal-title d-none">Edit Agenda</h5>
                            </div>
                            <div class="modal-body p-0">
                                <div class="row g-3 mb-2">
                                    <div class="col-md-6">
                                        <label for="JudulAgenda" class="form-label">Judul Agenda<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="JudulAgenda" id="JudulAgenda" class="form-control"
                                            required placeholder="Masukkan judul agenda"
                                            value="{{ old('JudulAgenda', $data->JudulAgenda) }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="KategoriAgenda" class="form-label">Kategori Agenda</label>
                                        <input type="text" name="KategoriAgenda" id="KategoriAgenda" class="form-control"
                                            placeholder="Masukkan kategori agenda"
                                            value="{{ old('KategoriAgenda', $data->KategoriAgenda) }}">
                                    </div>

                                    <div class="col-md-12">
                                        <label for="DeskripsiAgenda" class="form-label">Deskripsi Agenda</label>
                                        <textarea name="DeskripsiAgenda" id="DeskripsiAgenda" class="form-control" rows="2"
                                            placeholder="Masukkan deskripsi agenda">{{ old('DeskripsiAgenda', $data->DeskripsiAgenda) }}</textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="TanggalMulai" class="form-label">Tanggal Mulai<span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="TanggalMulai" id="TanggalMulai" class="form-control"
                                            required placeholder="Pilih tanggal mulai"
                                            value="{{ old('TanggalMulai', $data->TanggalMulai) }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="TanggalSelesai" class="form-label">Tanggal Selesai</label>
                                        <input type="date" name="TanggalSelesai" id="TanggalSelesai" class="form-control"
                                            placeholder="Pilih tanggal selesai"
                                            value="{{ old('TanggalSelesai', $data->TanggalSelesai) }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="JamMulai" class="form-label">Jam Mulai</label>
                                        <input type="time" name="JamMulai" id="JamMulai" class="form-control"
                                            placeholder="Pilih jam mulai" value="{{ old('JamMulai', $data->JamMulai) }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="JamSelesai" class="form-label">Jam Selesai</label>
                                        <input type="time" name="JamSelesai" id="JamSelesai" class="form-control"
                                            placeholder="Pilih jam selesai"
                                            value="{{ old('JamSelesai', $data->JamSelesai) }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="LokasiAgenda" class="form-label">Lokasi Agenda</label>
                                        <input type="text" name="LokasiAgenda" id="LokasiAgenda" class="form-control"
                                            placeholder="Masukkan lokasi agenda"
                                            value="{{ old('LokasiAgenda', $data->LokasiAgenda) }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="TautanRapat" class="form-label">Tautan Rapat</label>
                                        <input type="text" name="TautanRapat" id="TautanRapat" class="form-control"
                                            placeholder="Masukkan tautan rapat (bila ada)"
                                            value="{{ old('TautanRapat', $data->TautanRapat) }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="StatusAgenda" class="form-label">Status Agenda</label>
                                        <select name="StatusAgenda" id="StatusAgenda" class="form-control"
                                            placeholder="Pilih status agenda">
                                            <option value="Draft"
                                                {{ old('StatusAgenda', $data->StatusAgenda) == 'Draft' ? 'selected' : '' }}>
                                                Draft</option>
                                            <option value="Pending"
                                                {{ old('StatusAgenda', $data->StatusAgenda) == 'Pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="Disetujui"
                                                {{ old('StatusAgenda', $data->StatusAgenda) == 'Disetujui' ? 'selected' : '' }}>
                                                Disetujui</option>
                                            <option value="Selesai"
                                                {{ old('StatusAgenda', $data->StatusAgenda) == 'Selesai' ? 'selected' : '' }}>
                                                Selesai</option>
                                            <option value="Dibatalkan"
                                                {{ old('StatusAgenda', $data->StatusAgenda) == 'Dibatalkan' ? 'selected' : '' }}>
                                                Dibatalkan</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="LampiranAgenda" class="form-label">Lampiran Agenda</label>
                                        <input type="file" name="LampiranAgenda" id="LampiranAgenda"
                                            class="form-control" placeholder="Pilih lampiran (opsional)">
                                        @if ($data->LampiranAgenda)
                                            <div class="mt-1">
                                                <a href="{{ $data->LampiranAgenda }}" target="_blank">Lihat Lampiran
                                                    Saat Ini</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer px-0">
                                <button type="submit" class="btn btn-primary">
                                    Simpan Agenda
                                </button>
                                <a href="{{ route('agenda.index') }}" class="btn btn-secondary">
                                    Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
