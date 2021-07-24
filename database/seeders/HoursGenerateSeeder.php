<?php

namespace Database\Seeders;

use App\Models\Hours;

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
// use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class HoursGenerateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {


        foreach (range(1, 30) as $index) {
            $i = $index;
            $ip = $index + 1;
            $company = Hours::create([
                'workers_id' => $i,
                // 'workers_price_hour'=>DB::table('workers')->where('id', $i)->value('price_hour'),
                                'workers_price_hour'=>100,

                'contrahents_id' => $ip,
                'contrahents_salary_cash'=>DB::table('contrahents')->where('id', $ip)->value('salary_cash'),
                'contrahents_salary_invoice'=>DB::table('contrahents')->where('id', $ip)->value('salary_invoice'),
                'work_day'=>$faker->dateTimeBetween('-2 years', 'now'),
                'hours'=>9,
                'status_of_billings'=>$faker->numberBetween(0,1),

            ]);
        }

        // Hours::factory()->count(150)->create();
    }
}