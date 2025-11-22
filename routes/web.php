<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MasterRuanganController;
use App\Http\Controllers\MeetingController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

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
    Route::prefix('master/ruangan')->group(function () {
        Route::GET('/', [MasterRuanganController::class, 'index'])->name('master-ruangan.index');
        Route::GET('/create', [MasterRuanganController::class, 'create'])->name('master-ruangan.create');
        Route::POST('/store', [MasterRuanganController::class, 'store'])->name('master-ruangan.store');
        Route::GET('/edit/{id}', [MasterRuanganController::class, 'edit'])->name('master-ruangan.edit');
        Route::PUT('/update/{id}', [MasterRuanganController::class, 'update'])->name('master-ruangan.update');
        Route::DELETE('/delete/{id}', [MasterRuanganController::class, 'destroy'])->name('master-ruangan.destroy');
    });
});
