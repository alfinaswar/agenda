@extends('layouts.app')

@section('content')
    <style>
        /* Custom calendar cell style (B) */
        #custom-calendar .calendar-date {
            background-color: #f1f7fe;
            transition: background 0.2s, border 0.2s;
            border: 2px solid #b3c7e6;
            cursor: pointer;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(66, 133, 244, 0.05);

            /* BIAR SEMPURNA KOTAK */
            aspect-ratio: 1 / 1;
            height: 100%;
            padding: 4px;

            position: relative;
            overflow: hidden;
        }

        #custom-calendar .calendar-date:hover {
            background-color: #e0ecff;
            /* Biru lebih terang saat hover */
            border-color: #6699ee;
            box-shadow: 0 2px 8px rgba(66, 133, 244, 0.18);
        }

        #custom-calendar .calendar-date .fw-bold {
            color: #1a53aa;
            font-size: 1.085em;
            margin-bottom: 0.25em;
            margin-top: 2px;
        }

        #custom-calendar .calendar-date .agenda-items span.badge {
            background: linear-gradient(90deg, #1976d2 0%, #51a0fd 100%);
            color: #fff;
            font-weight: 500;
            font-size: 0.95em;
            border-radius: 6px;
        }

        #custom-calendar thead th {
            background-color: #e0ecff;
            color: #2166c5;
            border-bottom-width: 2px;
            border-top-right-radius: 0;
        }

        #custom-calendar .calendar-date[data-date]:active {
            background: #bdd6ff;
        }
    </style>
    <div class="page-header">
        <div class="row align-items-center w-100">
            <div class="col-lg-10 col-sm-12">
                <h3 class="page-title">Agenda</h3>
            </div>
            {{-- <div class="col-lg-2 col-sm-12 d-flex justify-content-end p-0">
                <a href="javascript:void(0);" class="btn btn-primary" id="btn-add-event">
                    Tambah Agenda
                </a>
            </div> --}}
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card bg-white">
                <div class="card-body">
                    <!-- Ganti Calendar: Simple Table Calendar -->
                    <div id="custom-calendar-wrapper">
                        <table class="table table-bordered text-center" id="custom-calendar">
                            <thead>
                                <tr>
                                    <th>Minggu</th>
                                    <th>Senin</th>
                                    <th>Selasa</th>
                                    <th>Rabu</th>
                                    <th>Kamis</th>
                                    <th>Jumat</th>
                                    <th>Sabtu</th>
                                </tr>
                            </thead>
                            <tbody id="calendar-body">
                                <!-- Calendar will be populated by JS -->
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-between mt-2">
                            <button id="prev-month" class="btn btn-outline-secondary btn-sm">Sebelumnya</button>
                            <span id="calendar-month-year" class="fw-bold"></span>
                            <button id="next-month" class="btn btn-outline-secondary btn-sm">Berikutnya</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card bg-white">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datanew table-striped align-middle dataTable" id="agenda-datatable">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Judul Agenda</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Jam Mulai</th>
                                    <th>Jam Selesai</th>
                                    <th>Lokasi</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($agendas as $index => $agenda)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $agenda->JudulAgenda }}</td>
                                        <td>{{ \Carbon\Carbon::parse($agenda->TanggalMulai)->format('d-m-Y') }}</td>
                                        <td>
                                            @if ($agenda->TanggalSelesai)
                                                {{ \Carbon\Carbon::parse($agenda->TanggalSelesai)->format('d-m-Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $agenda->JamMulai ?? '-' }}</td>
                                        <td>{{ $agenda->JamSelesai ?? '-' }}</td>
                                        <td>{{ $agenda->LokasiAgenda ?? '-' }}</td>
                                        <td>{{ $agenda->KategoriAgenda ?? '-' }}</td>
                                        <td>{{ $agenda->StatusAgenda ?? '-' }}</td>
                                        <td>
                                            <!-- Tombol aksi (misal: Detail/Edit/Hapus, implementasi lebih lanjut jika diinginkan) -->
                                            <button class="btn btn-outline-secondary btn-sm" disabled>Detail</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Belum ada data agenda.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit Agenda -->
    <div class="modal fade" id="modalAddAgenda" tabindex="-1" role="dialog" aria-labelledby="modalAddAgendaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <form id="agendaForm" enctype="multipart/form-data" action="{{ route('agenda.store') }}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAddAgendaLabel">Tambah Agenda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <label for="JudulAgenda" class="form-label">Judul Agenda<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="JudulAgenda" id="JudulAgenda" class="form-control" required
                                    placeholder="Masukkan judul agenda">
                            </div>
                            <div class="col-md-6">
                                <label for="KategoriAgenda" class="form-label">Kategori Agenda</label>
                                <input type="text" name="KategoriAgenda" id="KategoriAgenda" class="form-control"
                                    placeholder="Masukkan kategori agenda">
                            </div>
                            <div class="col-md-12">
                                <label for="DeskripsiAgenda" class="form-label">Deskripsi Agenda</label>
                                <textarea name="DeskripsiAgenda" id="DeskripsiAgenda" class="form-control" rows="2"
                                    placeholder="Masukkan deskripsi agenda"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="TanggalMulai" class="form-label">Tanggal Mulai<span
                                        class="text-danger">*</span></label>
                                <input type="date" name="TanggalMulai" id="TanggalMulai" class="form-control" required
                                    placeholder="Pilih tanggal mulai">
                            </div>
                            <div class="col-md-6">
                                <label for="TanggalSelesai" class="form-label">Tanggal Selesai</label>
                                <input type="date" name="TanggalSelesai" id="TanggalSelesai" class="form-control"
                                    placeholder="Pilih tanggal selesai">
                            </div>
                            <div class="col-md-6">
                                <label for="JamMulai" class="form-label">Jam Mulai</label>
                                <input type="time" name="JamMulai" id="JamMulai" class="form-control"
                                    placeholder="Pilih jam mulai">
                            </div>
                            <div class="col-md-6">
                                <label for="JamSelesai" class="form-label">Jam Selesai</label>
                                <input type="time" name="JamSelesai" id="JamSelesai" class="form-control"
                                    placeholder="Pilih jam selesai">
                            </div>
                            <div class="col-md-6">
                                <label for="LokasiAgenda" class="form-label">Lokasi Agenda</label>
                                <input type="text" name="LokasiAgenda" id="LokasiAgenda" class="form-control"
                                    placeholder="Masukkan lokasi agenda">
                            </div>
                            <div class="col-md-6">
                                <label for="TautanRapat" class="form-label">Tautan Rapat</label>
                                <input type="text" name="TautanRapat" id="TautanRapat" class="form-control"
                                    placeholder="Masukkan tautan rapat (bila ada)">
                            </div>
                            <div class="col-md-6">
                                <label for="StatusAgenda" class="form-label">Status Agenda</label>
                                <select name="StatusAgenda" id="StatusAgenda" class="form-control"
                                    placeholder="Pilih status agenda">
                                    <option value="Draft">Draft</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Disetujui">Disetujui</option>
                                    <option value="Selesai">Selesai</option>
                                    <option value="Dibatalkan">Dibatalkan</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="LampiranAgenda" class="form-label">Lampiran Agenda</label>
                                <input type="file" name="LampiranAgenda" id="LampiranAgenda" class="form-control"
                                    placeholder="Pilih lampiran (opsional)">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            Simpan Agenda
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('js')
        <script>
            const agendas = @json($agendas);

            function loadAgendaToCalendar() {
                agendas.forEach(a => {
                    let tanggal = a.TanggalMulai;

                    let agendaDiv = document.getElementById("agenda-" + tanggal);
                    if (agendaDiv) {
                        agendaDiv.innerHTML += `
                <span class="badge bg-primary d-block mb-1">
                    ${a.JudulAgenda}
                </span>
            `;
                    }
                });
            }
        </script>

        <script>
            // Calendar Indonesia (mulai Minggu, nama bulan & hari sesuai Indonesia, libur timezone Asia/Jakarta)
            document.addEventListener('DOMContentLoaded', function() {
                // Gunakan waktu lokal Asia/Jakarta
                function getTodayJakarta() {
                    // Ambil waktu sekarang dalam zona waktu Asia/Jakarta
                    let now = new Date();
                    // Offset manual ke WIB (UTC+7) jika browser client bukan WIB
                    // (catatan: Date JS di browser selalu UTC/Local, tdk bisa full TZ. Alternatif: intl API)
                    try {
                        return new Date(new Date().toLocaleString("en-US", {
                            timeZone: "Asia/Jakarta"
                        }));
                    } catch (e) {
                        return new Date();
                    }
                }

                let today = getTodayJakarta();
                let currentMonth = today.getMonth();
                let currentYear = today.getFullYear();

                const calendarBody = document.getElementById('calendar-body');
                const monthYearLabel = document.getElementById('calendar-month-year');
                const btnPrev = document.getElementById('prev-month');
                const btnNext = document.getElementById('next-month');

                // Nama bulan & hari Indonesia, sesuai urutan Minggu (0), Senin (1), dst.
                const monthNames = [
                    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                ];
                const dayNames = [
                    "Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"
                ];

                function renderCalendar(month, year) {
                    loadAgendaToCalendar();
                    calendarBody.innerHTML = "";

                    // Di Indonesia, minggu dimulai dari Minggu (0)
                    // Hari pertama di bulan di JavaScript: 0=Sunday/Minggu, 1=Senin,...6=Sabtu
                    const firstDay = (new Date(year, month, 1)).getDay();
                    const daysInMonth = 32 - new Date(year, month, 32).getDate();

                    let date = 1;
                    // Isi baris minggu (maksud baris minggu, row table)
                    for (let i = 0; i < 6; i++) {
                        let row = document.createElement('tr');
                        for (let j = 0; j < 7; j++) {
                            /*
                            j=0 = Minggu
                            j=1 = Senin
                            ...
                            */
                            // i === 0 (minggu pertama di bulan/hari): colspan hari kosong sebelum tanggal 1
                            if (i === 0 && j < firstDay) {
                                let cell = document.createElement('td');
                                cell.innerHTML = "";
                                row.appendChild(cell);
                            } else if (date > daysInMonth) {
                                let cell = document.createElement('td');
                                cell.innerHTML = "";
                                row.appendChild(cell);
                            } else {
                                let cell = document.createElement('td');
                                cell.classList.add('calendar-date');
                                cell.setAttribute('data-date', formatDate(year, month + 1, date));

                                cell.innerHTML =
                                    `<div class="fw-bold">${date}</div><div class="agenda-items" id="agenda-${formatDate(year, month + 1, date)}"></div>`;
                                row.appendChild(cell);
                                date++;
                            }
                        }
                        calendarBody.appendChild(row);
                        if (date > daysInMonth) break;
                    }
                    monthYearLabel.textContent = `${monthNames[month]} ${year}`;
                }

                function formatDate(y, m, d) {
                    return `${y}-${String(m).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                }

                function resetAndShowModalWithDate(dateStr) {
                    $('#agendaForm')[0].reset();
                    $('#TanggalMulai').val(dateStr);
                    $('#TanggalSelesai').val('');
                    $('#modalAddAgenda').modal('show');
                }

                // Click date to add new agenda
                calendarBody.addEventListener('click', function(e) {
                    let target = e.target;
                    while (target && !target.classList.contains('calendar-date') && target !== calendarBody) {
                        target = target.parentElement;
                    }
                    if (target && target.classList.contains('calendar-date')) {
                        let dateStr = target.getAttribute('data-date');
                        if (dateStr) {
                            resetAndShowModalWithDate(dateStr);
                        }
                    }
                });

                // Prev/Next month navigation
                btnPrev.addEventListener('click', function() {
                    currentMonth--;
                    if (currentMonth < 0) {
                        currentMonth = 11;
                        currentYear--;
                    }
                    renderCalendar(currentMonth, currentYear);
                    loadAgendaToCalendar();
                });

                btnNext.addEventListener('click', function() {
                    currentMonth++;
                    if (currentMonth > 11) {
                        currentMonth = 0;
                        currentYear++;
                    }
                    renderCalendar(currentMonth, currentYear);
                    loadAgendaToCalendar();
                });

                // Initial render
                renderCalendar(currentMonth, currentYear);
                loadAgendaToCalendar();
                // Add button click
                $('#btn-add-event').click(function() {
                    $('#agendaForm')[0].reset();
                    $('#modalAddAgenda').modal('show');
                });

            });
        </script>
    @endpush
@endsection
