<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Message;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lead1 = Lead::create([
            'nome' => 'João Silva',
            'telefone' => '5511999999999',
            'status' => 'novo',
        ]);

        Message::create([
            'lead_id' => $lead1->id,
            'author' => 'lead',
            'content' => 'Olá, gostaria de mais informações sobre o produto.',
        ]);

        Message::create([
            'lead_id' => $lead1->id,
            'author' => 'user',
            'content' => 'Olá João! Claro, posso te ajudar. Qual produto você gostaria de saber mais?',
        ]);

        $lead2 = Lead::create([
            'nome' => 'Maria Santos',
            'telefone' => '5511988888888',
            'status' => 'em_contato',
        ]);

        Message::create([
            'lead_id' => $lead2->id,
            'author' => 'lead',
            'content' => 'Quanto custa?',
        ]);

        $lead3 = Lead::create([
            'nome' => 'Pedro Oliveira',
            'telefone' => '5511977777777',
            'status' => 'convertido',
        ]);
    }
}
