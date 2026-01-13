<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\MasterPegawai;
use App\Models\PesertaEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest('created_at')->select(
            'id',
            'Jenis',
            'NamaEvent',
            'Deskripsi',
            'Keterangan',
            'TanggalMulai',
            'TanggalSelesai',
            'Lokasi',
            'UserCreate'
        )->get();

        return view('event.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('event.create');
    }

    public function downloadTemplatePeserta()
    {
        $filePath = public_path('format-import/peserta_events.csv');
        if (file_exists($filePath)) {
            return response()->download($filePath, 'peserta_events.csv');
        } else {
            return redirect()->back()->with('error', 'Template file tidak ditemukan.');
        }
    }

    public function TambahPeserta($id)
    {
        $event = Event::with('getPeserta')->find($id);
        // dd($event);
        $listEventSebelumnya = Event::with('getPeserta')->where('id', '!=', $id)->get();
        $pegawai = MasterPegawai::get();

        if (strtolower($event->Jenis ?? '') === 'internal') {
            return view('event.tambah-peserta-internal', compact('event', 'listEventSebelumnya', 'pegawai'));
        } else {
            // dd(123);
            return view('event.tambah-peserta', compact('event', 'listEventSebelumnya', 'pegawai'));
        }
    }

    public function importPesertaFromEvent(Request $request)
    {
        $peserta = PesertaEvent::where('EventId', $request->EventSumberId)->get();

        foreach ($peserta as $p) {
            PesertaEvent::create([
                'EventId' => $request->EventId,
                'Nik' => $p->Nik,
                'NamaPeserta' => $p->NamaPeserta,
                'Gender' => $p->Gender,
            ]);
        }

        return back()->with('success', 'Peserta berhasil diimport dari event sebelumnya');
    }

    public function absen($id)
    {
        $event = Event::with('getPeserta')->find($id);
        return view('event.absen', compact('event'));
    }

    public function submitAbsen(Request $request)
    {
        $event = Event::find($request->EventId);
        if (!$event) {
            return redirect()->back()->with('error', 'Event tidak ditemukan.');
        }

        $peserta = PesertaEvent::where('EventId', $request->EventId)
            ->where('Nik', $request->Nik)
            ->first();

        if (!$peserta) {
            return redirect()->back()->with('error', 'Peserta tidak ditemukan pada event ini.');
        }

        if ($peserta->Hadir == 'Y') {
            return redirect()->back()->with('warning', 'Peserta sudah absen sebelumnya.');
        }

        if (strtolower($event->Jenis) == 'internal') {

        } else {
            $signature = $request->input('signature');
            if (!$signature || !preg_match('/^data:image\/png;base64,/', $signature)) {
                return redirect()->back()->with('error', 'Tanda tangan peserta diperlukan.');
            }

            $signatureData = preg_replace('/^data:image\/png;base64,/', '', $signature);
            $signatureData = str_replace(' ', '+', $signatureData);
            $fileName = 'signature_' . $peserta->Nik . '_' . time() . '.png';
            $filePath = 'signature_file/' . $fileName;
            Storage::disk('public')->put($filePath, base64_decode($signatureData));
            $peserta->TandaTangan = $filePath;
        }

        $peserta->Hadir = 'Y';
        $peserta->save();

        return redirect()->back()->with('success', 'Absensi peserta berhasil.');
    }

    public function cariPeserta(Request $request)
    {
        $keyword = $request->input('keyword_peserta');
        $eventId = $request->input('EventId');

        if (!$keyword || !$eventId) {
            return response()->json(['message' => 'Parameter tidak lengkap.'], 400);
        }

        $event = Event::find($eventId);
        if (!$event) {
            return response()->json(['message' => 'Event tidak ditemukan.'], 404);
        }

        $peserta = PesertaEvent::where('EventId', $eventId)
            ->where(function ($query) use ($keyword) {
                $query
                    ->where('Nik', $keyword)
                    ->orWhere('NamaPeserta', 'like', "%$keyword%");
            })
            ->first();
        if ($peserta) {
            return response()->json([
                'peserta' => [
                    'NamaPeserta' => $peserta->NamaPeserta,
                    'Nik' => $peserta->Nik,
                ]
            ]);
        } else {
            return response()->json(['peserta' => null]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Jenis' => 'required|string|max:255',
            'NamaEvent' => 'required|string|max:255',
            'Deskripsi' => 'nullable|string',
            'Keterangan' => 'nullable|string',
            'TanggalMulai' => 'required|date',
            'TanggalSelesai' => 'nullable|date|after_or_equal:TanggalMulai',
            'Lokasi' => 'nullable|string|max:255',
        ]);

        $event = Event::create([
            'Jenis' => $request->Jenis,
            'NamaEvent' => $request->NamaEvent,
            'Deskripsi' => $request->Deskripsi,
            'Keterangan' => $request->Keterangan,
            'TanggalMulai' => $request->TanggalMulai,
            'TanggalSelesai' => $request->TanggalSelesai,
            'Lokasi' => $request->Lokasi,
            'UserCreate' => auth()->user()->name,
        ]);

        return redirect()->route('event.index')->with('success', 'Event berhasil ditambahkan.');
    }

    public function storePeserta(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'EventId' => 'required|integer|exists:events,id',
            'Nik' => 'required|array',
            'Nik.*' => 'required|string|max:20',
            'NamaPeserta' => 'required|array',
            'NamaPeserta.*' => 'required|string|max:255',
            'Gender' => 'required|array',
            'Gender.*' => 'required|string|in:L,P',
        ]);

        PesertaEvent::where('EventId', $request->EventId)->delete();

        $jumlahPeserta = count($request->NamaPeserta);
        for ($i = 0; $i < $jumlahPeserta; $i++) {
            $tandaTanganPath = null;
            if ($request->hasFile("TandaTangan.{$i}") && $request->file("TandaTangan.{$i}")->isValid()) {
                $uploadedFile = $request->file("TandaTangan.{$i}");
                $filename = uniqid('ttd_') . '.' . $uploadedFile->getClientOriginalExtension();
                $uploadedFile->storeAs('signature_file', $filename, 'public');
                $tandaTanganPath = 'signature_file/' . $filename;
            }

            PesertaEvent::create([
                'EventId' => $request->EventId,
                'Nik' => $request->Nik[$i],
                'NamaPeserta' => $request->NamaPeserta[$i],
                'AsalUnit' => $request->AsalUnit[$i] ?? null,
                'TandaTangan' => $tandaTanganPath,
                'Gender' => $request->Gender[$i],
                'UserCreate' => auth()->user()->name,
            ]);
        }

        return redirect()->route('event.index')->with('success', 'Event berhasil ditambahkan.');
    }

    public function importPeserta(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv',
            'EventId' => 'required|integer|exists:events,id',
        ]);

        $file = $request->file('file_excel');
        $data = [];

        // Get file extension to decide how to parse
        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'csv') {
            // Parse CSV
            if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
                $isFirst = true;
                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    if ($isFirst) {
                        $header = $row;
                        $isFirst = false;
                        continue;
                    }
                    $nik = isset($row[0]) ? trim($row[0]) : '';
                    $namaPeserta = isset($row[1]) ? trim($row[1]) : '';
                    $gender = isset($row[2]) ? strtoupper(trim($row[2])) : '';
                    if (empty($nik) && empty($namaPeserta)) {
                        continue;
                    }
                    $data[] = [
                        'Nik' => $nik,
                        'NamaPeserta' => $namaPeserta,
                        'Gender' => $gender,
                    ];
                }
                fclose($handle);
            }
        } else {
            // Assume XLSX/XLS, use IOFactory
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, false, false);
            $header = array_shift($rows);
            foreach ($rows as $row) {
                $nik = isset($row[0]) ? trim($row[0]) : '';
                $namaPeserta = isset($row[1]) ? trim($row[1]) : '';
                $gender = isset($row[2]) ? strtoupper(trim($row[2])) : '';
                if (empty($nik) && empty($namaPeserta)) {
                    continue;
                }
                $data[] = [
                    'Nik' => $nik,
                    'NamaPeserta' => $namaPeserta,
                    'Gender' => $gender,
                ];
            }
        }

        $nikList = [];
        $namaPesertaList = [];
        $genderList = [];
        foreach ($data as $peserta) {
            $nikList[] = $peserta['Nik'];
            $namaPesertaList[] = $peserta['NamaPeserta'];
            $genderList[] = $peserta['Gender'];
        }

        $validator = \Validator::make([
            'Nik' => $nikList,
            'NamaPeserta' => $namaPesertaList,
            'Gender' => $genderList,
        ], [
            'Nik' => 'required|array',
            'Nik.*' => 'required|string|max:20',
            'NamaPeserta' => 'required|array',
            'NamaPeserta.*' => 'required|string|max:255',
            'Gender' => 'required|array',
            'Gender.*' => 'required|string|in:L,P',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        PesertaEvent::where('EventId', $request->EventId)->delete();
        foreach ($data as $peserta) {
            PesertaEvent::create([
                'EventId' => $request->EventId,
                'Nik' => $peserta['Nik'],
                'NamaPeserta' => $peserta['NamaPeserta'],
                'AsalUnit' => $peserta['AsalUnit'],
                'Gender' => $peserta['Gender'],
                'UserCreate' => auth()->user()->name,
            ]);
        }

        return redirect()
            ->route('event.peserta', $request->EventId)
            ->with('success', 'Data peserta berhasil diimport dan disimpan permanen.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return view('event.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Event::find($id);
        return view('event.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'Jenis' => 'required|string|max:255',
            'NamaEvent' => 'required|string|max:255',
            'Deskripsi' => 'nullable|string',
            'Keterangan' => 'nullable|string',
            'TanggalMulai' => 'required|date',
            'TanggalSelesai' => 'nullable|date|after_or_equal:TanggalMulai',
            'Lokasi' => 'nullable|string|max:255',
        ]);

        $event = Event::findOrFail($id);

        $event->update($validated);

        return redirect()->route('event.index')->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        $event->delete();
        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Event berhasil dihapus.']);
        }
        return redirect()->route('event.index')->with('success', 'Event berhasil dihapus.');
    }

    public function DownloadRekap(Request $request)
    {
        $request->validate([
            'TanggalAwal' => 'required|date',
            'TanggalAkhir' => 'required'
        ]);

        $tanggalAwal = $request->TanggalAwal;
        $tanggalAkhir = $request->TanggalAkhir;

        $events = Event::whereDate('TanggalMulai', '>=', $tanggalAwal)
            ->whereDate('TanggalMulai', '<=', $tanggalAkhir)
            ->orderBy('TanggalMulai')
            ->get();

        $data = [
            'events' => $events,
            'tanggal_awal' => $tanggalAwal,
            'tanggal_akhir' => $tanggalAkhir
        ];

        $pdf = \PDF::loadView('event.rekap', $data);
        $filename = 'Rekap-Event-' . now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($filename);
    }
    public function DownloadPdf($id)
    {
        $event = Event::with('getPeserta')->findOrFail($id);
        $data = [
            'event' => $event,
            'pesertas' => $event->getPeserta ?? []
        ];

        $pdf = \PDF::loadView('event.per-event-pdf', $data);

        $filename = 'Event-' . preg_replace('/[^A-Za-z0-9\-]/', '', ($event->NamaEvent ?? 'Detail')) . '-' . now()->format('Ymd_His') . '.pdf';
        return $pdf->stream($filename);
    }
}
