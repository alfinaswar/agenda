<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>Login - Lumina Kasir</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('') }}assets/img/favicon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('') }}assets/css/bootstrap.min.css">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('') }}assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="{{ asset('') }}assets/plugins/fontawesome/css/all.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('') }}assets/css/style.css">
    <style>
        /* Custom calendar cell style (B) */
        #agenda-calendar .calendar-date,
        #meeting-calendar .calendar-date {
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

        #agenda-calendar .calendar-date:hover,
        #meeting-calendar .calendar-date:hover {
            background-color: #e0ecff;
            border-color: #6699ee;
            box-shadow: 0 2px 8px rgba(66, 133, 244, 0.18);
        }

        #agenda-calendar .calendar-date .fw-bold,
        #meeting-calendar .calendar-date .fw-bold {
            color: #1a53aa;
            font-size: 1.085em;
            margin-bottom: 0.25em;
            margin-top: 2px;
        }

        #agenda-calendar .calendar-date .agenda-items span.badge {
            background: linear-gradient(90deg, #1976d2 0%, #51a0fd 100%);
            color: #fff;
            font-weight: 500;
            font-size: 0.95em;
            border-radius: 6px;
            cursor: pointer;
        }

        #meeting-calendar .calendar-date .meeting-items span.badge {
            background: linear-gradient(90deg, #1976d2 0%, #51a0fd 100%);
            color: #fff;
            font-weight: 500;
            font-size: 0.95em;
            border-radius: 6px;
            cursor: pointer;
        }

        #agenda-calendar thead th,
        #meeting-calendar thead th {
            background-color: #e0ecff;
            color: #2166c5;
            border-bottom-width: 2px;
            border-top-right-radius: 0;
        }

        #agenda-calendar .calendar-date[data-date]:active,
        #meeting-calendar .calendar-date[data-date]:active {
            background: #bdd6ff;
        }
    </style>
</head>

<body class="account-page">

    {{-- <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div> --}}
    <style>
        .fab-login-logout {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 2000;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 15px;
        }

        .fab-login-logout .fab-btn-circular {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 16px rgba(60, 130, 246, 0.18);
            font-size: 1.5rem;
            padding: 0;
            border: none;
            background: linear-gradient(135deg, #1976d2 0%, #51a0fd 100%);
            color: #fff;
            transition: box-shadow 0.18s, background 0.15s;
        }

        .fab-login-logout .fab-btn-circular:hover,
        .fab-login-logout .fab-btn-circular:focus {
            box-shadow: 0 8px 24px rgba(60, 130, 246, 0.24);
            background: linear-gradient(135deg, #1759be 0%, #1976d2 100%);
            color: #fff;
        }

        .fab-login-logout .fab-label {
            background: #fff;
            color: #1976d2;
            font-weight: 500;
            border-radius: 50px;
            padding: 9px 20px 9px 20px;
            box-shadow: 0 2px 8px rgba(66, 133, 244, 0.12);
            margin-bottom: 3px;
            font-size: 1em;
            display: inline-block;
            margin-right: 0;
        }

        .fab-login-logout form {
            margin: 0;
        }
    </style>
    <div class="fab-login-logout">
        @guest
            <a href="{{ route('login') }}" class="fab-btn-circular" title="Login" aria-label="Login">
                <i class="fas fa-sign-in-alt"></i>
            </a>
        @else
            <span class="fab-label d-none d-md-inline shadow-sm">Halo, {{ Auth::user()->name }}</span>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="fab-btn-circular" title="Logout" aria-label="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        @endguest
    </div>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <div class="container mt-5">
            <ul class="nav nav-tabs" id="tabAgendaBooking" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active d-flex align-items-center" id="agenda-tab" data-bs-toggle="tab"
                        data-bs-target="#agenda" type="button" role="tab" aria-controls="agenda"
                        aria-selected="true" style="color: #1976d2; font-weight: 600;">
                        <i class="fas fa-calendar-alt me-2" style="color: #1976d2;"></i>
                        Agenda
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link d-flex align-items-center" id="booking-tab" data-bs-toggle="tab"
                        data-bs-target="#booking" type="button" role="tab" aria-controls="booking"
                        aria-selected="false" style="color: #0d8a5c; font-weight: 600;">
                        <i class="fas fa-door-open me-2" style="color: #0d8a5c;"></i>
                        Booking Ruangan
                    </button>
                </li>
            </ul>
            <div class="tab-content p-4 bg-white border border-top-0" id="tabAgendaBookingContent">

                <div class="tab-pane fade show active" id="agenda" role="tabpanel" aria-labelledby="agenda-tab">
                    <h4 class="mb-3">Daftar Agenda</h4>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-white">
                                <div class="card-body">
                                    <!-- Calendar khusus agenda -->
                                    <div id="agenda-calendar-wrapper">
                                        <table class="table table-bordered text-center" id="agenda-calendar">
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
                                            <tbody id="agenda-calendar-body">
                                                <!-- Calendar will be populated by JS -->
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-between mt-2">
                                            <button id="prev-agenda-month"
                                                class="btn btn-outline-secondary btn-sm">Sebelumnya</button>
                                            <span id="agenda-calendar-month-year" class="fw-bold"></span>
                                            <button id="next-agenda-month"
                                                class="btn btn-outline-secondary btn-sm">Berikutnya</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (isset($agendas) && count($agendas) > 0)
                        <div class="table-responsive">
                            <table class="table datanew table-striped align-middle dataTable" id="agenda-datatable">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Judul</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Jam Mulai</th>
                                        <th>Jam Selesai</th>
                                        <th>Lokasi</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($agendas as $agenda)
                                        <tr>
                                            <td>{{ $agenda->JudulAgenda }}</td>
                                            <td>{{ $agenda->TanggalMulai }}</td>
                                            <td>{{ $agenda->TanggalSelesai ?? '-' }}</td>
                                            <td>{{ $agenda->JamMulai ?? '-' }}</td>
                                            <td>{{ $agenda->JamSelesai ?? '-' }}</td>
                                            <td>{{ $agenda->LokasiAgenda ?? '-' }}</td>
                                            <td>{{ $agenda->KategoriAgenda ?? '-' }}</td>
                                            <td>{{ $agenda->StatusAgenda ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">Tidak ada agenda tersedia.</div>
                    @endif
                </div>
                <div class="tab-pane fade" id="booking" role="tabpanel" aria-labelledby="booking-tab">
                    <h4 class="mb-3">Daftar Booking Ruangan Meeting</h4>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-white">
                                <div class="card-body">
                                    <!-- Calendar khusus meeting/booking -->
                                    <div id="meeting-calendar-wrapper">
                                        <table class="table table-bordered text-center" id="meeting-calendar">
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
                                            <tbody id="meeting-calendar-body">
                                                <!-- Calendar will be populated by JS -->
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-between mt-2">
                                            <button id="prev-meeting-month"
                                                class="btn btn-outline-secondary btn-sm">Sebelumnya</button>
                                            <span id="meeting-calendar-month-year" class="fw-bold"></span>
                                            <button id="next-meeting-month"
                                                class="btn btn-outline-secondary btn-sm">Berikutnya</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
    <!-- /Main Wrapper -->

    <div class="customizer-links" id="setdata">
        <ul class="sticky-sidebar">
            <li class="sidebar-icons">
                <a href="#" class="navigation-add" data-bs-toggle="tooltip" data-bs-placement="left"
                    data-bs-original-title="Theme">
                    <i data-feather="settings" class="feather-five"></i>
                </a>
            </li>
        </ul>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('') }}assets/js/jquery-3.7.1.min.js"></script>

    <!-- Feather Icon JS -->
    <script src="{{ asset('') }}assets/js/feather.min.js"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{ asset('') }}assets/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('') }}assets/js/theme-script.js"></script>
    <script src="{{ asset('') }}assets/js/script.js"></script>

    <!-- JavaScript untuk Kalender Agenda -->
    <script>
        const agendas = @json($agendas);
        let lastDetailAgendaId = null;

        // Render agenda items with clickable badges to calendar (khusus agenda)
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
                            id="${badgeId}">
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
            lastDetailAgendaId = agenda.id;
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
                window.location.href = "{{ route('agenda.edit', ['id' => ':id']) }}".replace(':id',
                    lastDetailAgendaId);
            }
        });

        // Kalender Agenda (KALENDER BETUL2 MURNI UNTUK TAB AGENDA)
        function renderAgendaCalendar(month, year) {
            const calendarBody = document.getElementById('agenda-calendar-body');
            const monthYearLabel = document.getElementById('agenda-calendar-month-year');
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
                        let thisDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
                        cell.setAttribute('data-date', thisDate);
                        cell.innerHTML =
                            `<div class="fw-bold">${date}</div><div class="agenda-items" id="agenda-${thisDate}"></div>`;
                        row.appendChild(cell);
                        date++;
                    }
                }
                calendarBody.appendChild(row);
                if (date > daysInMonth) break;
            }
            monthYearLabel.textContent = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ][month] + ` ${year}`;
            setTimeout(loadAgendaToCalendar, 10);
        }

        (function() {
            // Hanya hidup pada tab agenda
            let today = new Date();
            let currentMonth = today.getMonth();
            let currentYear = today.getFullYear();

            function showModalTambahAgenda(dateStr) {
                $('#agendaForm')[0].reset();
                $('#TanggalMulai').val(dateStr);
                $('#TanggalSelesai').val('');
                $('#modalAddAgenda').modal('show');
            }

            if (document.getElementById('agenda-calendar-body')) {
                renderAgendaCalendar(currentMonth, currentYear);

                document.getElementById('prev-agenda-month').addEventListener('click', function() {
                    currentMonth--;
                    if (currentMonth < 0) {
                        currentMonth = 11;
                        currentYear--;
                    }
                    renderAgendaCalendar(currentMonth, currentYear);
                });

                document.getElementById('next-agenda-month').addEventListener('click', function() {
                    currentMonth++;
                    if (currentMonth > 11) {
                        currentMonth = 0;
                        currentYear++;
                    }
                    renderAgendaCalendar(currentMonth, currentYear);
                });

                document.getElementById('agenda-calendar-body').addEventListener('click', function(e) {
                    let target = e.target;
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
                    while (target && !target.classList.contains('calendar-date') && target !== this) {
                        target = target.parentElement;
                    }
                    if (target && target.classList.contains('calendar-date')) {
                        let dateStr = target.getAttribute('data-date');
                        if (dateStr) {
                            showModalTambahAgenda(dateStr);
                        }
                    }
                });

                $('#btn-add-event').click(function() {
                    $('#agendaForm')[0].reset();
                    $('#modalAddAgenda').modal('show');
                });
            }
        })();
    </script>

    <!-- JavaScript untuk Kalender Booking Ruangan/Meeting -->
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
                            id="${badgeId}">
                            ${m.JudulMeeting}
                            <br>
                            <small>
                                ${(m.get_ruangan && m.get_ruangan.NamaRuangan) ? m.get_ruangan.NamaRuangan : ''}
                                ${(m.JamMulai && m.JamSelesai)
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

        $(document).off('click', '#btnEditMeeting').on('click', '#btnEditMeeting', function(e) {
            $('#modalMeetingDetail').modal('hide');
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

        $(document).off('click', '#btnDeleteMeeting').on('click', '#btnDeleteMeeting', function() {
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

        // Kalender Meeting / Booking Room (khusus tab Booking Ruangan)
        function renderMeetingCalendar(month, year) {
            const calendarBody = document.getElementById('meeting-calendar-body');
            const monthYearLabel = document.getElementById('meeting-calendar-month-year');
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
                        let thisDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
                        cell.setAttribute('data-date', thisDate);
                        cell.innerHTML =
                            `<div class="fw-bold">${date}</div><div class="meeting-items" id="meeting-${thisDate}"></div>`;
                        row.appendChild(cell);
                        date++;
                    }
                }
                calendarBody.appendChild(row);
                if (date > daysInMonth) break;
            }
            monthYearLabel.textContent = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ][month] + ` ${year}`;
            setTimeout(loadMeetingToCalendar, 10);
        }

        (function() {
            let today = new Date();
            let currentMonth = today.getMonth();
            let currentYear = today.getFullYear();

            function showModalTambahMeeting(dateStr) {
                $('#meetingForm')[0].reset();
                $('#Tanggal').val(dateStr);
                $('#modalAddMeeting').modal('show');
            }

            if (document.getElementById('meeting-calendar-body')) {
                renderMeetingCalendar(currentMonth, currentYear);

                document.getElementById('prev-meeting-month').addEventListener('click', function() {
                    currentMonth--;
                    if (currentMonth < 0) {
                        currentMonth = 11;
                        currentYear--;
                    }
                    renderMeetingCalendar(currentMonth, currentYear);
                });

                document.getElementById('next-meeting-month').addEventListener('click', function() {
                    currentMonth++;
                    if (currentMonth > 11) {
                        currentMonth = 0;
                        currentYear++;
                    }
                    renderMeetingCalendar(currentMonth, currentYear);
                });

                document.getElementById('meeting-calendar-body').addEventListener('click', function(e) {
                    let target = e.target;
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
                    while (target && !target.classList.contains('calendar-date') && target !== this) {
                        target = target.parentElement;
                    }
                    if (target && target.classList.contains('calendar-date')) {
                        let dateStr = target.getAttribute('data-date');
                        if (dateStr) {
                            showModalTambahMeeting(dateStr);
                        }
                    }
                });

                $('#btn-add-meeting').click(function() {
                    $('#meetingForm')[0].reset();
                    $('#modalAddMeeting').modal('show');
                });
            }
        })();
    </script>

    <script>
        $(document).ready(function() {
            $('#Ruangan').select2({
                dropdownParent: $('#modalAddMeeting'),
                placeholder: "Pilih Ruangan",
                width: '100%'
            });
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
</body>

</html>
