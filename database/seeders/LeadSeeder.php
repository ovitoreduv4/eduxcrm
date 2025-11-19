<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Message;
use App\Models\Note;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $tags = Tag::all();

        $lead1 = Lead::create([
            'nome' => 'João Silva',
            'telefone' => '5511999999999',
            'status' => 'novo',
            'assigned_to' => $user->id,
            'unread_count' => 1,
            'last_message_at' => now()->subHours(2),
        ]);

        $lead1->tags()->attach($tags->where('name', 'Interessado')->first());
        $lead1->tags()->attach($tags->where('name', 'Quente')->first());

        Message::create([
            'lead_id' => $lead1->id,
            'author' => 'lead',
            'content' => 'Olá, gostaria de mais informações sobre o produto.',
            'status' => 'delivered',
        ]);

        Message::create([
            'lead_id' => $lead1->id,
            'author' => 'user',
            'content' => 'Olá João! Claro, posso te ajudar. Qual produto você gostaria de saber mais?',
            'status' => 'sent',
        ]);

        Note::create([
            'lead_id' => $lead1->id,
            'user_id' => $user->id,
            'content' => 'Cliente demonstrou muito interesse. Priorizar follow-up.',
        ]);

        $lead2 = Lead::create([
            'nome' => 'Maria Santos',
            'telefone' => '5511988888888',
            'status' => 'em_contato',
            'assigned_to' => $user->id,
            'unread_count' => 0,
            'last_message_at' => now()->subHours(5),
        ]);

        $lead2->tags()->attach($tags->where('name', 'Orçamento Enviado')->first());

        Message::create([
            'lead_id' => $lead2->id,
            'author' => 'lead',
            'content' => 'Quanto custa?',
            'status' => 'read',
        ]);

        $lead3 = Lead::create([
            'nome' => 'Pedro Oliveira',
            'telefone' => '5511977777777',
            'status' => 'convertido',
            'last_message_at' => now()->subDays(1),
        ]);

        $lead3->tags()->attach($tags->where('name', 'VIP')->first());

        $lead4 = Lead::create([
            'nome' => 'Ana Costa',
            'telefone' => '5511966666666',
            'status' => 'perdido',
            'last_message_at' => now()->subDays(5),
        ]);
    }
}
