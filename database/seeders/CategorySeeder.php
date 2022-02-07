<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            ['name' => 'Ethiopia, Meat, Beef, Chili pepper', 'created_at' => Carbon::now()],
            ['name' => 'China, Meat, Beef, Fish, Tofu, Sichuan pepper', 'created_at' => Carbon::now()],
            ['name' => 'Peru, Potato, Yellow Chili pepper', 'created_at' => Carbon::now()]
        ]);
    }
}
