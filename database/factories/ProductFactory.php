<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $categories = Category::all();
        $category = $categories->random();
        return [
            'product_name' => $this->faker->word,
            'product_code' => $this->faker->uuid,
            'description' => $this->faker->paragraph,
            'stock' => $this->faker->numberBetween(1, 10),
            'featured' => $this->faker->numberBetween(0, 1),
            'price' => $this->faker->randomFloat(2, 1, 12),
            'category_id' => $category->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
