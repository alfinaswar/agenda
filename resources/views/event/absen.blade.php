<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ env('APP_NAME', 'CCP') }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('') }}assets/img/favicon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('') }}assets/css/bootstrap.min.css">

    <!-- animation CSS -->
    <link rel="stylesheet" href="{{ asset('') }}assets/css/animate.css">

    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('') }}assets/css/dataTables.bootstrap5.min.css">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('') }}assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="{{ asset('') }}assets/plugins/fontawesome/css/all.min.css">
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('') }}assets/plugins/select2/css/select2.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('') }}assets/css/style.css">

    <link rel="stylesheet" href="{{ asset('') }}assets/plugins/fullcalendar/fullcalendar.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">


</head>

<body class="account-page">

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
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-lg">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">Cari Event dan Peserta</h4>
                        </div>
                        <div class="card-body">
                            <!-- Dropdown detail event -->
                            <form id="formCariEvent" method="POST" action="">
                                <div class="mb-3">
                                    <label for="event_id" class="form-label">Pilih Event</label>
                                    <select class="form-select" name="EventId" id="event_id" required aria-pointer="no"
                                        style="pointer-events: none; background-color: #e9ecef; color: #6c757d;">
                                        <option value="" disabled selected>-- Pilih Event --</option>
                                        @if (isset($event))
                                            <option value="{{ $event->id }}" selected>{{ $event->NamaEvent }} -
                                                {{ $event->TanggalMulai }}</option>
                                        @endif
                                    </select>
                                </div>
                            </form>
                            <hr>
                            <!-- Cari peserta berdasarkan nik atau nama -->
                            <form id="formCariPeserta" method="GET" action="javascript:void(0);">
                                <div class="mb-3">
                                    <label for="keyword_peserta" class="form-label">Cari Peserta (NIK atau Nama)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="keyword_peserta"
                                            id="keyword_peserta" placeholder="Masukkan NIK atau Nama" required>
                                        <button class="btn btn-primary" type="submit" id="btnCariPeserta">
                                            <i class="fas fa-search"></i> Cari Peserta
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div id="hasil-pencarian-peserta"></div>


                            <!-- Hasil pencarian peserta akan muncul di sini -->
                            <div id="hasil-pencarian-peserta"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    </div>
    </div>
    <!-- /Main Wrapper -->



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('') }}assets/js/jquery-ui.min.js"></script>
    <script src="{{ asset('') }}assets/plugins/fullcalendar/fullcalendar.min.js"></script>
    <script src="{{ asset('') }}assets/plugins/fullcalendar/jquery.fullcalendar.js"></script>
    <script src="{{ asset('') }}assets/plugins/select2/js/select2.min.js"></script>
    <!-- Feather Icon JS -->
    <script src="{{ asset('') }}assets/js/feather.min.js"></script>
    <!-- Slimscroll JS -->
    <script src="{{ asset('') }}assets/js/jquery.slimscroll.min.js"></script>
    <script src="{{ asset('') }}assets/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('') }}assets/js/dataTables.bootstrap5.min.js"></script>
    <!-- Bootstrap Core JS -->
    <script src="{{ asset('') }}assets/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('') }}assets/js/theme-script.js"></script>
    <script src="{{ asset('') }}assets/js/script.js"></script>
    <script src="{{ asset('') }}assets/js/custom-select2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: "{{ session('success') }}",
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: "{{ session('error') }}",
            });
        @endif

        @if (session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: "{{ session('warning') }}",
            });
        @endif
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
            }
        });

        $('#formCariPeserta').on('submit', function(e) {
            e.preventDefault();
            var keyword = $('#keyword_peserta').val();
            var eventId = $('#event_id').val();
            var jenisEvent = "{{ isset($event) ? $event->Jenis : '' }}".trim();

            if (!keyword) return;

            $('#hasil-pencarian-peserta').html('<div class="text-muted">Mencari peserta...</div>');
            $.ajax({
                url: "{{ route('event.cari-peserta') }}",
                type: "POST",
                data: {
                    ajax: 1,
                    keyword_peserta: keyword,
                    EventId: eventId
                },
                success: function(response) {
                    let peserta = null;

                    if (response && response.peserta) {
                        peserta = response.peserta.NamaPeserta && response.peserta.Nik ? response
                            .peserta : null;
                    }

                    if (!peserta) {
                        $('#hasil-pencarian-peserta').html(
                            '<div class="alert alert-warning">Peserta tidak ditemukan.</div>'
                        );
                        return;
                    }

                    // Logic untuk event Internal atau bukan
                    if (jenisEvent.toLowerCase() === "internal") {
                        // Jika internal, langsung tampilkan tombol absen saja tanpa upload file
                        $('#hasil-pencarian-peserta').html(`
                            <div class="card mt-4">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Detail Peserta</h5>
                                    <div class="mb-2"><strong>Nama:</strong> <span id="namaPeserta">${peserta.NamaPeserta}</span></div>
                                    <div class="mb-2"><strong>NIK:</strong> <span id="nikPeserta">${peserta.Nik}</span></div>
                                    <hr>
                                    <form id="formAbsenPeserta" method="POST" action="{{ route('event.absen.submit') }}">
                                        @csrf
                                        <input type="hidden" name="EventId" value="{{ isset($event) ? $event->id : '' }}">
                                        <input type="hidden" name="Nik" value="${peserta.Nik}">
                                        <button type="submit" class="btn btn-success mt-1 w-100"><i class="fas fa-check"></i> Absen</button>
                                    </form>
                                </div>
                            </div>
                        `);
                    } else {
                        // Non internal: pakai signature pad
                        $('#hasil-pencarian-peserta').html(`
                            <div class="card mt-4">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Detail Peserta</h5>
                                    <div class="mb-2"><strong>Nama:</strong> <span id="namaPeserta">${peserta.NamaPeserta}</span></div>
                                    <div class="mb-2"><strong>NIK:</strong> <span id="nikPeserta">${peserta.Nik}</span></div>
                                    <hr>
                                    <form id="formAbsenPeserta" method="POST" action="{{ route('event.absen.submit') }}">
                                        @csrf
                                        <input type="hidden" name="EventId" value="{{ isset($event) ? $event->id : '' }}">
                                        <input type="hidden" name="Nik" value="${peserta.Nik}">
                                        <div class="mb-3">
                                            <label for="signature-pad" class="form-label">Tanda Tangan Peserta</label>
                                            <div style="border:1px solid #ccc; border-radius:5px; width:100%; max-width:430px;padding:7px;">
                                                <canvas id="signature-pad" width="400" height="140" style="touch-action: none; background:#f6f8fa; border-radius:5px; width:100%; max-width:400px;"></canvas>
                                            </div>
                                            <input type="hidden" name="signature" id="signature">
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-secondary btn-sm" id="clear-signature">Bersihkan</button>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success mt-1"><i class="fas fa-check"></i> Absen</button>
                                    </form>
                                </div>
                            </div>
                        `);

                        // Signature Pad logic
                        let canvas = document.getElementById('signature-pad');
                        let ctx = canvas.getContext('2d');
                        let drawing = false;
                        let lastPos = {
                            x: 0,
                            y: 0
                        };

                        function getPosition(e) {
                            var rect = canvas.getBoundingClientRect();
                            let clientX = e.touches ? e.touches[0].clientX : e.clientX;
                            let clientY = e.touches ? e.touches[0].clientY : e.clientY;
                            return {
                                x: clientX - rect.left,
                                y: clientY - rect.top
                            };
                        }

                        function startDrawing(e) {
                            drawing = true;
                            lastPos = getPosition(e);
                        }

                        function draw(e) {
                            if (!drawing) return;
                            e.preventDefault();
                            let pos = getPosition(e);
                            ctx.beginPath();
                            ctx.moveTo(lastPos.x, lastPos.y);
                            ctx.lineTo(pos.x, pos.y);
                            ctx.strokeStyle = '#1c2833';
                            ctx.lineWidth = 2;
                            ctx.lineCap = 'round';
                            ctx.stroke();
                            lastPos = pos;
                        }

                        function stopDrawing(e) {
                            drawing = false;
                        }

                        // Mouse events
                        canvas.onmousedown = startDrawing;
                        canvas.onmousemove = draw;
                        canvas.onmouseup = stopDrawing;
                        canvas.onmouseleave = stopDrawing;

                        // Touch events
                        canvas.addEventListener('touchstart', function(e) {
                            startDrawing(e);
                        }, {
                            passive: false
                        });
                        canvas.addEventListener('touchmove', function(e) {
                            draw(e);
                        }, {
                            passive: false
                        });
                        canvas.addEventListener('touchend', stopDrawing);

                        $('#clear-signature').on('click', function() {
                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                            $('#signature').val('');
                        });

                        // Before submit, set signature to base64 image
                        $('#formAbsenPeserta').on('submit', function(e) {
                            let blankCanvas = document.createElement('canvas');
                            blankCanvas.width = canvas.width;
                            blankCanvas.height = canvas.height;

                            if (canvas.toDataURL() === blankCanvas.toDataURL()) {
                                e.preventDefault();
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Oops',
                                    text: 'Silakan tanda tangan peserta dulu.'
                                });
                                return false;
                            }
                            $('#signature').val(canvas.toDataURL());
                        });
                    }
                },
                error: function(xhr) {
                    let msg =
                        '<div class="alert alert-danger">Terjadi kesalahan saat mencari peserta.</div>';
                    if (
                        xhr && xhr.responseJSON && xhr.responseJSON.message === "CSRF token mismatch."
                    ) {
                        msg =
                            '<div class="alert alert-danger">Session expired. Silakan refresh halaman dan coba lagi. (CSRF token mismatch)</div>';
                    }
                    $('#hasil-pencarian-peserta').html(msg);
                }
            });
        });
    </script>
</body>

</html>
