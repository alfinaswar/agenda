<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('agenda.index');
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
        $validated = $request->validate([
            'JudulAgenda' => 'required|string|max:255',
            'KategoriAgenda' => 'nullable|string|max:255',
            'DeskripsiAgenda' => 'nullable|string',
            'TanggalMulai' => 'required|date',
            'TanggalSelesai' => 'nullable|date|after_or_equal:TanggalMulai',
            'JamMulai' => 'nullable',
            'JamSelesai' => 'nullable',
            'LokasiAgenda' => 'nullable|string|max:255',
        ]);

        $agenda = new Agenda();
        $agenda->JudulAgenda = $validated['JudulAgenda'];
        $agenda->KategoriAgenda = $validated['KategoriAgenda'] ?? null;
        $agenda->DeskripsiAgenda = $validated['DeskripsiAgenda'] ?? null;
        $agenda->TanggalMulai = $validated['TanggalMulai'];
        $agenda->TanggalSelesai = $validated['TanggalSelesai'] ?? null;
        $agenda->JamMulai = $validated['JamMulai'] ?? null;
        $agenda->JamSelesai = $validated['JamSelesai'] ?? null;
        $agenda->LokasiAgenda = $validated['LokasiAgenda'] ?? null;
        $agenda->save();

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
    public function edit(Agenda $agenda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Agenda $agenda)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agenda $agenda)
    {
        //
    }
}
