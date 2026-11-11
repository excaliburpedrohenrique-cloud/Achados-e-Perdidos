<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitacaos', function (Blueprint $table) {
            $table->foreignId('usuario_id')->nullable()->change();
            $table->string('nome')->nullable();
            $table->string('cpf', 255)->nullable();
            $table->string('email')->nullable();
            $table->string('telefone', 30)->nullable();
        });
    }

    public function down(): void
    {
        if (DB::table('solicitacaos')->whereNull('usuario_id')->exists()) {
            throw new RuntimeException('Não é possível reverter: existem solicitações públicas sem usuário vinculado.');
        }

        Schema::table('solicitacaos', function (Blueprint $table) {
            $table->dropColumn(['nome', 'cpf', 'email', 'telefone']);
            $table->foreignId('usuario_id')->nullable(false)->change();
        });
    }
};
