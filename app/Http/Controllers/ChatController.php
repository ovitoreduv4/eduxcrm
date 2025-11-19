<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function getMessages(Lead $lead)
    {
        $messages = $lead->messages()->orderBy('created_at', 'asc')->get();
        return response()->json($messages);
    }

    public function sendMessage(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'content' => 'required_without:attachment|string|nullable',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);

        $attachmentPath = null;
        $attachmentType = null;

        // Upload de anexo
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentPath = $file->store('attachments', 'public');
            $attachmentType = $file->getClientOriginalExtension();
        }

        // Salvar mensagem no banco
        $message = Message::create([
            'lead_id' => $lead->id,
            'author' => 'user',
            'content' => $validated['content'] ?? '',
            'attachment_path' => $attachmentPath,
            'attachment_type' => $attachmentType,
        ]);

        // Atualizar last_message_at
        $lead->update(['last_message_at' => now()]);

        // Enviar mensagem via Evolution API
        try {
            $evolutionApiUrl = config('services.evolution.url');
            $evolutionApiKey = config('services.evolution.api_key');
            $instanceName = config('services.evolution.instance_name');

            if ($evolutionApiUrl && $evolutionApiKey && $instanceName) {
                if ($attachmentPath) {
                    // Enviar arquivo
                    Http::withHeaders([
                        'apikey' => $evolutionApiKey,
                    ])->attach('attachment', file_get_contents(storage_path('app/public/' . $attachmentPath)))
                      ->post("{$evolutionApiUrl}/message/sendMedia/{$instanceName}", [
                        'number' => $lead->telefone,
                        'caption' => $validated['content'] ?? '',
                    ]);
                } else {
                    // Enviar texto
                    Http::withHeaders([
                        'apikey' => $evolutionApiKey,
                        'Content-Type' => 'application/json',
                    ])->post("{$evolutionApiUrl}/message/sendText/{$instanceName}", [
                        'number' => $lead->telefone,
                        'text' => $validated['content'],
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar mensagem via Evolution API: ' . $e->getMessage());
        }

        return response()->json($message, 201);
    }

    public function getTemplates()
    {
        $templates = \App\Models\MessageTemplate::where('user_id', auth()->id())
            ->orWhereNull('user_id')
            ->get();

        return response()->json($templates);
    }
}
