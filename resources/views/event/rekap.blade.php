<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rekap Event</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            margin: 15px;
            color: #222;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 0;
        }

        .subtitle {
            font-size: 12px;
            margin: 0 0 8px 0;
        }

        .periode {
            font-size: 12px;
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 8px;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #4066ad;
            padding: 4px 5px;
            font-size: 10px;
            text-align: left;
            vertical-align: top;
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

        tfoot {
            display: table-footer-group;
        }

        .footer {
            margin-top: 32px;
            font-size: 9.5px;
            color: #999;
            text-align: right;
        }

        .col-no {
            width: 18px;
        }

        .col-jenis {
            width: 48px;
        }

        .col-nama {
            width: 88px;
        }

        .col-deskripsi {
            width: 92px;
        }

        .col-keterangan {
            width: 78px;
        }

        .col-tglmulai {
            width: 50px;
        }

        .col-tglselesai {
            width: 50px;
        }

        .col-lokasi {
            width: 70px;
        }

        .col-user {
            width: 75px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">Rekap Event</div>
        <div class="periode">
            Periode:
            {{ \Carbon\Carbon::parse($tanggal_awal)->format('d-m-Y') }}
            s/d
            {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d-m-Y') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-jenis">Jenis</th>
                <th class="col-nama">Nama Event</th>
                <th class="col-deskripsi">Deskripsi</th>
                <th class="col-keterangan">Keterangan</th>
                <th class="col-tglmulai">Tgl Mulai</th>
                <th class="col-tglselesai">Tgl Selesai</th>
                <th class="col-lokasi">Lokasi</th>
                <th class="col-user">Dibuat Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($events as $no => $event)
                <tr>
                    <td style="text-align:center">{{ $no + 1 }}</td>
                    <td>{{ $event->Jenis ?? '-' }}</td>
                    <td>{{ \Str::limit($event->NamaEvent ?? '-', 36) }}</td>
                    <td>
                        @if ($event->Deskripsi)
                            {{ \Str::limit($event->Deskripsi, 42) }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($event->Keterangan)
                            {{ \Str::limit($event->Keterangan, 32) }}
                        @else
                            -
                        @endif
                    </td>
                    <td style="text-align:center;">
                        {{ \Carbon\Carbon::parse($event->TanggalMulai)->format('d-m-Y') }}
                    </td>
                    <td style="text-align:center;">
                        @if ($event->TanggalSelesai)
                            {{ \Carbon\Carbon::parse($event->TanggalSelesai)->format('d-m-Y') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $event->Lokasi ?? '-' }}</td>
                    <td>
                        @if ($event->UserCreate && is_object($event->UserCreate) && property_exists($event->UserCreate, 'name'))
                            {{ \Str::limit($event->UserCreate->name, 24) }}
                        @elseif (is_string($event->UserCreate))
                            {{ \Str::limit($event->UserCreate, 24) }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center;color:#b0b0b0;padding:14px 0;">
                        Tidak ada data event pada periode ini.
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
