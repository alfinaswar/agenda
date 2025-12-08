<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MasterRuanganController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\PengaturanHomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider and all of them will
 * | be assigned to the "web" middleware group. Make something great!
 * |
 */

Route::get('/', [BerandaController::class, 'beranda']);

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/event/absen/{id}', [EventController::class, 'absen'])->name('event.absen');
Route::post('/event/absen-submit', [EventController::class, 'submitAbsen'])->name('event.absen.submit');
Route::POST('/event/cari-peserta', [EventController::class, 'cariPeserta'])->name('event.cari-peserta');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);

    Route::prefix('agenda')->group(function () {
        Route::GET('/', [AgendaController::class, 'index'])->name('agenda.index');
        Route::GET('/create', [AgendaController::class, 'create'])->name('agenda.create');
        Route::POST('/store', [AgendaController::class, 'store'])->name('agenda.store');
        Route::GET('/edit/{id}', [AgendaController::class, 'edit'])->name('agenda.edit');
        Route::PUT('/update/{id}', [AgendaController::class, 'update'])->name('agenda.update');
        Route::DELETE('/delete/{id}', [AgendaController::class, 'destroy'])->name('agenda.destroy');
        Route::POST('/download-rekap-agenda', action: [AgendaController::class, 'DownloadRekap'])->name('agenda.download-rekap');
    });
    Route::prefix('booking-ruangan-meeting')->group(function () {
        Route::GET('/', [MeetingController::class, 'index'])->name('meeting.index');
        Route::GET('/create', [MeetingController::class, 'create'])->name('meeting.create');
        Route::POST('/store', [MeetingController::class, 'store'])->name('meeting.store');
        Route::POST('/download-rekap', [MeetingController::class, 'DownloadRekap'])->name('meeting.download-rekap');
        Route::GET('/edit/{id}', [MeetingController::class, 'edit'])->name('meeting.edit');
        Route::PUT('/update/{id}', [MeetingController::class, 'update'])->name('meeting.update');
        Route::DELETE('/delete/{id}', [MeetingController::class, 'destroy'])->name('meeting.destroy');
    });
    Route::prefix('pengaturan-halaman-utama')->group(function () {
        Route::get('/', [PengaturanHomeController::class, 'index'])->name('pengaturan-home.index');
        Route::get('/create', [PengaturanHomeController::class, 'create'])->name('pengaturan-home.create');
        Route::post('/store', [PengaturanHomeController::class, 'store'])->name('pengaturan-home.store');
        Route::get('/edit/{id}', [PengaturanHomeController::class, 'edit'])->name('pengaturan-home.edit');
        Route::put('/update/{id}', [PengaturanHomeController::class, 'update'])->name('pengaturan-home.update');
        Route::delete('/delete/{id}', [PengaturanHomeController::class, 'destroy'])->name('pengaturan-home.destroy');
    });
    Route::prefix('event')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('event.index');
        Route::get('/create', [EventController::class, 'create'])->name('event.create');
        Route::get('/tambah-peserta/{id}', [EventController::class, 'TambahPeserta'])->name('event.peserta');
        Route::post('/store', [EventController::class, 'store'])->name('event.store');
        Route::post('/store-peserta', [EventController::class, 'storePeserta'])->name('event.storePeserta');
        Route::get('/edit/{id}', [EventController::class, 'edit'])->name('event.edit');
        Route::put('/update/{id}', [EventController::class, 'update'])->name('event.update');
        Route::delete('/delete/{id}', [EventController::class, 'destroy'])->name('event.destroy');
        Route::post('/download-rekap', [EventController::class, 'DownloadRekap'])->name('event.download-rekap');
        Route::get('/download-template-peserta', [EventController::class, 'downloadTemplatePeserta'])->name('event.download-template-peserta');
        Route::post('/import-peserta', [EventController::class, 'importPeserta'])->name('event.import-peserta');

    });
    Route::prefix('master/ruangan')->group(function () {
        Route::GET('/', [MasterRuanganController::class, 'index'])->name('master-ruangan.index');
        Route::GET('/create', [MasterRuanganController::class, 'create'])->name('master-ruangan.create');
        Route::POST('/store', [MasterRuanganController::class, 'store'])->name('master-ruangan.store');
        Route::GET('/edit/{id}', [MasterRuanganController::class, 'edit'])->name('master-ruangan.edit');
        Route::PUT('/update/{id}', [MasterRuanganController::class, 'update'])->name('master-ruangan.update');
        Route::DELETE('/delete/{id}', [MasterRuanganController::class, 'destroy'])->name('master-ruangan.destroy');
    });
});
