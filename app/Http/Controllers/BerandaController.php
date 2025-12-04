<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Event;
use App\Models\Meeting;

class BerandaController extends Controller
{
    public function __construct()
    {
        // Tidak perlu middleware auth di sini agar tidak butuh login
    }

    public function beranda()
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
            'LampiranAgenda'
        )->get();
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
            'UserCreate',
        )->get();
        $events = Event::with('getPeserta')->get();
        return view('beranda', compact('agendas', 'meetings', 'events'));
    }
}
