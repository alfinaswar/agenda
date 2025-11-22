<?php

namespace App\Http\Controllers;

use App\Models\MasterRuangan;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meetings = Meeting::with('getRuangan')->select(
            'id',
            'Ruangan',
            'JudulMeeting',
            'DeskripsiMeeting',
            'Tanggal',
            'JamMulai',
            'JamSelesai',
            'DurasiMenit',
            'TautanMeeting',
            'LampiranAgenda',
            'UserCreate'
        )->get();
        $MasterRuangan = MasterRuangan::get();
        return view('meeting.index', compact('meetings', 'MasterRuangan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Ruangan' => 'required',
            'JudulMeeting' => 'required|string|max:255',
            'DeskripsiMeeting' => 'nullable|string',
            'Tanggal' => 'required|date',
            'JamMulai' => 'required',
            'JamSelesai' => 'required|after:JamMulai',
            'DurasiMenit' => 'nullable|integer|min:1',
            'TautanMeeting' => 'nullable|string|max:255',
            'LampiranAgenda' => 'nullable|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx|max:5120',
        ]);

        // Cek bentrok jadwal ruangan
        $conflict = Meeting::where('Ruangan', $request->Ruangan)
            ->where('Tanggal', $request->Tanggal)
            ->where(function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('JamMulai', '<', $request->JamSelesai)
                        ->where('JamSelesai', '>', $request->JamMulai);
                });
            })
            ->exists();

        if ($conflict) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['JamMulai' => 'Waktu meeting bentrok dengan jadwal ruangan lain pada tanggal tersebut.']);
        }

        $lampiran = null;
        if ($request->hasFile('LampiranAgenda')) {
            $file = $request->file('LampiranAgenda');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('lampiran_meeting', $fileName, 'public');
            $lampiran = $fileName;
        }

        $meeting = Meeting::create([
            'Ruangan' => $request->Ruangan,
            'JudulMeeting' => $request->JudulMeeting,
            'DeskripsiMeeting' => $request->DeskripsiMeeting,
            'Tanggal' => $request->Tanggal,
            'JamMulai' => $request->JamMulai,
            'JamSelesai' => $request->JamSelesai,
            'DurasiMenit' => $request->DurasiMenit,
            'TautanMeeting' => $request->TautanMeeting,
            'LampiranAgenda' => $lampiran,
            'UserCreate' => auth()->user()->name,
        ]);

        return redirect()->route('meeting.index')->with('success', 'Booking meeting berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Meeting $meeting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $meeting = Meeting::findOrFail($id);
        $MasterRuangan = MasterRuangan::where('StatusRuangan', 'Aktif')->get();
        return view('meeting.edit', compact('meeting', 'MasterRuangan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meeting $meeting)
    {
        // Validation rules
        $request->validate([
            'Ruangan' => 'required',
            'JudulMeeting' => 'required|string|max:255',
            'DeskripsiMeeting' => 'nullable|string',
            'Tanggal' => 'required|date',
            'JamMulai' => 'required',
            'JamSelesai' => 'required',
            'DurasiMenit' => 'nullable|numeric',
            'TautanMeeting' => 'nullable|string|max:500',
            'LampiranAgenda' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:5120',
        ]);

        $data = [
            'Ruangan' => $request->Ruangan,
            'JudulMeeting' => $request->JudulMeeting,
            'DeskripsiMeeting' => $request->DeskripsiMeeting,
            'Tanggal' => $request->Tanggal,
            'JamMulai' => $request->JamMulai,
            'JamSelesai' => $request->JamSelesai,
            'DurasiMenit' => $request->DurasiMenit,
            'TautanMeeting' => $request->TautanMeeting,
        ];

        if ($request->hasFile('LampiranAgenda')) {
            $file = $request->file('LampiranAgenda');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('lampiran_meeting', $fileName, 'public');
            // Optionally: delete old file
            if ($meeting->LampiranAgenda && Storage::disk('public')->exists('lampiran_meeting/' . $meeting->LampiranAgenda)) {
                Storage::disk('public')->delete('lampiran_meeting/' . $meeting->LampiranAgenda);
            }
            $data['LampiranAgenda'] = $fileName;
        }

        $meeting->update($data);

        return redirect()->route('meeting.index')->with('success', 'Booking meeting berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $meeting = Meeting::findOrFail($id);

        // Hapus file lampiran jika ada
        if ($meeting->LampiranAgenda && Storage::disk('public')->exists('lampiran_meeting/' . $meeting->LampiranAgenda)) {
            Storage::disk('public')->delete('lampiran_meeting/' . $meeting->LampiranAgenda);
        }

        $meeting->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Booking meeting berhasil dihapus.']);
        }
        return redirect()->route('meeting.index')->with('success', 'Booking meeting berhasil dihapus.');
    }
    public function DownloadRekap(Request $request)
    {
        $request->validate([
            'Ruangan' => 'required',
            'TanggalAwal' => 'required|date',
            'TanggalAkhir' => 'required|date|after_or_equal:TanggalAwal'
        ]);

        $ruanganId = $request->Ruangan;
        $tanggalAwal = $request->TanggalAwal;
        $tanggalAkhir = $request->TanggalAkhir;

        $query = Meeting::with('getRuangan')
            ->whereBetween('Tanggal', [$tanggalAwal, $tanggalAkhir]);

        if ($ruanganId !== 'Semua') {
            $query->where('Ruangan', $ruanganId);
        }

        $meetings = $query->orderBy('Tanggal')
            ->orderBy('JamMulai')
            ->get();

        $ruanganNama = 'Semua Ruangan';
        if ($ruanganId !== 'Semua') {
            $ruangan = MasterRuangan::find($ruanganId);
            if ($ruangan) {
                $ruanganNama = $ruangan->NamaRuangan;
            }
        }

        // Siapkan data untuk PDF
        $data = [
            'meetings' => $meetings,
            'ruangan_nama' => $ruanganNama,
            'tanggal_awal' => $tanggalAwal,
            'tanggal_akhir' => $tanggalAkhir
        ];

        // Gunakan dompdf melalui laravel-dompdf
        $pdf = \PDF::loadView('meeting.rekap_pdf', $data);
        $filename = 'Rekap-Booking-Meeting-' . now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($filename);
    }
}
