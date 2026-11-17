<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        // Funções fixas do sistema
        DB::table('roles')->insert([
            ['name' => 'registered'], // id 1
            ['name' => 'editor'],     // id 2
            ['name' => 'admin'],      // id 3
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};