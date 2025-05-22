<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        Storage::fake('public'); // Fake the public disk
    }

    /** @test */
    public function index_displays_products()
    {
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee($product1->nombre); // 'nombre' is the name field for Product
        $response->assertSee($product2->nombre);
    }

    /** @test */
    public function create_page_loads_successfully_and_displays_categories()
    {
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        $response = $this->get(route('products.create'));

        $response->assertStatus(200);
        $response->assertSee('Crear Producto'); // Assuming Spanish based on previous controller views
        $response->assertSee($category1->name);
        $response->assertSee($category2->name);
    }

    /** @test */
    public function store_creates_new_product_with_image_and_redirects()
    {
        $category = Category::factory()->create();
        $productData = Product::factory()->make([
            'category_id' => $category->id,
        ])->toArray();

        $file = UploadedFile::fake()->image('product.jpg');
        $productDataWithFile = array_merge($productData, ['image' => $file]);

        $response = $this->post(route('products.store'), $productDataWithFile);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['nombre' => $productData['nombre'], 'barcode' => $productData['barcode']]);
        
        $product = Product::firstWhere('barcode', $productData['barcode']);
        $this->assertNotNull($product->image_path);
        Storage::disk('public')->assertExists($product->image_path); // Path is relative to 'public/storage'
        $response->assertSessionHas('success');
    }

    /** @test */
    public function store_fails_with_invalid_data_missing_name()
    {
        $category = Category::factory()->create();
        $productData = Product::factory()->make([
            'category_id' => $category->id,
            'nombre' => '', // Invalid: name is missing
        ])->toArray();
        
        $file = UploadedFile::fake()->image('product.jpg');
        $productDataWithFile = array_merge($productData, ['image' => $file]);

        $response = $this->post(route('products.store'), $productDataWithFile);

        $response->assertSessionHasErrors('nombre');
        $this->assertDatabaseMissing('products', ['barcode' => $productData['barcode']]);
    }

    /** @test */
    public function store_fails_with_duplicate_barcode()
    {
        $existingProduct = Product::factory()->create();
        $category = Category::factory()->create();
        $productData = Product::factory()->make([
            'category_id' => $category->id,
            'barcode' => $existingProduct->barcode, // Duplicate barcode
        ])->toArray();

        $file = UploadedFile::fake()->image('product.jpg');
        $productDataWithFile = array_merge($productData, ['image' => $file]);

        $response = $this->post(route('products.store'), $productDataWithFile);

        $response->assertSessionHasErrors('barcode');
        $this->assertEquals(1, Product::where('barcode', $existingProduct->barcode)->count());
    }
    
    /** @test */
    public function store_fails_if_category_does_not_exist()
    {
        $productData = Product::factory()->make([
            'category_id' => 999, // Non-existent category
        ])->toArray();

        $file = UploadedFile::fake()->image('product.jpg');
        $productDataWithFile = array_merge($productData, ['image' => $file]);

        $response = $this->post(route('products.store'), $productDataWithFile);

        $response->assertSessionHasErrors('category_id');
        $this->assertDatabaseMissing('products', ['barcode' => $productData['barcode']]);
    }

    /** @test */
    public function show_displays_product_with_category()
    {
        $product = Product::factory()->create(); // Factory will create associated category

        $response = $this->get(route('products.show', $product));

        $response->assertStatus(200);
        $response->assertSee($product->nombre);
        $response->assertSee($product->category->name); // Accessing related category's name
    }

    /** @test */
    public function edit_page_loads_successfully_and_displays_categories()
    {
        $product = Product::factory()->create();
        $otherCategory = Category::factory()->create(); // Another category for the dropdown

        $response = $this->get(route('products.edit', $product));

        $response->assertStatus(200);
        $response->assertSee('Editar Producto');
        $response->assertSee($product->nombre);
        $response->assertSee($product->barcode);
        $response->assertSee($product->category->name); // Current category selected/displayed
        $response->assertSee($otherCategory->name); // Other category available in dropdown
    }

    /** @test */
    public function update_modifies_product_with_new_image_and_redirects()
    {
        $product = Product::factory()->create(['image_path' => UploadedFile::fake()->image('old_product.jpg')->store('products', 'public')]);
        $oldImagePath = $product->image_path;

        $newCategory = Category::factory()->create();
        $updatedProductData = Product::factory()->make([
            'category_id' => $newCategory->id,
            'nombre' => 'Updated Product Name',
        ])->toArray();

        $newFile = UploadedFile::fake()->image('new_product.jpg');
        $updatedProductDataWithFile = array_merge($updatedProductData, ['image' => $newFile]);

        $response = $this->put(route('products.update', $product), $updatedProductDataWithFile);

        $response->assertRedirect(route('products.index')); // Or show, $product
        $product->refresh();

        $this->assertEquals('Updated Product Name', $product->nombre);
        $this->assertEquals($newCategory->id, $product->category_id);
        $this->assertNotNull($product->image_path);
        $this->assertNotEquals($oldImagePath, $product->image_path);
        Storage::disk('public')->assertMissing($oldImagePath);
        Storage::disk('public')->assertExists($product->image_path);
        $response->assertSessionHas('success');
    }

    /** @test */
    public function update_modifies_product_without_new_image()
    {
        $initialImagePath = 'products/initial_image.jpg';
        Storage::disk('public')->put($initialImagePath, UploadedFile::fake()->image('initial_image.jpg')->getContent());
        
        $product = Product::factory()->create(['image_path' => $initialImagePath]);

        $updatedProductData = [
            'nombre' => 'Updated Name No New Image',
            'precio_menudeo' => 123.45,
            // Send other required fields from factory, ensuring they are valid
            'barcode' => $product->barcode, // Keep barcode same or ensure it's valid and unique if changed
            'category_id' => $product->category_id,
            'precio_mayoreo' => $product->precio_mayoreo,
            'stock_actual' => $product->stock_actual,
        ];

        $response = $this->put(route('products.update', $product), $updatedProductData);

        $response->assertRedirect(route('products.index')); // Or show, $product
        $product->refresh();

        $this->assertEquals('Updated Name No New Image', $product->nombre);
        $this->assertEquals(123.45, $product->precio_menudeo);
        $this->assertEquals($initialImagePath, $product->image_path); // Image path should not change
        Storage::disk('public')->assertExists($initialImagePath); // Original image should still exist
        $response->assertSessionHas('success');
    }

    /** @test */
    public function update_fails_with_invalid_data()
    {
        $product = Product::factory()->create();
        $originalName = $product->nombre;

        $invalidData = array_merge(
            Product::factory()->make(['category_id' => $product->category_id])->toArray(),
            ['nombre' => ''] // Invalid: name is missing
        );
        // Remove image from data to test only validation of other fields, or add a fake one if required by request rules
        unset($invalidData['image']);


        $response = $this->put(route('products.update', $product), $invalidData);

        $response->assertSessionHasErrors('nombre');
        $this->assertDatabaseHas('products', ['id' => $product->id, 'nombre' => $originalName]);
    }

    /** @test */
    public function update_fails_with_duplicate_barcode()
    {
        $productToUpdate = Product::factory()->create();
        $otherProductWithBarcode = Product::factory()->create();
        $originalBarcode = $productToUpdate->barcode;

        $invalidData = array_merge(
            Product::factory()->make([
                'category_id' => $productToUpdate->category_id,
                'nombre' => $productToUpdate->nombre, // keep other fields valid
            ])->toArray(),
            ['barcode' => $otherProductWithBarcode->barcode] // Duplicate barcode
        );
        unset($invalidData['image']);

        $response = $this->put(route('products.update', $productToUpdate), $invalidData);

        $response->assertSessionHasErrors('barcode');
        $this->assertDatabaseHas('products', ['id' => $productToUpdate->id, 'barcode' => $originalBarcode]);
    }

    /** @test */
    public function update_fails_if_category_does_not_exist()
    {
        $product = Product::factory()->create();
        $originalCategoryId = $product->category_id;

        $invalidData = array_merge(
            Product::factory()->make([
                'nombre' => $product->nombre, // keep other fields valid
                'barcode' => $product->barcode,
            ])->toArray(),
            ['category_id' => 999] // Non-existent category
        );
        unset($invalidData['image']);

        $response = $this->put(route('products.update', $product), $invalidData);

        $response->assertSessionHasErrors('category_id');
        $this->assertDatabaseHas('products', ['id' => $product->id, 'category_id' => $originalCategoryId]);
    }

    /** @test */
    public function destroy_deletes_product_and_image_and_redirects()
    {
        // Manually ensure an image file exists for the product to delete
        $image = UploadedFile::fake()->image('product_to_delete.jpg');
        $imagePath = $image->store('products', 'public'); // Store it and get path

        $product = Product::factory()->create(['image_path' => $imagePath]);
        
        // Ensure the file exists before deletion attempt
        Storage::disk('public')->assertExists($imagePath);

        $response = $this->delete(route('products.destroy', $product));

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        Storage::disk('public')->assertMissing($imagePath); // Check if the specific image file was deleted
        $response->assertSessionHas('success');
    }
}
