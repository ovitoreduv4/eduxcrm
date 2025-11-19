<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return redirect()->route('leads.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Rotas do perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas de Leads
    Route::resource('leads', LeadController::class);

    // Rotas do Chat
    Route::get('/leads/{lead}/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/leads/{lead}/messages', [ChatController::class, 'sendMessage'])->name('chat.send');
});

// Rota de webhook (sem autenticação)
Route::post('/webhook/messages', [WebhookController::class, 'receiveMessage'])->name('webhook.receive');

require __DIR__.'/auth.php';
