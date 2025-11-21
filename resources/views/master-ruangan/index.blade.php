@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Master Ruangan</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Master Ruangan</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col text-end">
            <a href="{{ route('master-ruangan.create') }}" class="btn btn-success">
                Tambah Ruangan
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0 text-white">Daftar Ruangan</h4>
                    <p class="card-text text-white-50 mb-0">
                        Tabel ini berisi semua data ruangan yang tersedia.
                    </p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datanew cell-border compact stripe" id="ruanganTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Ruangan</th>
                                    <th>Kapasitas</th>
                                    <th>Lokasi</th>
                                    <th>Fasilitas</th>
                                    <th>Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rooms as $index => $room)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $room->NamaRuangan }}</td>
                                        <td>{{ $room->Kapasitas ?? '-' }}</td>
                                        <td>{{ $room->Lokasi ?? '-' }}</td>
                                        <td>{{ $room->Fasilitas ?? '-' }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $room->StatusRuangan == 'Aktif' ? 'success' : 'secondary' }}">
                                                {{ $room->StatusRuangan }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('master-ruangan.edit', $room->id) }}"
                                                class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('master-ruangan.destroy', $room->id) }}" method="POST"
                                                class="d-inline form-hapus-ruangan">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-danger btn-hapus-ruangan">Hapus</button>
                                            </form>
                                            @push('scripts')
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        const hapusButtons = document.querySelectorAll('.btn-hapus-ruangan');
                                                        hapusButtons.forEach(function(btn) {
                                                            btn.addEventListener('click', function(e) {
                                                                e.preventDefault();
                                                                let form = this.closest('form');
                                                                Swal.fire({
                                                                    title: 'Apakah Anda yakin?',
                                                                    text: "Data ruangan akan dihapus!",
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
