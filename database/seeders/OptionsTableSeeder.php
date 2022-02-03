<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Option;
use Illuminate\Support\Facades\DB;
class OptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $graphics = [
            'AMD Radeon RX 560X',
            'AMD Radeon RX 560',
            'NVIDIA Quadro M2200',
            'AMD Radeon RX 460 ',
            'AMD Radeon R9 M390',
            'Intel Iris Xe MAX Graphics',
            'AMD Radeon Pro 560X',
            'AMD Radeon Pro 560',
            'NVIDIA GeForce GTX 950M',
            'NVIDIA Quadro M1000M'
        ];

        $ram = [
            '2GB','4GB','8GB','12GB','16GB','33GB','More than 32GB'
        ];

        $room = [
            '180GB','320GB','500GB','1TB','2TB','5TB','More than 5TB'
        ];

        $type =[
            '2 in 1',
            'Gaming',
            'Netbook',
            'Notebook',
        ];
        $processor = [
            '9th Gen Intel Core i7',
            '10th Gen Intel Core i3',
            '10th Gen Intel Core i5',
            '10th Gen Intel Core i7',
            'AMD Ryzen 7 4800H',
            'Intel Core i7-10750H',
            'Intel Core i7-10875H',
            'AMD Ryzen 5 4500U',
            '11th Gen Intel Core i5',
            '11th Gen Intel Core i7'
        ];

        $options=[
            [

                'key'    => 'ram',
                'code'   => 'RAM',
                'values' => $ram
            ],
            [
                'key'    => 'room',
                'code'   => 'HDD Capacity',
                'values' => $room
            ],
            [
                'key'    => 'graphics_card',
                'code'   => 'Graphics Card',
                'values' => $graphics
            ],
            [
                'key'    => 'type',
                'code'   => 'Type',
                'values' => $type
            ],
            [
                'key'    => 'processor',
                'code'   => 'Processor',
                'values' => $processor
            ],
        ];

        foreach($options as $option){
            $op= new Option();
            $op->create($option);
        }
        //Option::create($options);

    }
}
