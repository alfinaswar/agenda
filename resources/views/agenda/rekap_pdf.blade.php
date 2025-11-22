<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rekap Agenda</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
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

        /* Reduce margins and add page-break for long tables */
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
            width: 20px;
        }

        .col-tgl {
            width: 54px;
        }

        .col-judul {
            width: 92px;
        }

        .col-deskripsi {
            width: 105px;
        }

        .col-mulai {
            width: 32px;
        }

        .col-selesai {
            width: 34px;
        }

        .col-lokasi {
            width: 70px;
        }

        .col-kategori {
            width: 56px;
        }

        .col-status {
            width: 42px;
        }

        .col-lampiran {
            width: 23px;
        }

        .col-user {
            width: 62px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">Rekap Agenda</div>
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
                <th class="col-tgl">Tgl Mulai</th>
                <th class="col-mulai">Jam<br>Mulai</th>
                <th class="col-selesai">Jam<br>Selesai</th>
                <th class="col-judul">Judul</th>
                <th class="col-deskripsi">Deskripsi</th>
                <th class="col-lokasi">Lokasi</th>
                <th class="col-kategori">Kategori</th>
                <th class="col-status">Status</th>
                <th class="col-lampiran">Lmpn</th>
                <th class="col-user">User</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($agendas as $no => $a)
                <tr>
                    <td style="text-align:center">{{ $no + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($a->TanggalMulai)->format('d-m-Y') }}</td>
                    <td style="text-align:center;">{{ $a->JamMulai ?? '-' }}</td>
                    <td style="text-align:center;">{{ $a->JamSelesai ?? '-' }}</td>
                    <td>{{ \Str::limit($a->JudulAgenda, 28) }}</td>
                    <td>
                        @if ($a->DeskripsiAgenda)
                            {{ \Str::limit($a->DeskripsiAgenda, 42) }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ \Str::limit($a->LokasiAgenda ?? '-', 18) }}</td>
                    <td>{{ $a->KategoriAgenda ?? '-' }}</td>
                    <td>{{ $a->StatusAgenda ?? '-' }}</td>
                    <td style="text-align:center;">
                        @if ($a->LampiranAgenda)
                            &#9989;
                        @else
                            &mdash;
                        @endif
                    </td>
                    <td>{{ \Str::limit($a->UserCreate ?? '-', 18) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align:center;color:#b0b0b0;padding:14px 0;">
                        Tidak ada agenda pada periode ini.
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
