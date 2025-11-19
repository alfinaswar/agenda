<?php

use App\Http\Controllers\AgendaController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
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
    });
});
