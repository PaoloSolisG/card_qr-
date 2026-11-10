<?php

use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CardController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

/*
| Public card opened from the QR.
*/
Route::get('/c/{agent}', [CardController::class, 'show'])->name('card.show');
Route::get('/c/{agent}/contacto.vcf', [CardController::class, 'vcard'])->name('card.vcard');
Route::post('/c/{agent}/datos', [CardController::class, 'storeLead'])
    ->middleware('throttle:10,1')
    ->name('card.leads.store');

/*
| Admin panel.
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:6,1');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::resource('agents', AgentController::class)->except('show');
    Route::get('agents/{agent}/qr', [AgentController::class, 'qr'])->name('agents.qr');
    Route::get('agents/{agent}/qr.{format}', [AgentController::class, 'downloadQr'])
        ->whereIn('format', ['png', 'svg'])
        ->name('agents.qr.download');

    Route::get('leads', [LeadController::class, 'index'])->name('leads.index');
    Route::get('leads/export', [LeadController::class, 'export'])->name('leads.export');
    Route::delete('leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');
