<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('objetos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('categoria_id')->constrained('categorias')->cascadeOnDelete();

            $table->string('titulo');
            $table->string('local');
            $table->string('turno');

            $table->text('descricao')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status', ['disponível', 'devolvido'])->default('disponível');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objetos');
    }
};
