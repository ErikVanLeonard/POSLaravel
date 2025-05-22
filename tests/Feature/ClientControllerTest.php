<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientControllerTest extends TestCase
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
    public function index_displays_clients()
    {
        $client1 = Client::factory()->create();
        $client2 = Client::factory()->create();

        $response = $this->get(route('clients.index'));

        $response->assertStatus(200);
        $response->assertSee($client1->name);
        $response->assertSee($client2->name);
    }

    /** @test */
    public function create_page_loads_successfully()
    {
        $response = $this->get(route('clients.create'));

        $response->assertStatus(200);
        // Assuming the view contains the text "Nuevo Cliente"
        $response->assertSee('Nuevo Cliente');
    }

    /** @test */
    public function store_creates_new_client_and_redirects()
    {
        $clientData = Client::factory()->make([
            'rfc' => 'XAXX010101000', // Use a known simple valid RFC format
        ])->toArray();

        $response = $this->post(route('clients.store'), $clientData);

        $response->assertRedirect(route('clients.index'));
        $this->assertDatabaseHas('clients', ['email' => $clientData['email'], 'rfc' => 'XAXX010101000']);
        $response->assertSessionHas('success');
    }

    /** @test */
    public function store_fails_with_invalid_rfc()
    {
        $clientData = Client::factory()->make(['rfc' => 'INVALIDRFC'])->toArray();

        $response = $this->post(route('clients.store'), $clientData);

        $response->assertSessionHasErrors('rfc');
        $this->assertDatabaseMissing('clients', ['rfc' => 'INVALIDRFC']);
    }

    /** @test */
    public function store_fails_with_invalid_email()
    {
        $clientData = Client::factory()->make(['email' => 'not-an-email'])->toArray();

        $response = $this->post(route('clients.store'), $clientData);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('clients', ['email' => 'not-an-email']);
    }

    /** @test */
    public function store_fails_with_duplicate_rfc()
    {
        $existingClient = Client::factory()->create();
        $clientData = Client::factory()->make(['rfc' => $existingClient->rfc])->toArray();

        $response = $this->post(route('clients.store'), $clientData);

        $response->assertSessionHasErrors('rfc');
        $this->assertEquals(1, Client::where('rfc', $existingClient->rfc)->count());
    }

    /** @test */
    public function store_fails_with_duplicate_email()
    {
        $existingClient = Client::factory()->create(); // This client has a valid RFC and email
        
        // Generate a new set of data using the factory, which should be valid by default
        $newClientData = Client::factory()->make()->toArray();
        // Now, only override the email to make it a duplicate of the existing client's email
        $newClientData['email'] = $existingClient->email;

        $response = $this->post(route('clients.store'), $newClientData);

        $response->assertSessionHasErrors('email');
        // Ensure that the 'rfc' field did not cause an error. If it does, the factory is inconsistent with validation rules.
        $response->assertSessionDoesntHaveErrors(['rfc', 'name', 'company', 'address', 'phone']);
        $this->assertEquals(1, Client::where('email', $existingClient->email)->count());
    }

    /** @test */
    public function show_displays_client()
    {
        $client = Client::factory()->create();

        $response = $this->get(route('clients.show', $client));

        $response->assertStatus(200);
        $response->assertSee($client->name);
        $response->assertSee($client->rfc);
        $response->assertSee($client->email);
    }

    /** @test */
    public function edit_page_loads_successfully()
    {
        $client = Client::factory()->create();

        $response = $this->get(route('clients.edit', $client));

        $response->assertStatus(200);
        $response->assertSee($client->name);
        $response->assertSee($client->email);
        // Assuming the view contains the text "Editar Cliente"
        $response->assertSee('Editar Cliente');
    }

    /** @test */
    public function update_modifies_client_and_redirects()
    {
        $client = Client::factory()->create();
        $updatedData = Client::factory()->make([
            'rfc' => 'XAXX010101001', // Use a known simple valid RFC, different from store
        ])->toArray();

        $response = $this->put(route('clients.update', $client), $updatedData);

        $response->assertRedirect(route('clients.show', $client)); 
        $this->assertDatabaseHas('clients', ['id' => $client->id, 'email' => $updatedData['email'], 'rfc' => 'XAXX010101001']);
        $response->assertSessionHas('success');
    }

    /** @test */
    public function update_fails_with_invalid_rfc()
    {
        $client = Client::factory()->create();
        $originalRfc = $client->rfc;
        $invalidData = ['rfc' => 'INVALIDRFC']; // Other data can be valid or from factory

        $response = $this->put(route('clients.update', $client), array_merge(Client::factory()->make()->toArray(), $invalidData));

        $response->assertSessionHasErrors('rfc');
        $this->assertDatabaseHas('clients', ['id' => $client->id, 'rfc' => $originalRfc]);
    }

    /** @test */
    public function update_fails_with_invalid_email()
    {
        $client = Client::factory()->create();
        $originalEmail = $client->email;
        $invalidData = ['email' => 'not-an-email'];

        $response = $this->put(route('clients.update', $client), array_merge(Client::factory()->make()->toArray(), $invalidData));

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseHas('clients', ['id' => $client->id, 'email' => $originalEmail]);
    }

    /** @test */
    public function update_fails_with_duplicate_rfc()
    {
        $clientToUpdate = Client::factory()->create();
        $otherClientWithRfc = Client::factory()->create(); // This client has the RFC we'll try to duplicate
        $originalRfcForClientToUpdate = $clientToUpdate->rfc;

        $updateData = Client::factory()->make(['rfc' => $otherClientWithRfc->rfc])->toArray();

        $response = $this->put(route('clients.update', $clientToUpdate), $updateData);

        $response->assertSessionHasErrors('rfc');
        $this->assertDatabaseHas('clients', ['id' => $clientToUpdate->id, 'rfc' => $originalRfcForClientToUpdate]);
    }

    /** @test */
    public function update_fails_with_duplicate_email()
    {
        $clientToUpdate = Client::factory()->create();
        $otherClientWithEmail = Client::factory()->create(); // This client has the email we'll try to duplicate
        $originalEmailForClientToUpdate = $clientToUpdate->email;

        $updateData = Client::factory()->make(['email' => $otherClientWithEmail->email])->toArray();

        $response = $this->put(route('clients.update', $clientToUpdate), $updateData);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseHas('clients', ['id' => $clientToUpdate->id, 'email' => $originalEmailForClientToUpdate]);
    }

    /** @test */
    public function destroy_soft_deletes_client_and_redirects()
    {
        $client = Client::factory()->create();

        $response = $this->delete(route('clients.destroy', $client));

        $response->assertRedirect(route('clients.index'));
        $this->assertSoftDeleted($client);
        $response->assertSessionHas('success');
    }
}
