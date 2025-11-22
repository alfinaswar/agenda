@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Master User</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Master User</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col text-end">
            <a href="{{ route('users.create') }}" class="btn btn-success">
                Tambah User
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0 text-white">Daftar User</h4>
                    <p class="card-text text-white-50 mb-0">
                        Tabel ini berisi semua data user yang tersedia.
                    </p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datanew cell-border compact stripe" id="userTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @foreach ($user->roles as $role)
                                                <span class="badge bg-info text-dark">{{ $role->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $user->status == 'Aktif' ? 'success' : 'secondary' }}">
                                                {{ $user->status ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('users.edit', $user->id) }}"
                                                class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                class="d-inline form-hapus-user">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-danger btn-hapus-user">Hapus</button>
                                            </form>
                                            @push('scripts')
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        const hapusButtons = document.querySelectorAll('.btn-hapus-user');
                                                        hapusButtons.forEach(function(btn) {
                                                            btn.addEventListener('click', function(e) {
                                                                e.preventDefault();
                                                                let form = this.closest('form');
                                                                Swal.fire({
                                                                    title: 'Apakah Anda yakin?',
                                                                    text: "Data user akan dihapus!",
                                                                    icon: 'warning',
                                                                    showCancelButton: true,
                                                                    confirmButtonColor: '#3085d6',
                                                                    cancelButtonColor: '#d33',
                                                                    confirmButtonText: 'Ya, hapus!'
                                                                }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        form.submit();
                                                                    }
                                                                });
                                                            });
                                                        });
                                                    });
                                                </script>
                                            @endpush
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
