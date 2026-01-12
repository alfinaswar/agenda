@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Edit Pegawai</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('master-pegawai.index') }}">Master Pegawai</a></li>
                    <li class="breadcrumb-item active">Edit Pegawai</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col text-end">
            <a href="{{ route('master-pegawai.index') }}" class="btn btn-secondary">
                Kembali ke Daftar Pegawai
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0 text-white">Form Edit Pegawai</h4>
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

                    <form action="{{ route('master-pegawai.update', $pegawai->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="Nik" class="form-label">NIK <span class="text-danger">*</span></label>
                            <input type="text" name="Nik" id="Nik" value="{{ old('Nik', $pegawai->Nik) }}"
                                class="form-control" required autofocus maxlength="16" minlength="16" pattern="\d{16}"
                                placeholder="Contoh: 3210012345010001">
                            <div class="form-text">NIK harus terdiri dari 16 digit angka sesuai standar di Indonesia.</div>
                        </div>

                        <div class="mb-3">
                            <label for="NamaPeserta" class="form-label">Nama Peserta <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="NamaPeserta" id="NamaPeserta"
                                value="{{ old('NamaPeserta', $pegawai->NamaPeserta) }}" class="form-control" required
                                placeholder="Contoh: Andi Wijaya">
                        </div>

                        <div class="mb-3">
                            <label for="AsalUnit" class="form-label">Asal Unit</label>
                            <input type="text" name="AsalUnit" id="AsalUnit"
                                value="{{ old('AsalUnit', $pegawai->AsalUnit) }}" class="form-control"
                                placeholder="Contoh: Bagian Keuangan">
                        </div>

                        <div class="mb-3">
                            <label for="Gender" class="form-label">Gender <span class="text-danger">*</span></label>
                            <select class="form-select" id="Gender" name="Gender" required>
                                <option value="" disabled {{ old('Gender', $pegawai->Gender) ? '' : 'selected' }}>
                                    Pilih Gender</option>
                                <option value="L" {{ old('Gender', $pegawai->Gender) == 'L' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="P" {{ old('Gender', $pegawai->Gender) == 'P' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="TandaTangan" class="form-label">Tanda Tangan</label>
                            <input type="file" name="TandaTangan" id="TandaTangan" class="form-control" accept="image/*">
                            <div class="form-text">Upload gambar tanda tangan (.jpg/.jpeg/.png, opsional). Kosongkan jika
                                tidak ingin mengganti.</div>

                            <div id="preview-tandatangan" class="mt-2">
                                @php
                                    $previewTtd = old('TandaTangan')
                                        ? old('TandaTangan')
                                        : ($pegawai->TandaTangan
                                            ? asset('storage/' . $pegawai->TandaTangan)
                                            : null);
                                @endphp
                                @if ($pegawai->TandaTangan)
                                    <img src="{{ asset('storage/' . $pegawai->TandaTangan) }}" alt="Tanda Tangan Lama"
                                        style="max-height:160px; max-width:360px;">
                                    <div class="form-text text-muted">Tanda tangan saat ini</div>
                                @endif
                            </div>
                        </div>


                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('master-pegawai.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('TandaTangan');
            const preview = document.getElementById('preview-tandatangan');
            input.addEventListener('change', function(event) {
                // Hapus img lama jika ada (hanya preview, gambar dari DB tetap tampil jika tidak memilih file baru)
                // Cukup hapus semua kecuali teks info
                Array.from(preview.querySelectorAll('img')).forEach(function(img) {
                    img.remove();
                });
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Preview Tanda Tangan';
                        img.style.maxHeight = '160px';
                        img.style.maxWidth = '360px';
                        preview.insertBefore(img, preview.firstChild);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            });
        });
    </script>
@endpush
