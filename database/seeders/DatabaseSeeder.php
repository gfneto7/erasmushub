<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // A ordem importa: os guias precisam de utilizadores e categorias
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            GuideSeeder::class,
        ]);
    }
}