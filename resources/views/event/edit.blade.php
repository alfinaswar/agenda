@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Edit Event</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('event.index') }}">Event</a></li>
                    <li class="breadcrumb-item active">Edit Event</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col text-end">
            <a href="{{ route('event.index') }}" class="btn btn-secondary">
                Kembali ke Daftar Event
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0 text-white">Form Edit Event</h4>
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

                    <form id="eventForm" enctype="multipart/form-data" action="{{ route('event.update', $data->id) }}"
                        method="post">
                        @csrf
                        @method('PUT')

                        <div class="modal-content border-0 p-0 bg-transparent">
                            <div class="modal-header bg-transparent p-0 border-0">
                                <h5 class="modal-title d-none">Edit Event</h5>
                            </div>
                            <div class="modal-body p-0">
                                <div class="row g-3 mb-2">
                                    <div class="col-md-6">
                                        <label for="Jenis" class="form-label">Jenis<span
                                                class="text-danger">*</span></label>
                                        <select name="Jenis" id="Jenis" class="form-control" required>
                                            <option value="">Pilih jenis event</option>
                                            <option value="Internal"
                                                {{ old('Jenis', $data->Jenis) == 'Internal' ? 'selected' : '' }}>Internal
                                            </option>
                                            <option value="Eksternal"
                                                {{ old('Jenis', $data->Jenis) == 'Eksternal' ? 'selected' : '' }}>Eksternal
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="NamaEvent" class="form-label">Nama Event<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="NamaEvent" id="NamaEvent" class="form-control" required
                                            placeholder="Masukkan nama event"
                                            value="{{ old('NamaEvent', $data->NamaEvent) }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="Deskripsi" class="form-label">Deskripsi</label>
                                        <textarea name="Deskripsi" id="Deskripsi" class="form-control" rows="2" placeholder="Masukkan deskripsi event">{{ old('Deskripsi', $data->Deskripsi) }}</textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="Keterangan" class="form-label">Keterangan</label>
                                        <input type="text" name="Keterangan" id="Keterangan" class="form-control"
                                            placeholder="Masukkan keterangan event"
                                            value="{{ old('Keterangan', $data->Keterangan) }}">
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
                                    <div class="col-md-12">
                                        <label for="Lokasi" class="form-label">Lokasi</label>
                                        <input type="text" name="Lokasi" id="Lokasi" class="form-control"
                                            placeholder="Masukkan lokasi event" value="{{ old('Lokasi', $data->Lokasi) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer px-0" style="margin-top: 20px;">
                                <button type="submit" class="btn btn-primary me-2">
                                    Simpan Event
                                </button>
                                <a href="{{ route('event.index') }}" class="btn btn-secondary">
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
