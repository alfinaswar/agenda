@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Master Role</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Master Role</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col text-end">
            @can('role-create')
                <a href="{{ route('roles.create') }}" class="btn btn-success">
                    Tambah Role
                </a>
            @endcan
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0 text-white">Daftar Role</h4>
                    <p class="card-text text-white-50 mb-0">
                        Tabel ini berisi semua data role yang tersedia.
                    </p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datanew cell-border compact stripe" id="roleTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $index => $role)
                                    <tr>
                                        <td>{{ ($roles->currentPage() - 1) * $roles->perPage() + $index + 1 }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>

                                            @can('role-edit')
                                                <a href="{{ route('roles.edit', $role->id) }}"
                                                    class="btn btn-sm btn-primary">Edit</a>
                                            @endcan
                                            @can('role-delete')
                                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                    class="d-inline form-hapus-role">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger btn-hapus-role">Hapus</button>
                                                </form>
                                                @push('scripts')
                                                    <script>
                                                        document.addEventListener('DOMContentLoaded', function() {
                                                            const hapusButtons = document.querySelectorAll('.btn-hapus-role');
                                                            hapusButtons.forEach(function(btn) {
                                                                btn.addEventListener('click', function(e) {
                                                                    e.preventDefault();
                                                                    let form = this.closest('form');
                                                                    Swal.fire({
                                                                        title: 'Apakah Anda yakin?',
                                                                        text: "Data role akan dihapus!",
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
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {!! $roles->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
