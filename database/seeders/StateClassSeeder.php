<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class StateClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $states =  [
            [
              'name' => 'Province No. 1',
              
            ],
            [
              'name' => 'Madhesh Province',
              
            ],
            [
              'name' => 'Bagmati Province',
              
            ],
            [
              'name' => 'Gandaki Province',
              
            ],
            [
                'name' => 'Lumbini Province',
            ],
            [
                'name' => 'Karnali Province',
            ],
            [
                'name' => 'Sudurpashchim Province',
            ]

          ];

          DB::table('states')->insert($states);
    }
}
