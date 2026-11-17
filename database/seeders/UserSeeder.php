<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::pluck('id', 'name'); // ['registered' => 1, 'editor' => 2, 'admin' => 3]

        $users = [
            // nome, email, papel, ativo
            ['Admin ErasmusHub', 'admin@example.com', 'admin', true],
            ['Editor Um', 'editor1@example.com', 'editor', true],
            ['Editor Dois', 'editor2@example.com', 'editor', true],
            ['Utilizador Registado', 'user@example.com', 'registered', true],
            ['Conta Desativada', 'inactive@example.com', 'registered', false],
        ];

        foreach ($users as [$name, $email, $role, $active]) {
            // forceCreate ignora o $fillable: role_id e is_active
            // só podem ser definidos pelo servidor, nunca pelo utilizador
            User::forceCreate([
                'name' => $name,
                'email' => $email,
                'password' => 'password', // o cast 'hashed' do User faz o hash
                'role_id' => $roles[$role],
                'is_active' => $active,
                'email_verified_at' => now(),
            ]);
        }
    }
}