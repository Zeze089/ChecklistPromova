<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'suporte@promova.net',
            'password' => Hash::make('123456'),
            'is_admin' => true,
        ]);

        // Criar usuário comum para testes
        User::create([
            'name' => 'Usuário Teste',
            'email' => 'usuario@promova.com',
            'password' => Hash::make('123456'),
            'is_admin' => false,
        ]);

    }
}