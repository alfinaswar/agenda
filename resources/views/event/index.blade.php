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
            aspect-ratio: 1 / 1;
            height: 100%;
            padding: 4px;
            position: relative;
            overflow: hidden;
        }

        #custom-calendar .calendar-date:hover {
            background-color: #e0ecff;
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
            cursor: pointer;
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
                <h3 class="page-title">Event</h3>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card bg-white">
                <div class="card-body">
                    <!-- Calendar for Events -->
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
    {{-- @can('event-laporan') --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="card bg-light border-primary">
                    <div class="card-header bg-primary text-white fw-bold d-flex align-items-center justify-content-between">
                        <span>Rekap Event (Filter Periode Tanggal)</span>
                        <!-- Form download laporan -->
                        <form action="{{ route('event.download-rekap') }}" method="POST" class="d-flex align-items-center"
                            target="_blank">
                            @csrf
                            <div class="me-2">
                                <input type="date" name="TanggalAwal" class="form-control form-control-md" required>
                            </div>
                            <div class="me-2">
                                <input type="date" name="TanggalAkhir" class="form-control form-control-md" required>
                            </div>
                            <button type="submit" class="btn btn-success btn-md">
                                <i class="bi bi-download me-1"></i> Download Laporan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    {{-- @endcan --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card bg-white">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datanew table-striped align-middle dataTable" id="event-datatable">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Jenis</th>
                                    <th>Nama Event</th>
                                    <th>Deskripsi</th>
                                    <th>Keterangan</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Lokasi</th>
                                    <th>Dibuat Oleh</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $index => $event)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $event->Jenis ?? '-' }}</td>
                                        <td>{{ $event->NamaEvent ?? '-' }}</td>
                                        <td>{{ $event->Deskripsi ?? '-' }}</td>
                                        <td>{{ $event->Keterangan ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($event->TanggalMulai)->format('d-m-Y') }}</td>
                                        <td>
                                            @if ($event->TanggalSelesai)
                                                {{ \Carbon\Carbon::parse($event->TanggalSelesai)->format('d-m-Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $event->Lokasi ?? '-' }}</td>
                                        <td>{{ $event->UserCreate->name ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('event.peserta', $event->id) }}"
                                                class="btn btn-sm btn-primary mt-2">
                                                <i class="bi bi-person-plus"></i> Tambah Peserta
                                            </a>
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

    <!-- Modal Tambah/Edit Event -->
    <div class="modal fade" id="modalAddEvent" tabindex="-1" role="dialog" aria-labelledby="modalAddEventLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <form id="eventForm" enctype="multipart/form-data" action="{{ route('event.store') }}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAddEventLabel">Tambah Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <label for="Jenis" class="form-label">Jenis<span class="text-danger">*</span></label>
                                <select name="Jenis" id="Jenis" class="form-control" required>
                                    <option value="">Pilih jenis event</option>
                                    <option value="Internal">Internal</option>
                                    <option value="Eksternal">Eksternal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="NamaEvent" class="form-label">Nama Event<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="NamaEvent" id="NamaEvent" class="form-control" required
                                    placeholder="Masukkan nama event">
                            </div>
                            <div class="col-md-12">
                                <label for="Deskripsi" class="form-label">Deskripsi</label>
                                <textarea name="Deskripsi" id="Deskripsi" class="form-control" rows="2" placeholder="Masukkan deskripsi event"></textarea>
                            </div>
                            <div class="col-md-12">
                                <label for="Keterangan" class="form-label">Keterangan</label>
                                <input type="text" name="Keterangan" id="Keterangan" class="form-control"
                                    placeholder="Masukkan keterangan event">
                            </div>
                            <div class="col-md-6">
                                <label for="TanggalMulai" class="form-label">Tanggal Mulai<span
                                        class="text-danger">*</span></label>
                                <input type="date" name="TanggalMulai" id="TanggalMulai" class="form-control"
                                    required placeholder="Pilih tanggal mulai">
                            </div>
                            <div class="col-md-6">
                                <label for="TanggalSelesai" class="form-label">Tanggal Selesai</label>
                                <input type="date" name="TanggalSelesai" id="TanggalSelesai" class="form-control"
                                    placeholder="Pilih tanggal selesai">
                            </div>
                            <div class="col-md-12">
                                <label for="Lokasi" class="form-label">Lokasi</label>
                                <input type="text" name="Lokasi" id="Lokasi" class="form-control"
                                    placeholder="Masukkan lokasi event">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            Simpan Event
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal Lihat Detail Event -->
    <div class="modal fade" id="modalEventDetail" tabindex="-1" aria-labelledby="modalEventDetailLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mb-0" id="modalEventDetailLabel">Detail Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body py-4">
                    <dl class="row g-2 mb-0">
                        <dt class="col-sm-4 fw-semibold text-muted">Jenis</dt>
                        <dd class="col-sm-8" id="detailJenis"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Nama Event</dt>
                        <dd class="col-sm-8" id="detailNamaEvent"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Deskripsi</dt>
                        <dd class="col-sm-8" id="detailDeskripsi"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Keterangan</dt>
                        <dd class="col-sm-8" id="detailKeterangan"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Tanggal Mulai</dt>
                        <dd class="col-sm-8" id="detailTanggalMulai"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Tanggal Selesai</dt>
                        <dd class="col-sm-8" id="detailTanggalSelesai"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Lokasi</dt>
                        <dd class="col-sm-8" id="detailLokasi"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Dibuat Oleh</dt>
                        <dd class="col-sm-8" id="detailUserCreate"></dd>
                    </dl>
                </div>
                <div class="modal-footer d-flex justify-content-end">
                    @can('event-edit')
                        <button type="button" class="btn btn-warning btn-sm me-2" id="btnEditEvent">
                            <i class="bi bi-pencil-square me-1"></i> Edit
                        </button>
                    @endcan
                    @can('event-delete')
                        <button type="button" class="btn btn-danger btn-sm me-2" id="btnDeleteEvent">
                            <i class="bi bi-trash me-1"></i> Hapus
                        </button>
                    @endcan

                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Lihat Detail Event -->

    @push('js')
        <script>
            const events = @json($events);
            let lastDetailEventId = null;

            function loadEventToCalendar() {
                events.forEach(e => {
                    let tanggal = e.TanggalMulai;
                    let eventDiv = document.getElementById("agenda-" + tanggal);
                    if (eventDiv) {
                        let badgeId = "calendar-event-" + e.id;
                        // badge onclick untuk detail modal
                        eventDiv.innerHTML += `
                           <span class="badge bg-primary d-block mb-1 calendar-event-badge"
                               data-event-id="${e.id}"
                               style="cursor:pointer"
                               id="${badgeId}"
                           >
                               ${e.NamaEvent}
                           </span>
                       `;
                    }
                });
            }

            // Helper: show event detail in modal
            function showEventDetailModal(event) {
                $('#detailJenis').text(event.Jenis ?? '-');
                $('#detailNamaEvent').text(event.NamaEvent ?? '-');
                $('#detailDeskripsi').text(event.Deskripsi ?? '-');
                $('#detailKeterangan').text(event.Keterangan ?? '-');
                $('#detailTanggalMulai').text(event.TanggalMulai ? formatTanggalIndo(event.TanggalMulai) : '-');
                $('#detailTanggalSelesai').text(event.TanggalSelesai ? formatTanggalIndo(event.TanggalSelesai) : '-');
                $('#detailLokasi').text(event.Lokasi ?? '-');
                $('#detailUserCreate').text(event.UserCreate && event.UserCreate.name ? event.UserCreate.name : '-');
                lastDetailEventId = event.id;
                $('#modalEventDetail').modal('show');
            }

            // Helper convert Y-m-d ke d-m-Y
            function formatTanggalIndo(iso) {
                if (!iso) return '-';
                let p = iso.split('-');
                return p.length === 3 ? `${p[2]}-${p[1]}-${p[0]}` : iso;
            }

            // Event: Klik tombol Edit pada modal detail -> redirect ke halaman edit
            $(document).on('click', '#btnEditEvent', function() {
                if (lastDetailEventId) {
                    window.location.href = "{{ route('event.edit', ['id' => ':id']) }}".replace(':id',
                        lastDetailEventId);
                }
            });

            // Event: Klik tombol Delete pada modal detail
            $(document).on('click', '#btnDeleteEvent', function() {
                if (lastDetailEventId) {
                    if (confirm('Apakah Anda yakin ingin menghapus event ini?')) {
                        let token = $('meta[name="csrf-token"]').attr('content');
                        if (!token) {
                            alert('CSRF token tidak ditemukan. Silakan refresh halaman dan coba lagi.');
                            return;
                        }
                        $.ajax({
                            url: "{{ route('event.destroy', ['id' => ':id']) }}".replace(':id',
                                lastDetailEventId),
                            method: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: token
                            },
                            success: function(response) {
                                location.reload();
                            },
                            error: function(xhr) {
                                if (xhr.status === 419) {
                                    alert(
                                        'Session telah kadaluarsa atau CSRF token tidak valid. Silakan refresh halaman.'
                                    );
                                } else if (xhr.status === 403) {
                                    alert('Aksi ini tidak diizinkan. Silakan login ulang.');
                                } else {
                                    alert('Terjadi kesalahan saat menghapus event.\nStatus: ' + xhr.status +
                                        '\nPesan: ' + (xhr.responseText || ''));
                                }
                            }
                        });
                    }
                }
            });
        </script>

        <script>
            // Calendar Indonesia (mulai Minggu, nama bulan & hari sesuai Indonesia, libur timezone Asia/Jakarta)
            document.addEventListener('DOMContentLoaded', function() {
                // Gunakan waktu lokal Asia/Jakarta
                function getTodayJakarta() {
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

                const monthNames = [
                    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                ];

                function renderCalendar(month, year) {
                    calendarBody.innerHTML = "";
                    const firstDay = (new Date(year, month, 1)).getDay();
                    const daysInMonth = 32 - new Date(year, month, 32).getDate();

                    let date = 1;
                    for (let i = 0; i < 6; i++) {
                        let row = document.createElement('tr');
                        for (let j = 0; j < 7; j++) {
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
                    setTimeout(loadEventToCalendar, 10); // update event badges after table built
                }

                function formatDate(y, m, d) {
                    return `${y}-${String(m).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                }

                function resetAndShowModalWithDate(dateStr) {
                    $('#eventForm')[0].reset();
                    $('#TanggalMulai').val(dateStr);
                    $('#TanggalSelesai').val('');
                    $('#modalAddEvent').modal('show');
                }

                // Click date to add new event
                calendarBody.addEventListener('click', function(e) {
                    let target = e.target;
                    // 1. Cek apakah klik di dalam badge event
                    if (target.classList.contains('calendar-event-badge')) {
                        let eventId = target.getAttribute('data-event-id');
                        if (eventId) {
                            let found = events.find(ev => String(ev.id) === String(eventId));
                            if (found) {
                                showEventDetailModal(found);
                                return;
                            }
                        }
                    }
                    // 2. Jika klik di cell kosong, buka modal tambah seperti biasa
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

                // Navigation calendar
                btnPrev.addEventListener('click', function() {
                    currentMonth--;
                    if (currentMonth < 0) {
                        currentMonth = 11;
                        currentYear--;
                    }
                    renderCalendar(currentMonth, currentYear);
                });

                btnNext.addEventListener('click', function() {
                    currentMonth++;
                    if (currentMonth > 11) {
                        currentMonth = 0;
                        currentYear++;
                    }
                    renderCalendar(currentMonth, currentYear);
                });

                // Initial render
                renderCalendar(currentMonth, currentYear);

                $('#btn-add-event').click(function() {
                    $('#eventForm')[0].reset();
                    $('#modalAddEvent').modal('show');
                });
            });
        </script>
    @endpush
@endsection
