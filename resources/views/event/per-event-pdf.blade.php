<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Peserta Event</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            margin: 15px;
            color: #222;
        }

        .header {
            text-align: center;
            margin-bottom: 18px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 12px;
            margin-bottom: 10px;
        }

        .event-info {
            margin: 9px 0 22px 0;
            font-size: 11px;
        }

        .event-info span {
            display: inline-block;
            min-width: 70px;
            font-weight: bold;
            color: #223a66;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 10px;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #4066ad;
            padding: 4px 5px;
            font-size: 10.1px;
            text-align: left;
            vertical-align: middle;
            word-break: break-word;
        }

        th {
            background: #e6f0ff;
            font-weight: bold;
            text-align: center;
        }

        tr:nth-child(even) td {
            background: #f8fafd;
        }

        @page {
            margin: 18px 12px 30px 12px;
        }

        thead {
            display: table-header-group;
        }

        .footer {
            margin-top: 32px;
            font-size: 9.5px;
            color: #999;
            text-align: right;
        }

        .col-no {
            width: 24px;
        }

        .col-nama {
            width: 110px;
        }

        .col-nik {
            width: 70px;
        }

        .col-instansi {
            width: 110px;
        }

        .col-ttd {
            width: 60px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">Daftar Peserta Event</div>
        <div class="subtitle">
            {{ $event->NamaEvent ?? '-' }}
        </div>
    </div>
    <div class="event-info">
        <div><span>Jenis</span>: {{ $event->Jenis ?? '-' }}</div>
        <div><span>Tanggal</span>:
            {{ \Carbon\Carbon::parse($event->TanggalMulai)->format('d-m-Y') }}
            @if ($event->TanggalSelesai)
                s/d {{ \Carbon\Carbon::parse($event->TanggalSelesai)->format('d-m-Y') }}
            @endif
        </div>
        <div><span>Lokasi</span>: {{ $event->Lokasi ?? '-' }}</div>
        <div><span>Dibuat Oleh</span>:
            @if ($event->UserCreate && is_object($event->UserCreate) && property_exists($event->UserCreate, 'name'))
                {{ \Str::limit($event->UserCreate->name, 24) }}
            @elseif (is_string($event->UserCreate))
                {{ \Str::limit($event->UserCreate, 24) }}
            @else
                -
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-nama">Nama</th>
                <th class="col-nik">NIK</th>
                <th class="col-instansi">Asal Unit Kerja/Instansi</th>
                <th class="col-ttd">TTD</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pesertas as $no => $peserta)
                <tr>
                    <td style="text-align:center">{{ $no + 1 }}</td>
                    <td>{{ $peserta->NamaPeserta ?? '-' }}</td>
                    <td>{{ $peserta->Nik ?? '-' }}</td>
                    <td>{{ $peserta->AsalUnit ?? '-' }}</td>
                    <td></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;color:#b0b0b0;padding:14px 0;">
                        Tidak ada data peserta pada event ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
    </div>
</body>

</html>
