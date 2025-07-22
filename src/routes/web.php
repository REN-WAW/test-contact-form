<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contacts/confirm', [ContactController::class, 'confirm'])->name('contact.confirm');
Route::post('/send', [ContactController::class, 'send'])->name('contact.send');
Route::get('/thanks', [ContactController::class, 'thanks'])->name('contact.thanks');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/search', [AdminController::class, 'search'])->name('admin.search');
    Route::get('/admin/export', [AdminController::class, 'export'])->name('admin.export');
    Route::delete('/admin/{contact}', [AdminController::class, 'destroy'])->name('admin.destroy');
});
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
