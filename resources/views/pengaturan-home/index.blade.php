@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Pengaturan Halaman Utama</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pengaturan Halaman Utama</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0 text-white">Pengaturan Tampilan Halaman Utama</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('pengaturan-home.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="ShowAgenda" class="form-label">Tampilkan Agenda</label>
                            <select name="ShowAgenda" id="ShowAgenda" class="form-select">
                                <option value=""
                                    {{ old('ShowAgenda', $data->ShowAgenda ?? null) === null ? 'selected' : '' }}>-- Pilih
                                    --</option>
                                <option value="Y"
                                    {{ old('ShowAgenda', $data->ShowAgenda ?? null) === 'Y' ? 'selected' : '' }}>Ya
                                </option>
                                <option value="N"
                                    {{ old('ShowAgenda', $data->ShowAgenda ?? null) === 'N' ? 'selected' : '' }}>Tidak
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="ShowBooking" class="form-label">Tampilkan Booking</label>
                            <select name="ShowBooking" id="ShowBooking" class="form-select">
                                <option value=""
                                    {{ old('ShowBooking', $data->ShowBooking ?? null) === null ? 'selected' : '' }}>-- Pilih
                                    --</option>
                                <option value="Y"
                                    {{ old('ShowBooking', $data->ShowBooking ?? null) === 'Y' ? 'selected' : '' }}>Ya
                                </option>
                                <option value="N"
                                    {{ old('ShowBooking', $data->ShowBooking ?? null) === 'N' ? 'selected' : '' }}>Tidak
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="ShowEvent" class="form-label">Tampilkan Event</label>
                            <select name="ShowEvent" id="ShowEvent" class="form-select">
                                <option value=""
                                    {{ old('ShowEvent', $data->ShowEvent ?? null) === null ? 'selected' : '' }}>-- Pilih --
                                </option>
                                <option value="Y"
                                    {{ old('ShowEvent', $data->ShowEvent ?? null) === 'Y' ? 'selected' : '' }}>Ya
                                </option>
                                <option value="N"
                                    {{ old('ShowEvent', $data->ShowEvent ?? null) === 'N' ? 'selected' : '' }}>Tidak
                                </option>
                            </select>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
