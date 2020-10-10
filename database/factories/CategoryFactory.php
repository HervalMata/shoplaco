<?php

namespace Database\Factories;

use App\Models\Category;
use Carbon\Carbon;
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
        return [
            'category_name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
