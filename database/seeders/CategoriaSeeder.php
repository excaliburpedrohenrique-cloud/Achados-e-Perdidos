<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        Categoria::firstOrCreate(
            ['nome' => 'Material Escolar'],
            ['descricao' => 'Materiais escolares encontrados ou perdidos.'],
        );

        Categoria::firstOrCreate(
            ['nome' => 'Objeto pessoal'],
            ['descricao' => 'Objetos pessoais encontrados ou perdidos.'],
        );
    }
}
