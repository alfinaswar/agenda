<?php

namespace App\Http\Controllers;

use App\Models\MasterRuangan;
use Illuminate\Http\Request;

class MasterRuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = MasterRuangan::all();
        return view('master-ruangan.index', compact('rooms'));
    }

    public function create()
    {
        return view('master-ruangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'NamaRuangan' => 'required',
            'Kapasitas' => 'nullable|integer',
            'Lokasi' => 'nullable|string',
            'Fasilitas' => 'nullable|string',
            'StatusRuangan' => 'required'
        ]);

        MasterRuangan::create($request->all());

        return redirect()->route('master-ruangan.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $room = MasterRuangan::findOrFail($id);
        return view('master-ruangan.edit', compact('room'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'NamaRuangan' => 'required',
            'Kapasitas' => 'nullable|integer',
            'Lokasi' => 'nullable|string',
            'Fasilitas' => 'nullable|string',
            'StatusRuangan' => 'required'
        ]);

        $room = MasterRuangan::findOrFail($id);
        $room->update($request->all());

        return redirect()->route('master-ruangan.index')->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        MasterRuangan::findOrFail($id)->delete();
        return redirect()->route('master-ruangan.index')->with('success', 'Ruangan berhasil dihapus.');
    }
}
