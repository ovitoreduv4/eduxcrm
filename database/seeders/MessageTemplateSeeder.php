<?php

namespace Database\Seeders;

use App\Models\MessageTemplate;
use Illuminate\Database\Seeder;

class MessageTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Saudação Inicial',
                'content' => 'Olá! Como posso ajudar você hoje?',
                'user_id' => null, // Template global
            ],
            [
                'name' => 'Agradecimento',
                'content' => 'Obrigado pelo contato! Em breve retornaremos.',
                'user_id' => null,
            ],
            [
                'name' => 'Informações Adicionais',
                'content' => 'Preciso de mais informações para melhor atendê-lo. Pode me passar mais detalhes?',
                'user_id' => null,
            ],
            [
                'name' => 'Orçamento',
                'content' => 'Segue nosso orçamento conforme solicitado...',
                'user_id' => null,
            ],
        ];

        foreach ($templates as $template) {
            MessageTemplate::create($template);
        }
    }
}
