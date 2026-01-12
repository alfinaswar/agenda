@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Master Pegawai</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Master Pegawai</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col text-end">
            @can('master-pegawai-create')
                <a href="{{ route('master-pegawai.create') }}" class="btn btn-success">
                    Tambah Pegawai
                </a>
            @endcan
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0 text-white">Daftar Pegawai</h4>
                    <p class="card-text text-white-50 mb-0">
                        Tabel ini berisi semua data pegawai yang tersedia.
                    </p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datanew cell-border compact stripe" id="pegawaiTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>NIK</th>
                                    <th>Nama Peserta</th>
                                    <th>Asal Unit</th>
                                    <th>Gender</th>
                                    <th>Tanda Tangan</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pegawais as $index => $pegawai)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $pegawai->Nik }}</td>
                                        <td>{{ $pegawai->NamaPeserta }}</td>
                                        <td>{{ $pegawai->AsalUnit ?? '-' }}</td>
                                        <td>{{ $pegawai->Gender }}</td>
                                        <td>
                                            @if ($pegawai->TandaTangan)
                                                <img src="{{ asset('storage/' . $pegawai->TandaTangan) }}"
                                                    alt="Tanda Tangan" height="50">
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @can('master-pegawai-edit')
                                                <a href="{{ route('master-pegawai.edit', $pegawai->id) }}"
                                                    class="btn btn-sm btn-primary">Edit</a>
                                            @endcan
                                            @can('master-pegawai-delete')
                                                <form action="{{ route('master-pegawai.destroy', $pegawai->id) }}"
                                                    method="POST" class="d-inline form-hapus-pegawai">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger btn-hapus-pegawai">Hapus</button>
                                                </form>
                                                @push('scripts')
                                                    <script>
                                                        document.addEventListener('DOMContentLoaded', function() {
                                                            const hapusButtons = document.querySelectorAll('.btn-hapus-pegawai');
                                                            hapusButtons.forEach(function(btn) {
                                                                btn.addEventListener('click', function(e) {
                                                                    e.preventDefault();
                                                                    let form = this.closest('form');
                                                                    Swal.fire({
                                                                        title: 'Apakah Anda yakin?',
                                                                        text: "Data pegawai akan dihapus!",
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
                </div>
            </div>
        </div>
    </div>
@endsection
