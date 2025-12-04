@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Tambah Peserta Event</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('event.index') }}">Event</a></li>
                    <li class="breadcrumb-item active">Tambah Peserta</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Tampilkan detail event --}}
    @isset($event)
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card border-primary mb-3">
                    <div class="card-header bg-dark">
                        <h4 class="card-title mb-0 text-white">Event</h4>
                    </div>
                    <div class="card-body p-3">
                        <div class="row mb-2">
                            <div class="col-md-2 fw-bold">Nama Event</div>
                            <div class="col-md-9">{{ $event->NamaEvent ?? '-' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2 fw-bold">Jenis</div>
                            <div class="col-md-9">{{ $event->Jenis ?? '-' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2 fw-bold">Deskripsi</div>
                            <div class="col-md-9">{{ $event->Deskripsi ?? '-' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2 fw-bold">Tanggal Mulai</div>
                            <div class="col-md-9">
                                {{ isset($event->TanggalMulai) ? \Carbon\Carbon::parse($event->TanggalMulai)->format('d-m-Y') : '-' }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2 fw-bold">Tanggal Selesai</div>
                            <div class="col-md-9">
                                {{ isset($event->TanggalSelesai) ? \Carbon\Carbon::parse($event->TanggalSelesai)->format('d-m-Y') : '-' }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2 fw-bold">Lokasi</div>
                            <div class="col-md-9">{{ $event->Lokasi ?? '-' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2 fw-bold">Keterangan</div>
                            <div class="col-md-9">{{ $event->Keterangan ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endisset

    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header bg-dark d-flex align-items-center justify-content-between flex-wrap">
                    <h4 class="card-title mb-0 text-white">Form Tambah Peserta</h4>
                    <div>
                        <!-- Download format excel template -->
                        <a href="{{ route('event.download-template-peserta') }}" class="btn btn-sm btn-success me-2">
                            <i class="bi bi-download"></i> Download Format Excel
                        </a>
                    </div>
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

                    <!-- Upload excel untuk import peserta -->
                    <form action="{{ route('event.import-peserta') }}" method="POST" enctype="multipart/form-data"
                        class="mb-4" id="form-import-peserta">
                        @csrf

                        <input type="hidden" name="EventId" value="{{ $event->id ?? '' }}">

                        <div class="row align-items-end">
                            <div class="col-md-5 mb-2 mb-md-0">
                                <label for="file_excel" class="form-label fw-semibold mb-0">Upload Excel Peserta</label>
                                <input type="file" name="file_excel" id="file_excel" class="form-control"
                                    accept=".xlsx,.xls" required>
                            </div>
                            <div class="col-md-auto">
                                <button type="submit" class="btn btn-info">
                                    <i class="bi bi-upload"></i> Upload Data Excel
                                </button>
                            </div>

                        </div>
                    </form>

                    <form action="{{ route('event.storePeserta') }}" method="POST" id="peserta-form">
                        @csrf
                        <input type="hidden" name="EventId" value="{{ $event->id ?? '' }}">

                        <div class="table-responsive mb-3">
                            <table class="table table-striped align-middle dataTable" id="peserta-table">
                                <thead class="table-primary">
                                    <tr>
                                        <th style="width:180px">NIK <span class="text-danger">*</span></th>
                                        <th>Nama Peserta <span class="text-danger">*</span></th>
                                        <th style="width:160px;">Jenis Kelamin <span class="text-danger">*</span></th>
                                        <th style="width:50px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($event) && $event->getPeserta && count($event->getPeserta))
                                        @foreach ($event->getPeserta as $peserta)
                                            <tr>
                                                <td>
                                                    <input type="text" name="Nik[]" class="form-control" required
                                                        placeholder="NIK Peserta" maxlength="16"
                                                        value="{{ $peserta->Nik }}">
                                                </td>
                                                <td>
                                                    <input type="text" name="NamaPeserta[]" class="form-control" required
                                                        placeholder="Nama Peserta" value="{{ $peserta->NamaPeserta }}">
                                                </td>
                                                <td>
                                                    <select name="Gender[]" class="form-select" required>
                                                        <option value="">Pilih</option>
                                                        <option value="L"
                                                            {{ $peserta->Gender == 'L' ? 'selected' : '' }}>Laki-laki
                                                        </option>
                                                        <option value="P"
                                                            {{ $peserta->Gender == 'P' ? 'selected' : '' }}>Perempuan
                                                        </option>
                                                    </select>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <button type="button" class="btn btn-danger btn-sm remove-row"
                                                        title="Hapus Baris">
                                                        <i class="bi bi-dash"></i> -
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>
                                                <input type="text" name="Nik[]" class="form-control" required
                                                    placeholder="NIK Peserta" maxlength="16">
                                            </td>
                                            <td>
                                                <input type="text" name="NamaPeserta[]" class="form-control" required
                                                    placeholder="Nama Peserta">
                                            </td>
                                            <td>
                                                <select name="Gender[]" class="form-select" required>
                                                    <option value="">Pilih</option>
                                                    <option value="L">Laki-laki</option>
                                                    <option value="P">Perempuan</option>
                                                </select>
                                            </td>
                                            <td class="text-center align-middle">
                                                <button type="button" class="btn btn-danger btn-sm remove-row"
                                                    title="Hapus Baris">
                                                    <i class="bi bi-dash"></i> -
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end mt-2">
                                <button type="button" class="btn btn-success btn-sm add-row">
                                    <i class="bi bi-plus"></i> Tambah Baris
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Peserta</button>
                        <a href="{{ route('event.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        // Tampilkan SweetAlert jika ada pesan sukses dari session (misal redirect setelah submit form)
        @if (session('success'))
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#3085d6',
                });
            });
        @endif
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let pesertaIdx = 1;
            const table = document.getElementById("peserta-table").getElementsByTagName('tbody')[0];
            const addBtn = document.querySelector(".add-row");

            function updateRemoveButtons() {
                // Tampilkan tombol remove jika baris > 1, jika cuma satu hidden
                const trs = table.querySelectorAll("tr");
                trs.forEach((tr, i) => {
                    const removeBtn = tr.querySelector(".remove-row");
                    if (removeBtn) {
                        if (trs.length > 1) {
                            removeBtn.classList.remove("d-none");
                        } else {
                            removeBtn.classList.add("d-none");
                        }
                    }
                });
            }

            // removeBaris dan tambahBaris logic
            table.addEventListener("click", function(e) {
                if (e.target.closest(".remove-row")) {
                    const row = e.target.closest("tr");
                    if (table.querySelectorAll("tr").length > 1) {
                        row.parentNode.removeChild(row);
                        updateRemoveButtons();
                    }
                }
            });

            addBtn.addEventListener("click", function() {
                const trs = table.querySelectorAll("tr");
                const row = trs[trs.length - 1];
                const newRow = row.cloneNode(true);

                // Kosongkan isi input
                newRow.querySelectorAll("input, select").forEach(function(input) {
                    if (input.tagName.toLowerCase() === 'input') {
                        input.value = '';
                    }
                    if (input.tagName.toLowerCase() === 'select') {
                        input.value = '';
                    }
                });
                table.appendChild(newRow);
                pesertaIdx++;
                updateRemoveButtons();
                // Autofocus NIK pada row baru
                newRow.querySelector("input[placeholder='NIK Peserta']").focus();
            });

            // Saat page load
            updateRemoveButtons();
        });
    </script>
@endpush
