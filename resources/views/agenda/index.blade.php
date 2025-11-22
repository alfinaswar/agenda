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
                <h3 class="page-title">Agenda</h3>
            </div>
            @can('create agenda')
                <div class="col-lg-2 col-sm-12 d-flex justify-content-end p-0">
                    <a href="javascript:void(0);" class="btn btn-primary" id="btn-add-event">
                        Tambah Agenda
                    </a>
                </div>
            @endcan
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
    @can('export agenda')
        <div class="row mt-4">
            <div class="col-12">
                <div class="card bg-light border-primary">
                    <div class="card-header bg-primary text-white fw-bold d-flex align-items-center justify-content-between">
                        <span>Rekap Agenda (Per Tanggal)</span>
                        <!-- Form download laporan -->

                        <form action="{{ route('agenda.download-rekap') }}" method="POST" class="d-flex align-items-center"
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
    @endcan
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
                                    {{-- <th>Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($agendas as $index => $agenda)
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
                                        {{-- <td>
                                            <button class="btn btn-outline-secondary btn-sm" disabled>Detail</button>
                                        </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit Agenda -->
    @can('create agenda')
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
                                    <input type="date" name="TanggalMulai" id="TanggalMulai" class="form-control"
                                        required placeholder="Pilih tanggal mulai">
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
    @endcan

    <!-- Modal Lihat Detail Agenda -->
    <div class="modal fade" id="modalAgendaDetail" tabindex="-1" aria-labelledby="modalAgendaDetailLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mb-0" id="modalAgendaDetailLabel">Detail Agenda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body py-4">
                    <dl class="row g-2 mb-0">
                        <dt class="col-sm-4 fw-semibold text-muted">Judul Agenda</dt>
                        <dd class="col-sm-8" id="detailJudul"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Kategori</dt>
                        <dd class="col-sm-8" id="detailKategori"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Deskripsi</dt>
                        <dd class="col-sm-8" id="detailDeskripsi"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Tanggal Mulai</dt>
                        <dd class="col-sm-8" id="detailTanggalMulai"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Tanggal Selesai</dt>
                        <dd class="col-sm-8" id="detailTanggalSelesai"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Jam Mulai</dt>
                        <dd class="col-sm-8" id="detailJamMulai"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Jam Selesai</dt>
                        <dd class="col-sm-8" id="detailJamSelesai"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Lokasi</dt>
                        <dd class="col-sm-8" id="detailLokasi"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Tautan Rapat</dt>
                        <dd class="col-sm-8" id="detailTautan"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Status</dt>
                        <dd class="col-sm-8" id="detailStatus"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Lampiran</dt>
                        <dd class="col-sm-8" id="detailLampiran"></dd>
                    </dl>
                </div>
                <div class="modal-footer d-flex justify-content-end">
                    @can('update agenda')
                        <button type="button" class="btn btn-warning btn-sm me-2" id="btnEditAgenda">
                            <i class="bi bi-pencil-square me-1"></i> Edit
                        </button>
                    @endcan
                    @can('delete agenda')
                        <button type="button" class="btn btn-danger btn-sm me-2" id="btnDeleteAgenda">
                            <i class="bi bi-trash me-1"></i> Hapus
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Lihat Detail Agenda -->

    @push('js')
        <script>
            const agendas = @json($agendas);
            let lastDetailAgendaId = null;

            // Render agenda items with clickable badges to calendar
            function loadAgendaToCalendar() {
                agendas.forEach(a => {
                    let tanggal = a.TanggalMulai;
                    let agendaDiv = document.getElementById("agenda-" + tanggal);
                    if (agendaDiv) {
                        let badgeId = "calendar-agenda-" + a.id;
                        // badge onclick untuk detail modal
                        agendaDiv.innerHTML += `
                           <span class="badge bg-primary d-block mb-1 calendar-agenda-badge"
                               data-agenda-id="${a.id}"
                               style="cursor:pointer"
                               id="${badgeId}"
                           >
                               ${a.JudulAgenda}
                           </span>
                       `;
                    }
                });
            }

            // Helper: show agenda detail in modal
            function showAgendaDetailModal(agenda) {
                $('#detailJudul').text(agenda.JudulAgenda ?? '-');
                $('#detailKategori').text(agenda.KategoriAgenda ?? '-');
                $('#detailDeskripsi').text(agenda.DeskripsiAgenda ?? '-');
                $('#detailTanggalMulai').text(agenda.TanggalMulai ? formatTanggalIndo(agenda.TanggalMulai) : '-');
                $('#detailTanggalSelesai').text(agenda.TanggalSelesai ? formatTanggalIndo(agenda.TanggalSelesai) : '-');
                $('#detailJamMulai').text(agenda.JamMulai ?? '-');
                $('#detailJamSelesai').text(agenda.JamSelesai ?? '-');
                $('#detailLokasi').text(agenda.LokasiAgenda ?? '-');
                $('#detailTautan').html((agenda.TautanRapat) ?
                    `<a href="${agenda.TautanRapat}" target="_blank">${agenda.TautanRapat}</a>` : '-');
                $('#detailStatus').text(agenda.StatusAgenda ?? '-');
                if (agenda.LampiranAgenda) {
                    let fileName = agenda.LampiranAgenda.split('/').pop();
                    $('#detailLampiran').html(`<a href="${agenda.LampiranAgenda}" target="_blank">${fileName}</a>`);
                } else {
                    $('#detailLampiran').text('-');
                }
                lastDetailAgendaId = agenda.id; // save id for edit/redirect/delete
                $('#modalAgendaDetail').modal('show');
            }

            // Helper convert Y-m-d ke d-m-Y
            function formatTanggalIndo(iso) {
                if (!iso) return '-';
                let p = iso.split('-');
                return p.length === 3 ? `${p[2]}-${p[1]}-${p[0]}` : iso;
            }

            // Event: Klik tombol Edit pada modal detail -> redirect ke halaman edit
            $(document).on('click', '#btnEditAgenda', function() {
                if (lastDetailAgendaId) {
                    // Laravel route: agenda.edit (pakai route)
                    window.location.href = "{{ route('agenda.edit', ['id' => ':id']) }}".replace(':id',
                        lastDetailAgendaId);
                }
            });


            $(document).on('click', '#btnDeleteAgenda', function() {
                if (lastDetailAgendaId) {
                    if (confirm('Apakah Anda yakin ingin menghapus agenda ini?')) {
                        // Gunakan AJAX agar error CSRF token bisa lebih jelas dideteksi
                        let token = $('meta[name="csrf-token"]').attr('content');
                        if (!token) {
                            alert('CSRF token tidak ditemukan. Silakan refresh halaman dan coba lagi.');
                            return;
                        }

                        $.ajax({
                            url: "{{ route('agenda.destroy', ['id' => ':id']) }}".replace(':id',
                                lastDetailAgendaId),
                            method: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: token
                            },
                            success: function(response) {
                                // reload halaman atau tampilkan pesan sukses
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
                                    alert('Terjadi kesalahan saat menghapus agenda.\nStatus: ' + xhr
                                        .status + '\nPesan: ' + (xhr.responseText || ''));
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
                const dayNames = [
                    "Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"
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
                    setTimeout(loadAgendaToCalendar, 10); // update agenda badges after table built
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
                    // 1. Cek apakah klik di dalam badge agenda
                    if (target.classList.contains('calendar-agenda-badge')) {
                        let agendaId = target.getAttribute('data-agenda-id');
                        if (agendaId) {
                            let found = agendas.find(a => String(a.id) === String(agendaId));
                            if (found) {
                                showAgendaDetailModal(found);
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
                            @can('create agenda')
                                resetAndShowModalWithDate(dateStr);
                            @endcan
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
                    $('#agendaForm')[0].reset();
                    $('#modalAddAgenda').modal('show');
                });
            });
        </script>
    @endpush
@endsection
