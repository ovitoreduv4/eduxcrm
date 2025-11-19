<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Message;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function receiveMessage(Request $request)
    {
        // Log do payload recebido para debug
        \Log::info('Webhook recebido', $request->all());

        try {
            // Extrair dados do webhook da Evolution API
            $data = $request->all();

            // Ajuste conforme estrutura real do webhook da Evolution API
            $phone = $data['data']['key']['remoteJid'] ?? null;
            $messageContent = $data['data']['message']['conversation'] ??
                             $data['data']['message']['extendedTextMessage']['text'] ?? null;

            if (!$phone || !$messageContent) {
                return response()->json(['error' => 'Dados inválidos'], 400);
            }

            // Limpar número de telefone (remover @s.whatsapp.net se presente)
            $phone = str_replace('@s.whatsapp.net', '', $phone);

            // Buscar ou criar lead
            $lead = Lead::firstOrCreate(
                ['telefone' => $phone],
                ['nome' => 'Lead ' . substr($phone, -4), 'status' => 'novo']
            );

            // Salvar mensagem
            Message::create([
                'lead_id' => $lead->id,
                'author' => 'lead',
                'content' => $messageContent,
            ]);

            // Incrementar contador de não lidas e atualizar last_message_at
            $lead->increment('unread_count');
            $lead->update(['last_message_at' => now()]);

            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            \Log::error('Erro ao processar webhook: ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno'], 500);
        }
    }
}
