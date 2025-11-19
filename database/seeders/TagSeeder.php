<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Interessado', 'color' => '#10B981'],
            ['name' => 'Quente', 'color' => '#EF4444'],
            ['name' => 'Orçamento Enviado', 'color' => '#3B82F6'],
            ['name' => 'Follow-up', 'color' => '#F59E0B'],
            ['name' => 'VIP', 'color' => '#8B5CF6'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
