<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Guide;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GuideSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'slug');
        $authors = User::pluck('id', 'email');

        $guides = [
            // título, resumo, categoria, cidade, autor, publicado?
            ['Como encontrar quarto em Lisboa', 'Dicas para procurar alojamento sem cair em burlas.', 'alojamento', 'Lisboa', 'editor1@example.com', true],
            ['Passe de transportes para estudantes', 'Descontos e como pedir o passe mensal.', 'transportes', 'Lisboa', 'editor1@example.com', true],
            ['Os melhores sítios para almoçar no Porto', 'Pratos do dia a menos de 8 euros.', 'comida', 'Porto', 'editor2@example.com', true],
            ['Número fiscal: como pedir', 'Passo a passo para obter o NIF como estudante estrangeiro.', 'burocracia', 'Lisboa', 'admin@example.com', true],
            ['Associações de estudantes e eventos', 'Como participar na semana de receção e conhecer outros Erasmus.', 'vida-social', 'Coimbra', 'editor2@example.com', true],
            // Rascunhos: não podem aparecer no site público nem na API
            ['Guia de matrícula (rascunho)', 'Ainda em escrita.', 'universidade', 'Porto', 'editor1@example.com', false],
            ['Dicas de inverno (rascunho)', 'A completar.', 'vida-social', 'Coimbra', 'editor2@example.com', false],
            ['Seguro de saúde (rascunho)', 'Por rever pelo administrador.', 'burocracia', 'Lisboa', 'admin@example.com', false],
        ];

        foreach ($guides as [$title, $summary, $category, $city, $author, $published]) {
            // forceCreate porque author_id não está no $fillable
            Guide::forceCreate([
                'title' => $title,
                'slug' => Str::slug($title),
                'summary' => $summary,
                'content' => "<p>{$summary}</p><p>Conteúdo de exemplo para o guia <strong>{$title}</strong>. Este texto serve apenas para testar a aplicação.</p>",
                'category_id' => $categories[$category],
                'author_id' => $authors[$author],
                'city' => $city,
                'status' => $published ? 'published' : 'draft',
                'published_at' => $published ? now() : null,
            ]);
        }
    }
}