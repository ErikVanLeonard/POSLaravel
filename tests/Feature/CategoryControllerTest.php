<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function index_displays_categories()
    {
        $category1 = Category::factory()->create(['name' => 'Electronics']);
        $category2 = Category::factory()->create(['name' => 'Books']);

        $response = $this->get(route('categories.index'));

        $response->assertStatus(200);
        $response->assertSee($category1->name);
        $response->assertSee($category2->name);
    }

    /** @test */
    public function create_page_loads_successfully()
    {
        $response = $this->get(route('categories.create'));

        $response->assertStatus(200);
        // Assuming the view contains the text "Crear Nueva Categoría"
        // This might need adjustment based on actual view content
        $response->assertSee('Crear Nueva Categoría');
    }

    /** @test */
    public function store_creates_new_category_and_redirects()
    {
        $categoryData = ['name' => 'New Awesome Category'];

        $response = $this->post(route('categories.store'), $categoryData);

        // Assuming redirection to categories.index
        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', $categoryData);
        $response->assertSessionHas('success'); // Assuming a success message is flashed
    }

    /** @test */
    public function store_fails_with_invalid_data()
    {
        // Assuming 'name' is required
        $invalidData = ['name' => ''];

        $response = $this->post(route('categories.store'), $invalidData);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('categories', ['name' => '']); // Ensure no category with empty name was created
        // Also, check that the count of categories hasn't changed if that's easier
        $this->assertEquals(0, Category::count());
    }

    /** @test */
    public function show_displays_category()
    {
        $category = Category::factory()->create();

        $response = $this->get(route('categories.show', $category));

        $response->assertStatus(200);
        $response->assertSee($category->name);
    }

    /** @test */
    public function edit_page_loads_successfully()
    {
        $category = Category::factory()->create();

        $response = $this->get(route('categories.edit', $category));

        $response->assertStatus(200);
        $response->assertSee($category->name); // Check if current name is in the form
        $response->assertSee('Editar Categoría'); // Or similar text from the view
    }

    /** @test */
    public function update_modifies_category_and_redirects()
    {
        $category = Category::factory()->create();
        $updatedData = ['name' => 'Updated Category Name'];

        $response = $this->put(route('categories.update', $category), $updatedData);

        $response->assertRedirect(route('categories.index')); // Or categories.show, $category
        $this->assertDatabaseHas('categories', array_merge(['id' => $category->id], $updatedData));
        $response->assertSessionHas('success');
    }

    /** @test */
    public function update_fails_with_invalid_data()
    {
        $category = Category::factory()->create(['name' => 'Original Name']);
        // Assuming 'name' is required
        $invalidData = ['name' => ''];

        $response = $this->put(route('categories.update', $category), $invalidData);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Original Name']); // Ensure name hasn't changed
    }

    /** @test */
    public function destroy_deletes_category_and_redirects()
    {
        $category = Category::factory()->create();

        $response = $this->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $response->assertSessionHas('success');
    }
}
