<?php

namespace Database\Seeders;
use App\Models\Workers;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
class WorkersGenerateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        Workers::factory()->count(150)->create();
    }
}
