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
                    <div class="mb-4">
                        <label class="form-label fw-bold">Metode Tambah Peserta</label>
                        <div class="d-flex gap-4 mt-2">
                            <div class="form-check">
                                <input class="form-check-input metode-peserta" type="radio" name="metode_peserta"
                                    id="metode_excel" value="excel" checked>
                                <label class="form-check-label" for="metode_excel">
                                    Upload Excel
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input metode-peserta" type="radio" name="metode_peserta"
                                    id="metode_event" value="event">
                                <label class="form-check-label" for="metode_event">
                                    Import dari Event Sebelumnya
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="form-excel">
                        <form action="{{ route('event.import-peserta') }}" method="POST" enctype="multipart/form-data"
                            class="mb-4">
                            @csrf
                            <input type="hidden" name="EventId" value="{{ $event->id ?? '' }}">

                            <div class="row align-items-end">
                                <div class="col-md-5 mb-2 mb-md-0">
                                    <label class="form-label fw-semibold mb-0">
                                        Upload Excel Peserta
                                    </label>
                                    <input type="file" name="file_excel" class="form-control" accept=".xlsx,.xls"
                                        required>
                                </div>
                                <div class="col-md-auto">
                                    <button type="submit" class="btn btn-info">
                                        <i class="bi bi-upload"></i> Upload Data Excel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="form-event" class="d-none mb-4">
                        <form action="{{ route('event.import-peserta-from-event') }}" method="POST">
                            @csrf
                            <input type="hidden" name="EventId" value="{{ $event->id ?? '' }}">

                            <div class="row align-items-end">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Pilih Event Sebelumnya
                                    </label>
                                    <select name="EventSumberId" class="form-select" required>
                                        <option value="">-- Pilih Event --</option>
                                        @foreach ($listEventSebelumnya as $evt)
                                            <option value="{{ $evt->id }}">
                                                {{ $evt->NamaEvent }}
                                                ({{ \Carbon\Carbon::parse($evt->TanggalMulai)->format('d-m-Y') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-auto">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bi bi-arrow-repeat"></i> Import Peserta
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>


                    <form action="{{ route('event.storePeserta') }}" method="POST" id="peserta-form"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="EventId" value="{{ $event->id ?? '' }}">

                        @php
                            $isInternal = isset($event) && strtolower($event->Jenis ?? '') === 'internal';
                        @endphp
                        <div class="table-responsive mb-3">
                            <table class="table table-striped align-middle dataTable" id="peserta-table">
                                <thead class="table-primary">
                                    <tr>
                                        <th style="width:380px">NIK <span class="text-danger">*</span></th>
                                        <th>Nama Peserta <span class="text-danger">*</span></th>
                                        <th>Asal Unit Kerja/Instansi <span class="text-danger">*</span></th>
                                        <th style="width:160px;">Jenis Kelamin <span class="text-danger">*</span></th>
                                        @if ($isInternal)
                                            <th style="width:50px">Tanda Tangan</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($event) && $event->getPeserta && count($event->getPeserta))
                                        @foreach ($event->getPeserta as $peserta)
                                            <tr>
                                                <td>
                                                    <select name="Nik[]" class="select2 form-select" required>
                                                        <option value="">Pilih Pegawai</option>
                                                        @foreach ($pegawai as $peg)
                                                            <option value="{{ $peg->Nik }}"
                                                                data-nama="{{ $peg->NamaPeserta }}"
                                                                data-asalunit="{{ $peg->AsalUnit }}"
                                                                data-gender="{{ $peg->Gender }}"
                                                                {{ $peserta->Nik == $peg->Nik ? 'selected' : '' }}>
                                                                {{ $peg->Nik }} - {{ $peg->NamaPeserta }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="NamaPeserta[]" class="form-control"
                                                        required placeholder="Nama Peserta"
                                                        value="{{ $peserta->NamaPeserta }}">
                                                </td>
                                                <td>
                                                    <input type="text" name="AsalUnit[]" class="form-control" required
                                                        placeholder="Asal Unit" value="{{ $peserta->AsalUnit }}">
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
                                                @if ($isInternal)
                                                    <td>
                                                        <input type="file" name="TandaTangan[]" class="form-control"
                                                            accept="image/*">
                                                        @if (!empty($peserta->TandaTangan))
                                                            <a href="{{ asset('storage/' . $peserta->TandaTangan) }}"
                                                                target="_blank">Lihat</a>
                                                        @endif
                                                    </td>
                                                @endif
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
                                                <select name="Nik[]" class="select2 form-select" required>
                                                    <option value="">Pilih Pegawai</option>
                                                    @foreach ($pegawai as $peg)
                                                        <option value="{{ $peg->Nik }}"
                                                            data-nama="{{ $peg->NamaPeserta }}"
                                                            data-asalunit="{{ $peg->AsalUnit }}"
                                                            data-gender="{{ $peg->Gender }}">
                                                            {{ $peg->Nik }} - {{ $peg->NamaPeserta }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="NamaPeserta[]" class="form-control" required
                                                    placeholder="Nama Peserta">
                                            </td>
                                            <td>
                                                <input type="text" name="AsalUnit[]" class="form-control" required
                                                    placeholder="Asal Unit">
                                            </td>
                                            <td>
                                                <select name="Gender[]" class="form-select" required>
                                                    <option value="">Pilih</option>
                                                    <option value="L">Laki-laki</option>
                                                    <option value="P">Perempuan</option>
                                                </select>
                                            </td>
                                            @if ($isInternal)
                                                <td>
                                                    <input type="file" name="TandaTangan[]" class="form-control"
                                                        accept="image/*">
                                                </td>
                                            @endif
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
            const isInternal = {{ $isInternal ? 'true' : 'false' }};

            // Inisialisasi Select2 untuk elemen yang sudah ada
            function initSelect2(element) {
                $(element).select2({
                    placeholder: "Pilih Pegawai",
                    allowClear: true,
                    width: '100%'
                });
            }

            // Inisialisasi semua select2 yang ada
            $('.select2').each(function() {
                initSelect2(this);
            });

            // Event handler untuk autofill data berdasarkan NIK
            function attachNikChangeHandler(selectElement) {
                $(selectElement).on('change', function() {
                    const selectedOption = $(this).find('option:selected');
                    const row = $(this).closest('tr');

                    if (selectedOption.val()) {
                        // Ambil data dari atribut option
                        const nama = selectedOption.data('nama');
                        const asalunit = selectedOption.data('asalunit');
                        const gender = selectedOption.data('gender');

                        // Isi field lainnya
                        row.find('input[name="NamaPeserta[]"]').val(nama || '');
                        row.find('input[name="AsalUnit[]"]').val(asalunit || '');
                        row.find('select[name="Gender[]"]').val(gender || '');
                    } else {
                        // Kosongkan jika tidak ada yang dipilih
                        row.find('input[name="NamaPeserta[]"]').val('');
                        row.find('input[name="AsalUnit[]"]').val('');
                        row.find('select[name="Gender[]"]').val('');
                    }
                });
            }

            // Attach handler untuk semua select NIK yang sudah ada
            $('select[name="Nik[]"]').each(function() {
                attachNikChangeHandler(this);
            });

            function updateRemoveButtons() {
                const trs = table.querySelectorAll("tr");
                trs.forEach((tr) => {
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

            // Event remove baris
            table.addEventListener("click", function(e) {
                if (e.target.closest(".remove-row")) {
                    const row = e.target.closest("tr");
                    if (table.querySelectorAll("tr").length > 1) {
                        // Destroy select2 sebelum remove row
                        $(row).find('.select2').select2('destroy');
                        row.parentNode.removeChild(row);
                        updateRemoveButtons();
                    }
                }
            });

            // Event tambah baris
            addBtn.addEventListener("click", function() {
                const newRow = document.createElement('tr');

                // Template untuk baris baru
                let rowHTML = `
            <td>
                <select name="Nik[]" class="form-select select2-dynamic" required>
                    <option value="">Pilih Pegawai</option>
                    @foreach ($pegawai as $peg)
                        <option value="{{ $peg->Nik }}"
                            data-nama="{{ $peg->NamaPeserta }}"
                            data-asalunit="{{ $peg->AsalUnit }}"
                            data-gender="{{ $peg->Gender }}">
                            {{ $peg->Nik }} - {{ $peg->NamaPeserta }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" name="NamaPeserta[]" class="form-control" required placeholder="Nama Peserta">
            </td>
            <td>
                <input type="text" name="AsalUnit[]" class="form-control" required placeholder="Asal Unit">
            </td>
            <td>
                <select name="Gender[]" class="form-select" required>
                    <option value="">Pilih</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </td>`;

                // Tambah kolom tanda tangan jika internal
                if (isInternal) {
                    rowHTML += `
            <td>
                <input type="file" name="TandaTangan[]" class="form-control" accept="image/*">
            </td>`;
                }

                rowHTML += `
            <td class="text-center align-middle">
                <button type="button" class="btn btn-danger btn-sm remove-row" title="Hapus Baris">
                    <i class="bi bi-dash"></i> -
                </button>
            </td>`;

                newRow.innerHTML = rowHTML;
                table.appendChild(newRow);

                // Inisialisasi select2 untuk baris baru
                const newSelect = newRow.querySelector('.select2-dynamic');
                initSelect2(newSelect);
                attachNikChangeHandler(newSelect);

                pesertaIdx++;
                updateRemoveButtons();

                $(newSelect).select2('open');
            });

            // Toggle form excel/event
            const excelForm = document.getElementById('form-excel');
            const eventForm = document.getElementById('form-event');

            document.querySelectorAll('.metode-peserta').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'excel') {
                        excelForm.classList.remove('d-none');
                        eventForm.classList.add('d-none');
                    } else {
                        excelForm.classList.add('d-none');
                        eventForm.classList.remove('d-none');
                    }
                });
            });

            // Saat page load
            updateRemoveButtons();
        });

        // SweetAlert untuk pesan sukses
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
            });
        @endif
    </script>
@endpush
