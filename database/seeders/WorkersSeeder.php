<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class WorkersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $int= mt_rand(1262055681,1262055681);

        DB::table('workers')->insert([
            'name' => Str::random(10),
            'surname' => Str::random(10),
            'price_hour' => rand(70, 200),
            'address' => Str::random(10),
            'notes' => Str::random(10),
            'status'=>(bool)random_int(0, 1),
            'date_of_billings' => date("Y-m-d H:i:s",$int),

        ]);
    }
}