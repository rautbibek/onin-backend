<?php

namespace Database\Seeders;
use App\Models\ProductType;
use Illuminate\Database\Seeder;

class ProductTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            [
                'title' => 'laptops',
                'field' => [
                    [
                        'name' => 'color',
                        'type' => 'text',
                        'as' => 'Color'
                    ],
                    [
                        'name' => 'ram',
                        'type' => 'text',
                        'as' => 'Ram'
                    ],
                    [
                        'name' => 'ROM',
                        'type' => 'text',
                        'as' => 'ROM(HDD)'
                    ]

                ],
                'cart_system' => true
            ],
            [
                'title' => 'clothes',
                'field' => [
                    [
                        'name' => 'color',
                        'type' => 'text',
                        'as' => 'Color'
                    ],
                    [
                        'name' => 'size',
                        'type' => 'text',
                        'as' => 'Size'
                    ]
                ],
                'cart_system' => true
            ]


        ];
        foreach ($types as $type){

            $productType = ProductType::where('title', $type['title'])->first();

            if($productType){
                $productType->field = json_encode($type['field']);
                $productType->cart_system = $type['cart_system'];
                $productType->update();
                continue;
            }

            $productType = new ProductType();
            $productType->title = $type['title'];
            $productType->field = json_encode($type['field']);
            $productType->cart_system = $type['cart_system'];
            $productType->save();
        }
    }
}
