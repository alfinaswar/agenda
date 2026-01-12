<?php

namespace App\Http\Controllers;

use App\Models\MasterPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MasterPegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pegawais = MasterPegawai::all();
        return view('master-pegawai.index', compact('pegawais'));
    }

    public function create()
    {
        return view('master-pegawai.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nik' => 'required|string|max:50',
            'NamaPeserta' => 'required|string|max:255',
            'AsalUnit' => 'nullable|string|max:255',
            'Gender' => 'required',
            'TandaTangan' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048', // max 2 MB
        ]);

        $tandaTanganFileName = null;
        if ($request->hasFile('TandaTangan')) {
            $file = $request->file('TandaTangan');
            $tandaTanganFileName = $file->store('tandatangan', 'public'); // simpan di storage/app/public/tandatangan

            // $tandaTanganFileName = $file->hashName();
        }

        MasterPegawai::create([
            'Nik' => $request->Nik,
            'NamaPeserta' => $request->NamaPeserta,
            'AsalUnit' => $request->AsalUnit,
            'Gender' => $request->Gender,
            'TandaTangan' => $tandaTanganFileName, // nama file/path
        ]);

        return redirect()->route('master-pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pegawai = MasterPegawai::findOrFail($id);
        return view('master-pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nik' => 'required|string|max:50',
            'NamaPeserta' => 'required|string|max:255',
            'AsalUnit' => 'nullable|string|max:255',
            'Gender' => 'required',
            'TandaTangan' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pegawai = MasterPegawai::findOrFail($id);

        $data = [
            'Nik' => $request->Nik,
            'NamaPeserta' => $request->NamaPeserta,
            'AsalUnit' => $request->AsalUnit,
            'Gender' => $request->Gender,
        ];

        if ($request->hasFile('TandaTangan')) {
            // Hapus file tanda tangan lama jika ada
            if ($pegawai->TandaTangan && Storage::disk('public')->exists($pegawai->TandaTangan)) {
                Storage::disk('public')->delete($pegawai->TandaTangan);
            }
            $data['TandaTangan'] = $request->file('TandaTangan')->store('tandatangan', 'public');
        }

        $pegawai->update($data);

        return redirect()->route('master-pegawai.index')->with('success', 'Pegawai berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pegawai = MasterPegawai::findOrFail($id);

        // Hapus file tanda tangan jika ada
        if ($pegawai->TandaTangan && Storage::disk('public')->exists($pegawai->TandaTangan)) {
            Storage::disk('public')->delete($pegawai->TandaTangan);
        }

        $pegawai->delete();
        return redirect()->route('master-pegawai.index')->with('success', 'Pegawai berhasil dihapus.');
    }
}
