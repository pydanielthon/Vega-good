<?php

namespace Database\Seeders;
use App\Models\Logs;
use Illuminate\Database\Seeder;

class LogsGenerateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach (range(1, 5000) as $index) {
            $i = $index;
            $ip = $index + 1;
            $company = Logs::create([
                'user_id' => 1,
                'name'=>"Test".$i,
                'worker_id' => 492,
                'contrahent_id' => 1,
                'hour_id' => 6,
                'billing_id' => 3,
                'notes' => 'Lorem ipsum dolor sit amet'. $i,

            ]);
        }
    }
}