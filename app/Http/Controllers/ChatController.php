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
            'content' => 'required|string',
        ]);

        // Salvar mensagem no banco
        $message = Message::create([
            'lead_id' => $lead->id,
            'author' => 'user',
            'content' => $validated['content'],
        ]);

        // Enviar mensagem via Evolution API
        try {
            $evolutionApiUrl = config('services.evolution.url');
            $evolutionApiKey = config('services.evolution.api_key');
            $instanceName = config('services.evolution.instance_name');

            if ($evolutionApiUrl && $evolutionApiKey && $instanceName) {
                Http::withHeaders([
                    'apikey' => $evolutionApiKey,
                    'Content-Type' => 'application/json',
                ])->post("{$evolutionApiUrl}/message/sendText/{$instanceName}", [
                    'number' => $lead->telefone,
                    'text' => $validated['content'],
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar mensagem via Evolution API: ' . $e->getMessage());
        }

        return response()->json($message, 201);
    }
}
