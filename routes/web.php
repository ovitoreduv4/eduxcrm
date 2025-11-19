<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\MessageTemplateController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rotas do perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas de Leads
    Route::resource('leads', LeadController::class);

    // Funil Kanban
    Route::get('/kanban', [KanbanController::class, 'index'])->name('kanban.index');
    Route::patch('/kanban/{lead}/status', [KanbanController::class, 'updateStatus'])->name('kanban.updateStatus');

    // Rotas do Chat
    Route::get('/leads/{lead}/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/leads/{lead}/messages', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/templates', [ChatController::class, 'getTemplates'])->name('chat.templates');

    // Notas
    Route::post('/leads/{lead}/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

    // Tags
    Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
    Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
    Route::delete('/tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');

    // Templates de Mensagens
    Route::get('/message-templates', [MessageTemplateController::class, 'index'])->name('templates.index');
    Route::post('/message-templates', [MessageTemplateController::class, 'store'])->name('templates.store');
    Route::delete('/message-templates/{template}', [MessageTemplateController::class, 'destroy'])->name('templates.destroy');
});

// Rota de webhook (sem autenticação)
Route::post('/webhook/messages', [WebhookController::class, 'receiveMessage'])->name('webhook.receive');

require __DIR__.'/auth.php';
