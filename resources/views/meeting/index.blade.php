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

        #custom-calendar .calendar-date .meeting-items span.badge {
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
                <h3 class="page-title">Booking Ruangan Meeting</h3>
            </div>
            <div class="col-lg-2 col-sm-12 d-flex justify-content-end p-0">
                @can('meeting-create')
                    <a href="javascript:void(0);" class="btn btn-primary" id="btn-add-meeting">
                        Booking Meeting
                    </a>
                @endcan
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card bg-white">
                <div class="card-body">
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
    <!-- REKAP BOOKING RUANGAN MEETING (PERTANGGAL & PER RUANGAN) -->
    @can('meeting-laporan')
        <div class="row mt-4">
            <div class="col-12">
                <div class="card bg-light border-primary">
                    <div class="card-header bg-primary text-white fw-bold d-flex align-items-center justify-content-between">
                        <span>Rekap Booking Ruangan Meeting (Per Tanggal dan Per Ruangan)</span>
                        <!-- Form download laporan -->
                        <form action="{{ route('meeting.download-rekap') }}" method="POST" class="d-flex align-items-center"
                            target="_blank">
                            @csrf
                            <div class="me-2">
                                <select name="Ruangan" class="form-select form-select-sm" required>
                                    <option value="Semua">Semua Ruangan</option>
                                    @foreach ($MasterRuangan as $ruangan)
                                        <option value="{{ $ruangan->id }}">{{ $ruangan->NamaRuangan }}</option>
                                    @endforeach
                                </select>
                            </div>
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
    <!-- END REKAP BOOKING RUANGAN MEETING -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card bg-white">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datanew table-striped align-middle dataTable" id="meeting-datatable">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Ruangan</th>
                                    <th>Judul Meeting</th>
                                    <th>Deskripsi</th>
                                    <th>Tanggal</th>
                                    <th>Jam Mulai</th>
                                    <th>Jam Selesai</th>
                                    <th>Durasi (Menit)</th>
                                    <th>Tautan Meeting</th>
                                    <th>Lampiran</th>
                                    <th>User Pemesan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($meetings as $index => $meeting)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $meeting->getRuangan->NamaRuangan ?? '-' }}</td>
                                        <td>{{ $meeting->JudulMeeting }}</td>
                                        <td>{{ $meeting->DeskripsiMeeting ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($meeting->Tanggal)->format('d-m-Y') }}</td>
                                        <td>{{ $meeting->JamMulai ?? '-' }}</td>
                                        <td>{{ $meeting->JamSelesai ?? '-' }}</td>
                                        <td>{{ $meeting->DurasiMenit ?? '-' }}</td>
                                        <td>
                                            @if ($meeting->TautanMeeting)
                                                <a href="{{ $meeting->TautanMeeting }}" target="_blank"
                                                    class="text-primary">
                                                    {{ $meeting->TautanMeeting }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($meeting->LampiranAgenda)
                                                @php
                                                    $filename = basename($meeting->LampiranAgenda);
                                                @endphp
                                                <a href="{{ $meeting->LampiranAgenda }}" target="_blank">
                                                    {{ $filename }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $meeting->UserCreate ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit Booking Meeting -->
    @can('meeting-create')
        <div class="modal fade" id="modalAddMeeting" tabindex="-1" role="dialog" aria-labelledby="modalAddMeetingLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <form id="meetingForm" enctype="multipart/form-data" action="{{ route('meeting.store') }}" method="post">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalAddMeetingLabel">Booking Ruangan Meeting</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3 mb-2">
                                <div class="col-md-6">
                                    <label for="Ruangan" class="form-label">Ruangan<span class="text-danger">*</span></label>
                                    <select name="Ruangan" id="Ruangan" class="form-control select2" required>
                                        <option value="" disabled selected>Pilih Ruangan</option>
                                        @foreach ($MasterRuangan as $ruangan)
                                            <option value="{{ $ruangan->id }}">{{ $ruangan->NamaRuangan }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-md-6">
                                    <label for="JudulMeeting" class="form-label">Judul Meeting<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="JudulMeeting" id="JudulMeeting" class="form-control" required
                                        placeholder="Masukkan judul meeting">
                                </div>
                                <div class="col-md-12">
                                    <label for="DeskripsiMeeting" class="form-label">Deskripsi Meeting</label>
                                    <textarea name="DeskripsiMeeting" id="DeskripsiMeeting" class="form-control" rows="2"
                                        placeholder="Masukkan deskripsi meeting"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="Tanggal" class="form-label">Tanggal<span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="Tanggal" id="Tanggal" class="form-control" required
                                        placeholder="Pilih tanggal meeting">
                                </div>
                                <div class="col-md-3">
                                    <label for="JamMulai" class="form-label">Jam Mulai<span
                                            class="text-danger">*</span></label>
                                    <input type="time" name="JamMulai" id="JamMulai" class="form-control" required
                                        placeholder="Pilih jam mulai" onchange="hitungDurasi()">
                                </div>
                                <div class="col-md-3">
                                    <label for="JamSelesai" class="form-label">Jam Selesai<span
                                            class="text-danger">*</span></label>
                                    <input type="time" name="JamSelesai" id="JamSelesai" class="form-control" required
                                        placeholder="Pilih jam selesai" onchange="hitungDurasi()">
                                </div>
                                <div class="col-md-4">
                                    <label for="DurasiMenit" class="form-label">Durasi (Menit)</label>
                                    <input type="number" name="DurasiMenit" id="DurasiMenit" min="1"
                                        class="form-control" placeholder="Hitung otomatis" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label for="TautanMeeting" class="form-label">Tautan Meeting</label>
                                    <input type="text" name="TautanMeeting" id="TautanMeeting" class="form-control"
                                        placeholder="Masukkan tautan (opsional)">
                                </div>
                                <div class="col-md-4">
                                    <label for="LampiranAgenda" class="form-label">Lampiran Meeting</label>
                                    <input type="file" name="LampiranAgenda" id="LampiranAgenda" class="form-control"
                                        placeholder="Pilih lampiran (opsional)">
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">
                                Simpan Booking
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

    <!-- Modal Lihat Detail Booking Meeting -->
    <div class="modal fade" id="modalMeetingDetail" tabindex="-1" aria-labelledby="modalMeetingDetailLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mb-0" id="modalMeetingDetailLabel">Detail Booking Ruangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body py-4">
                    <dl class="row g-2 mb-0">
                        <dt class="col-sm-4 fw-semibold text-muted">Ruangan</dt>
                        <dd class="col-sm-8" id="detailRuangan"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Judul Meeting</dt>
                        <dd class="col-sm-8" id="detailJudulMeeting"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Deskripsi</dt>
                        <dd class="col-sm-8" id="detailDeskripsiMeeting"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Tanggal</dt>
                        <dd class="col-sm-8" id="detailTanggal"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Jam Mulai</dt>
                        <dd class="col-sm-8" id="detailJamMulai"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Jam Selesai</dt>
                        <dd class="col-sm-8" id="detailJamSelesai"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Durasi (Menit)</dt>
                        <dd class="col-sm-8" id="detailDurasiMenit"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Tautan Meeting</dt>
                        <dd class="col-sm-8" id="detailTautanMeeting"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">Lampiran</dt>
                        <dd class="col-sm-8" id="detailLampiranMeeting"></dd>

                        <dt class="col-sm-4 fw-semibold text-muted">User Pemesan</dt>
                        <dd class="col-sm-8" id="detailUserCreate"></dd>
                    </dl>
                </div>
                <div class="modal-footer d-flex justify-content-end">
                    @can('meeting-edit')
                        <button type="button" class="btn btn-warning btn-sm me-2" id="btnEditMeeting">
                            <i class="bi bi-pencil-square me-1"></i> Edit
                        </button>
                    @endcan
                    @can('meeting-delete')
                        <button type="button" class="btn btn-danger btn-sm me-2" id="btnDeleteMeeting">
                            <i class="bi bi-trash me-1"></i> Hapus
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Lihat Detail Booking Meeting -->

    @push('js')
        <script>
            $(document).ready(function() {
                $('#Ruangan').select2({
                    dropdownParent: $('#modalAddMeeting'),
                    placeholder: "Pilih Ruangan",
                    width: '100%'
                });
                // Jika ada error booking bentrok, tampilkan SweetAlert
                @if ($errors->has('JamMulai'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Booking Bentrok',
                        text: '{{ $errors->first('JamMulai') }}',
                        confirmButtonColor: '#3085d6'
                    });
                @endif
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses',
                        text: '{{ session('success') }}',
                        confirmButtonColor: '#3085d6'
                    });
                @endif
            });
        </script>
        <script>
            function hitungDurasi() {
                const jamMulai = document.getElementById('JamMulai').value;
                const jamSelesai = document.getElementById('JamSelesai').value;
                const inputDurasi = document.getElementById('DurasiMenit');
                if (jamMulai && jamSelesai) {
                    const [mulaiJam, mulaiMenit] = jamMulai.split(':').map(Number);
                    const [selesaiJam, selesaiMenit] = jamSelesai.split(':').map(Number);
                    let menitMulai = mulaiJam * 60 + mulaiMenit;
                    let menitSelesai = selesaiJam * 60 + selesaiMenit;
                    let durasi = menitSelesai - menitMulai;
                    if (durasi < 0) {
                        durasi = 0;
                    }
                    inputDurasi.value = durasi;
                } else {
                    inputDurasi.value = '';
                }
            }
        </script>
        <script>
            const meetings = @json($meetings ?? []);
            let lastDetailMeetingId = null;

            function loadMeetingToCalendar() {
                meetings.forEach(m => {
                    let tanggal = m.Tanggal;
                    let meetingDiv = document.getElementById("meeting-" + tanggal);
                    if (meetingDiv) {
                        let badgeId = "calendar-meeting-" + m.id;
                        meetingDiv.innerHTML += `
                        <span class="badge bg-primary d-block mb-1 calendar-meeting-badge"
                            data-meeting-id="${m.id}"
                            style="cursor:pointer"
                            id="${badgeId}"
                        >
                            ${m.JudulMeeting}
                            <br>
                            <small>
                                ${m.get_ruangan?.NamaRuangan ?? ''}
                                ${m.JamMulai && m.JamSelesai
                                    ? `<br><span class='text-secondary'>${m.JamMulai} - ${m.JamSelesai}</span>`
                                    : ''}
                            </small>
                        </span>
                    `;
                    }
                });
            }

            // Helper: show meeting detail in modal
            function showMeetingDetailModal(meeting) {
                $('#detailRuangan').text(meeting.get_ruangan && meeting.get_ruangan.NamaRuangan ? meeting.get_ruangan
                    .NamaRuangan : '-');
                $('#detailJudulMeeting').text(meeting.JudulMeeting ?? '-');
                $('#detailDeskripsiMeeting').text(meeting.DeskripsiMeeting ?? '-');
                $('#detailTanggal').text(meeting.Tanggal ? formatTanggalIndo(meeting.Tanggal) : '-');
                $('#detailJamMulai').text(meeting.JamMulai ?? '-');
                $('#detailJamSelesai').text(meeting.JamSelesai ?? '-');
                $('#detailDurasiMenit').text(meeting.DurasiMenit ?? '-');
                $('#detailTautanMeeting').html((meeting.TautanMeeting) ?
                    `<a href="${meeting.TautanMeeting}" target="_blank">${meeting.TautanMeeting}</a>` : '-');
                if (meeting.LampiranAgenda) {
                    let fileName = meeting.LampiranAgenda.split('/').pop();
                    $('#detailLampiranMeeting').html(`<a href="${meeting.LampiranAgenda}" target="_blank">${fileName}</a>`);
                } else {
                    $('#detailLampiranMeeting').text('-');
                }
                $('#detailUserCreate').text(meeting.UserCreate ?? '-');
                lastDetailMeetingId = meeting.id;
                // Set data-meeting-id di modal untuk referensi
                $('#modalMeetingDetail').data('meeting-id', meeting.id);
                $('#btnEditMeeting').data('meeting-id', meeting.id);
                $('#btnDeleteMeeting').data('meeting-id', meeting.id);
                $('#modalMeetingDetail').modal('show');
            }

            // Helper: convert Y-m-d ke d-m-Y
            function formatTanggalIndo(iso) {
                if (!iso) return '-';
                let p = iso.split('-');
                return p.length === 3 ? `${p[2]}-${p[1]}-${p[0]}` : iso;
            }

            // --- FIX meeting ID not found for Edit button ---
            $(document).off('click', '#btnEditMeeting').on('click', '#btnEditMeeting', function(e) {
                $('#modalMeetingDetail').modal('hide');
                // Ambil dari data-meeting-id di tombol (priority),
                // kalau tidak ada, coba dari modal, lalu terakhir global lastDetailMeetingId
                var meetingId = $(this).data('meeting-id');
                if (!meetingId) {
                    meetingId = $('#modalMeetingDetail').data('meeting-id');
                }
                if (!meetingId) {
                    meetingId = lastDetailMeetingId;
                }
                if (!meetingId) {
                    alert('ID meeting tidak ditemukan!');
                    return;
                }
                setTimeout(function() {
                    window.location.href = "{{ route('meeting.edit', ['id' => ':id']) }}".replace(':id',
                        meetingId);
                }, 250);
            });

            // Event: Klik tombol Hapus pada modal detail
            $(document).off('click', '#btnDeleteMeeting').on('click', '#btnDeleteMeeting', function() {
                // Ambil dari data-meeting-id tombol hapus, modal, atau lastDetailMeetingId
                var meetingId = $(this).data('meeting-id');
                if (!meetingId) {
                    meetingId = $('#modalMeetingDetail').data('meeting-id');
                }
                if (!meetingId) {
                    meetingId = lastDetailMeetingId;
                }
                if (meetingId) {
                    if (confirm('Apakah Anda yakin ingin menghapus booking meeting ini?')) {
                        let token = $('meta[name="csrf-token"]').attr('content');
                        if (!token) {
                            alert('CSRF token tidak ditemukan. Silakan refresh halaman dan coba lagi.');
                            return;
                        }
                        $.ajax({
                            url: "{{ route('meeting.destroy', ['id' => ':id']) }}".replace(':id', meetingId),
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
                                    alert('Terjadi kesalahan saat menghapus booking.\nStatus: ' + xhr
                                        .status + '\nPesan: ' + (xhr.responseText || ''));
                                }
                            }
                        });
                    }
                }
            });
        </script>

        <script>
            // Calendar Indonesia
            document.addEventListener('DOMContentLoaded', function() {
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
                                    `<div class="fw-bold">${date}</div><div class="meeting-items" id="meeting-${formatDate(year, month + 1, date)}"></div>`;
                                row.appendChild(cell);
                                date++;
                            }
                        }
                        calendarBody.appendChild(row);
                        if (date > daysInMonth) break;
                    }
                    monthYearLabel.textContent = `${monthNames[month]} ${year}`;
                    setTimeout(loadMeetingToCalendar, 10);
                }

                function formatDate(y, m, d) {
                    return `${y}-${String(m).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                }

                function resetAndShowModalWithDate(dateStr) {
                    $('#meetingForm')[0].reset();
                    $('#Tanggal').val(dateStr);
                    $('#modalAddMeeting').modal('show');
                }

                // Click date to add new meeting booking
                calendarBody.addEventListener('click', function(e) {
                    let target = e.target;
                    // 1. Cek apakah klik di dalam badge meeting
                    if (target.classList.contains('calendar-meeting-badge')) {
                        let meetingId = target.getAttribute('data-meeting-id');
                        if (meetingId) {
                            let found = meetings.find(m => String(m.id) === String(meetingId));
                            if (found) {
                                showMeetingDetailModal(found);
                                return;
                            }
                        }
                    }
                    while (target && !target.classList.contains('calendar-date') && target !== calendarBody) {
                        target = target.parentElement;
                    }
                    @can('meeting-create')
                        if (target && target.classList.contains('calendar-date')) {
                            let dateStr = target.getAttribute('data-date');
                            if (dateStr) {
                                resetAndShowModalWithDate(dateStr);
                            }
                        }
                    @endcan
                });
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

                renderCalendar(currentMonth, currentYear);

                @can('meeting-create')
                    $('#btn-add-meeting').click(function() {
                        $('#meetingForm')[0].reset();
                        $('#modalAddMeeting').modal('show');
                    });
                @endcan
            });
        </script>
    @endpush
@endsection
