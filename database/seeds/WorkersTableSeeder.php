<?php

use Illuminate\Database\Seeder;
use App\Worker;

class WorkersTableSeeder extends Seeder
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

        for ($i = 0; $i < 50000; $i++) {
          $data = $faker->date($format = 'Y-m-d', $max = '1990-12-31');
          $dataW = new DateTime($data);
          $dataW->modify(''.rand(18,22).' year')->modify(''.rand(1,12).' month')->modify(''.rand(1,31).' day');
          $fio = explode(" ", $faker->name);
          Worker::firstOrCreate([
              'table_number' => $i+100000,
              'surname' => $fio[0],
              'name' => $fio[1],
              'patronymic' => $fio[2],
              'birthday' => $data,
              'position_id' => rand(1,100),
              'salary' => $faker->randomNumber(5),
              'reception_date' => $dataW->format('Y-m-d'),
            ]);
        }
    }
}
