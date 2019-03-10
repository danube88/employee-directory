<?php

use Illuminate\Database\Seeder;
use App\Position;

class PositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = \Faker\Factory::create('ru_RU');

        // Create 50 product records
        for ($i = 0; $i < 100; $i++) {
          if($i == 0){
            $level = 1;
          }
          if($i > 0 && $i < 20){
            $level = 2;
          }
          if($i > 20 && $i < 40){
            $level = 3;
          }
          if($i > 40 && $i < 60){
            $level = 4;
          }
          if($i > 60 && $i < 100){
            $level = 5;
          }
          Position::firstOrCreate([
              'name_position' => $faker->jobTitle,
              'default_salary' => $faker->randomNumber(5),
              'level' => $level
          ]);
        }
    }
}
