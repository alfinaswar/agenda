<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agendas = Agenda::select(
            'id',
            'JudulAgenda',
            'TanggalMulai',
            'TanggalSelesai',
            'JamMulai',
            'JamSelesai',
            'LokasiAgenda',
            'TautanRapat',
            'KategoriAgenda',
            'StatusAgenda',
            'LampiranAgenda',
            'PenyelenggaraAgenda',
            'PelaksanaAgenda'
        )->get();

        return view('agenda.index', compact('agendas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'JudulAgenda' => 'required|string|max:255',
            'DeskripsiAgenda' => 'nullable|string',
            'TanggalMulai' => 'required|date',
            'TanggalSelesai' => 'nullable|date|after_or_equal:TanggalMulai',
            'JamMulai' => 'nullable',
            'JamSelesai' => 'nullable',
            'LokasiAgenda' => 'nullable|string|max:255',
            'TautanRapat' => 'nullable|string|max:255',
            'KategoriAgenda' => 'nullable|string|max:255',
            'StatusAgenda' => 'nullable|string|max:50',
            'LampiranAgenda' => 'nullable|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx',
            'PenyelenggaraAgenda' => 'nullable|string|max:255',
            'PelaksanaAgenda' => 'nullable|string|max:255',
        ]);

        $lampiran = null;
        if ($request->hasFile('LampiranAgenda')) {
            $file = $request->file('LampiranAgenda');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('lampiran_agenda', $fileName, 'public');
            $lampiran = $fileName;
        }

        // Simpan ke DB
        $agenda = Agenda::create([
            'JudulAgenda' => $request->JudulAgenda,
            'DeskripsiAgenda' => $request->DeskripsiAgenda,
            'PenyelenggaraAgenda' => $request->PenyelenggaraAgenda,
            'PelaksanaAgenda' => $request->PelaksanaAgenda,
            'TanggalMulai' => $request->TanggalMulai,
            'TanggalSelesai' => $request->TanggalSelesai,
            'JamMulai' => $request->JamMulai,
            'JamSelesai' => $request->JamSelesai,
            'LokasiAgenda' => $request->LokasiAgenda,
            'TautanRapat' => $request->TautanRapat,
            'KategoriAgenda' => $request->KategoriAgenda,
            'StatusAgenda' => $request->StatusAgenda,
            'LampiranAgenda' => $lampiran,
            'UserCreate' => auth()->user()->name,  // User yang membuat agenda
        ]);

        return redirect()->route('agenda.index')->with('success', 'Agenda berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Agenda $agenda)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Agenda::find($id);
        return view('agenda.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi data input
        $validated = $request->validate([
            'JudulAgenda' => 'required|string|max:255',
            'DeskripsiAgenda' => 'nullable|string',
            'TanggalMulai' => 'required|date',
            'TanggalSelesai' => 'nullable|date|after_or_equal:TanggalMulai',
            'JamMulai' => 'nullable',
            'JamSelesai' => 'nullable',
            'LokasiAgenda' => 'nullable|string|max:255',
            'TautanRapat' => 'nullable|string|max:255',
            'KategoriAgenda' => 'nullable|string|max:255',
            'StatusAgenda' => 'nullable|string|max:255',
            'LampiranAgenda' => 'nullable|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx',
        ]);

        $agenda = Agenda::findOrFail($id);
        if ($request->hasFile('LampiranAgenda')) {
            if ($agenda->LampiranAgenda && Storage::disk('public')->exists('lampiran_agenda/' . $agenda->LampiranAgenda)) {
                Storage::disk('public')->delete('lampiran_agenda/' . $agenda->LampiranAgenda);
            }
            $file = $request->file('LampiranAgenda');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('lampiran_agenda', $fileName, 'public');
            $validated['LampiranAgenda'] = $fileName;
        } else {
            unset($validated['LampiranAgenda']);
        }

        $agenda->update($validated);

        return redirect()->route('agenda.index')->with('success', 'Agenda berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $agenda = Agenda::findOrFail($id);

        if ($agenda->LampiranAgenda && Storage::disk('public')->exists('lampiran_agenda/' . $agenda->LampiranAgenda)) {
            Storage::disk('public')->delete('lampiran_agenda/' . $agenda->LampiranAgenda);
        }

        $agenda->delete();
        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Agenda berhasil dihapus.']);
        }
        return redirect()->route('agenda.index')->with('success', 'Agenda berhasil dihapus.');
    }

    public function DownloadRekap(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'TanggalAwal' => 'required|date',
            'TanggalAkhir' => 'required'
        ]);

        $tanggalAwal = $request->TanggalAwal;
        $tanggalAkhir = $request->TanggalAkhir;

        $agendas = Agenda::whereDate('TanggalMulai', '>=', $tanggalAwal)
            ->whereDate('TanggalMulai', '<=', $tanggalAkhir)
            ->orderBy('TanggalMulai')
            ->orderBy('JamMulai')
            ->get();

        $data = [
            'agendas' => $agendas,
            'tanggal_awal' => $tanggalAwal,
            'tanggal_akhir' => $tanggalAkhir
        ];

        // Anda dapat membuat file resources/views/agenda/rekap_pdf.blade.php
        $pdf = \PDF::loadView('agenda.rekap_pdf', $data);
        $filename = 'Rekap-Agenda-' . now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($filename);
    }
}
