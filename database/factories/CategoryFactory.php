<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->name();
        return [

                'name' => $this->faker->name(),
                'slug' => $this->faker->name(),
                'meta_title' => $this->faker->sentence(),
                'icon' => 'icon',
                'cover' => $this->faker->name(),
                'is_featured' => true,

        ];
    }
}
