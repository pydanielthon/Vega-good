<?php

namespace Database\Seeders;
use App\Models\Contrahents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
class ContrahentsGenerateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        Contrahents::factory()->count(50)->create();
    }
}