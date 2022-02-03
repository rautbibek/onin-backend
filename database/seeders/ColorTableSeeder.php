<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use App\Models\ColorFamily;
class ColorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // DB::table('bt_color_families')->delete();
        $colors = [
            [
                'name'=>'Pink',
                'code'=>'#FFC0CB',
                'created_at'=>now(),
                'updated_at'=>now(),

            ],
            [
                'name'=>'Gold',
                'code'=>'#FFD700',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'name'=>'green',
                'code'=>'#008000',
                'created_at'=>now(),
                'updated_at'=>now(),
            ]
        ];

        DB::table('color_families')->insert($colors);
    }
}
