<?php

use App\Size;
use Illuminate\Database\Seeder;

class SizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        foreach (range(1, 10) as $index) {
            Size::create([
                'text' => $faker->text(50),
                'price' => $faker->randomFloat(2, 0, 40)
            ]);
        }
    }
}

