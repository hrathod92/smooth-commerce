<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            ['name' => 'Sik Sik Wat', 'category_id' => 1, 'sku' => 'DISH999ABCD', 'price' => 13.49, 'created_at' => Carbon::now()],
            ['name' => 'Huo Guo', 'category_id' => 2, 'sku' => 'DISH234ZFDR', 'price' => 11.99, 'created_at' => Carbon::now()],
            ['name' => 'Cau-Cau', 'category_id' => 3, 'sku' => 'DISH775TGHY', 'price' => 15.29, 'created_at' => Carbon::now()]
        ]);
    }
}
