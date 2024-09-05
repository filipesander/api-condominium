<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Position::firstOrCreate(['title' => 'Porteiro']);
        Position::firstOrCreate(['title' => 'Segurança']);
        Position::firstOrCreate(['title' => 'Zelador']);
        Position::firstOrCreate(['title' => 'Síndico']);
    }
}