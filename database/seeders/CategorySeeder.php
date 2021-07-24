<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('category')->insert([
            'name' => 'energia elektryczna',

        ]);
        DB::table('category')->insert([
            'name' => 'opłaty',

        ]);
        DB::table('category')->insert([
            'name' => 'serwis',

        ]);
        DB::table('category')->insert([
            'name' => 'zaliczki',

        ]);
        DB::table('category')->insert([
            'name' => 'premie',

        ]);
        DB::table('category')->insert([
            'name' => 'wyplaty',

        ]);
        DB::table('category')->insert([
            'name' => 'koszty pozostałe',

        ]);
        DB::table('category')->insert([
            'name' => 'bramki',

        ]);
        DB::table('category')->insert([
            'name' => 'paliwo',

        ]);
        DB::table('category')->insert([
            'name' => 'zaliczka od klienta',

        ]);
    
    }
}