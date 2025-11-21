@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Tambah Master Ruangan</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('master-ruangan.index') }}">Master Ruangan</a></li>
                    <li class="breadcrumb-item active">Tambah Ruangan</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col text-end">
            <a href="{{ route('master-ruangan.index') }}" class="btn btn-secondary">
                Kembali ke Daftar Ruangan
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0 text-white">Form Tambah Ruangan</h4>
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

                    <form action="{{ route('master-ruangan.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="NamaRuangan" class="form-label">Nama Ruangan <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="NamaRuangan" id="NamaRuangan" value="{{ old('NamaRuangan') }}"
                                class="form-control" required autofocus placeholder="Contoh: Ruang Rapat Utama">
                        </div>

                        <div class="mb-3">
                            <label for="Kapasitas" class="form-label">Kapasitas</label>
                            <input type="number" name="Kapasitas" id="Kapasitas" value="{{ old('Kapasitas') }}"
                                class="form-control" min="1" placeholder="Contoh: 30">
                        </div>

                        <div class="mb-3">
                            <label for="Lokasi" class="form-label">Lokasi</label>
                            <input type="text" name="Lokasi" id="Lokasi" value="{{ old('Lokasi') }}"
                                class="form-control" placeholder="Contoh: Lantai 2, Gedung A">
                        </div>

                        <div class="mb-3">
                            <label for="Fasilitas" class="form-label">Fasilitas</label>
                            <textarea name="Fasilitas" id="Fasilitas" class="form-control" placeholder="Contoh: Proyektor, Whiteboard, AC">{{ old('Fasilitas') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="StatusRuangan" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="StatusRuangan" name="StatusRuangan" required>
                                <option value="" disabled {{ old('StatusRuangan') ? '' : 'selected' }}>Pilih Status
                                </option>
                                <option value="Aktif" {{ old('StatusRuangan') == 'Aktif' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="Non-Aktif" {{ old('StatusRuangan') == 'Non-Aktif' ? 'selected' : '' }}>
                                    Non-Aktif</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('master-ruangan.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
