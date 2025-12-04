<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rekap Booking Ruangan Meeting</title>
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

        /* Make columns narrower to fit more on page */
        .col-no {
            width: 18px;
        }

        .col-tgl {
            width: 52px;
        }

        .col-ruangan {
            width: 58px;
        }

        .col-judul {
            width: 90px;
        }

        .col-deskripsi {
            width: 100px;
        }

        .col-mulai {
            width: 30px;
        }

        .col-selesai {
            width: 32px;
        }

        .col-durasi {
            width: 27px;
        }

        .col-lampiran {
            width: 23px;
        }

        .col-user {
            width: 60px;
        }

        .col-pic {
            width: 60px;
        }

        .col-nohp {
            width: 65px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">Rekap Booking Ruangan Meeting</div>
        <div class="subtitle">Ruangan: {{ $ruangan_nama }}</div>
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
                <th class="col-tgl">Tgl</th>
                <th class="col-ruangan">Ruangan</th>
                <th class="col-judul">Judul</th>
                <th class="col-deskripsi">Deskripsi</th>
                <th class="col-mulai">Mulai</th>
                <th class="col-selesai">Selesai</th>
                <th class="col-durasi">Durasi<br>(mnt)</th>
                <th class="col-lampiran">Lmpn</th>
                <th class="col-user">User</th>
                <th class="col-pic">PIC</th>
                <th class="col-nohp">No HP</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($meetings as $no => $m)
                <tr>
                    <td style="text-align:center">{{ $no + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($m->Tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $m->getRuangan->NamaRuangan ?? '-' }}</td>
                    <td>{{ \Str::limit($m->JudulMeeting, 28) }}</td>
                    <td>
                        @if ($m->DeskripsiMeeting)
                            {{ \Str::limit($m->DeskripsiMeeting, 42) }}
                        @else
                            -
                        @endif
                    </td>
                    <td style="text-align:center;">{{ $m->JamMulai ?? '-' }}</td>
                    <td style="text-align:center;">{{ $m->JamSelesai ?? '-' }}</td>
                    <td style="text-align:center;">{{ $m->DurasiMenit ?? '-' }}</td>
                    <td style="text-align:center;">
                        @if ($m->LampiranAgenda)
                            <a href="{{ asset('storage/lampiran_meeting/' . $m->LampiranAgenda) }}"
                                download>download</a>
                        @else
                            &mdash;
                        @endif
                    </td>
                    <td>{{ \Str::limit($m->UserCreate ?? '-', 20) }}</td>
                    <td>{{ \Str::limit($m->Pic ?? '-', 18) }}</td>
                    <td>{{ \Str::limit($m->NoHp ?? '-', 18) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" style="text-align:center;color:#b0b0b0;padding:14px 0;">
                        Tidak ada data booking ruangan meeting pada periode ini.
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
