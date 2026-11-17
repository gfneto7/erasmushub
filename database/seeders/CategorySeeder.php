<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Alojamento' => 'Como encontrar casa, residências e dicas para arrendar.',
            'Transportes' => 'Passes, metro, autocarros e viagens.',
            'Comida' => 'Onde comer bem e barato.',
            'Burocracia' => 'Documentos, seguros, cartões e prazos.',
            'Vida social' => 'Eventos, associações e como conhecer pessoas.',
            'Universidade' => 'Matrículas, aulas e serviços para estudantes.',
        ];

        foreach ($categories as $name => $description) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $description,
            ]);
        }
    }
}