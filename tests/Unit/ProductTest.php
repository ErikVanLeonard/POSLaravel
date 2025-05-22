<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function attributes_are_fillable()
    {
        $category = Category::factory()->create();

        $data = [
            'nombre' => 'Test Product',
            'barcode' => '1234567890123',
            'precio_menudeo' => 10.99,
            'precio_mayoreo' => 9.99,
            'stock_actual' => 100,
            'category_id' => $category->id,
            'image_path' => 'path/to/image.jpg',
        ];

        $product = new Product($data);

        $this->assertEquals($data['nombre'], $product->nombre);
        $this->assertEquals($data['barcode'], $product->barcode);
        $this->assertEquals($data['precio_menudeo'], $product->precio_menudeo);
        $this->assertEquals($data['precio_mayoreo'], $product->precio_mayoreo);
        $this->assertEquals($data['stock_actual'], $product->stock_actual);
        $this->assertEquals($data['category_id'], $product->category_id);
        $this->assertEquals($data['image_path'], $product->image_path);
    }

    /** @test */
    public function product_belongs_to_a_category()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($category->id, $product->category->id);
    }
}
