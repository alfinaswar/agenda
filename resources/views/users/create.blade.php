@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Tambah User</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Master User</a></li>
                    <li class="breadcrumb-item active">Tambah User</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col text-end">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                Kembali ke Daftar User
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0 text-white">Form Tambah User</h4>
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

                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                class="form-control" required autofocus placeholder="Masukkan nama user">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="form-control" required placeholder="Masukkan email user">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" class="form-control" required
                                placeholder="Password">
                        </div>
                        <div class="mb-3">
                            <label for="confirm-password" class="form-label">Konfirmasi Password <span
                                    class="text-danger">*</span></label>
                            <input type="password" name="confirm-password" id="confirm-password" class="form-control"
                                required placeholder="Konfirmasi Password">
                        </div>
                        <div class="mb-3">
                            <label for="roles" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" id="roles" name="roles[]" required multiple>
                                @foreach ($roles as $role)
                                    <option value="{{ $role }}"
                                        {{ collect(old('roles'))->contains($role) ? 'selected' : '' }}>
                                        {{ $role }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Tekan CTRL/CMD untuk memilih lebih dari satu role.</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
