<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
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
        return [
            'nombre' => $this->faker->word, // Changed 'name' to 'nombre'
            // 'description' => $this->faker->sentence, // Removed description
            'precio_menudeo' => $this->faker->randomFloat(2, 1, 100), // Assuming 'price' corresponds to 'precio_menudeo'
            'precio_mayoreo' => $this->faker->randomFloat(2, 10, 200),
            'stock_actual' => $this->faker->numberBetween(0, 100),
            'barcode' => $this->faker->ean13,
            'category_id' => Category::factory(),
        ];
    }
}
