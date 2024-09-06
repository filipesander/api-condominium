<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('pt-BR');

        for($i = 0; $i < 50; $i++){
            $floor = $faker->numberBetween(1, 22);
            $apartmentSuffix = $faker->numberBetween(1, 4);
            $apartmentNumber = ($floor * 100) + $apartmentSuffix;

            DB::table('owners')->insert([
                "name" => $faker->name(),
                "cpf" => $faker->unique()->numerify('###########'),
                "number" => $faker->unique()->phoneNumber(),
                "birth_date" => $faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
                "email" => $faker->unique()->safeEmail(),
                "tower" => $faker->numberBetween(1,3),
                "apartment_number" => $apartmentNumber,
                "garage" => $faker->randomNumber(4),
                "rented" => $faker->numberBetween(0, 1),
                "paid" => $faker->numberBetween(0, 1),
            ]);
        }
    }
}