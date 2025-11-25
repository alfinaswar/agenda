@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Tambah Role</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Role</a></li>
                    <li class="breadcrumb-item active">Tambah Role</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col text-end">
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                Kembali ke Daftar Role
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0 text-white">Form Tambah Role</h4>
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

                    <form action="{{ route('roles.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Role <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                class="form-control" required autofocus placeholder="Contoh: Admin">
                        </div>

                        <div class="mb-3">
                            <label class="form-label mb-1">Permission <span class="text-danger">*</span></label>
                            <div class="row">
                                @foreach ($permission as $value)
                                    <div class="col-md-4 col-6 mb-1">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="permission[]"
                                                id="perm-{{ $value->id }}" value="{{ $value->id }}"
                                                {{ is_array(old('permission')) && in_array($value->id, old('permission')) ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="perm-{{ $value->id }}">{{ $value->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
