<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Eletrônicos', 'Roupas', 'Alimentos', 'Livros', 'Outros'] as $name) {
            Type::firstOrCreate(['name' => $name]);
        }
    }
}
    