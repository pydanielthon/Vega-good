<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ContrahentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contrahents')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10)."@gmail.com",
            'salary_cash' => rand(70, 200),
            'salary_invoice' => rand(70, 200),
            'status' => (bool)random_int(0, 1),
            'notes' => Str::random(10),
        ]);
    }
}