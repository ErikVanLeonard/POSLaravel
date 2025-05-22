<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Provider;
use App\Models\ProviderDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProviderControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        Storage::fake('public'); // Fake the public disk for provider documents
    }

    /** @test */
    public function index_displays_providers_including_soft_deleted()
    {
        $activeProvider = Provider::factory()->create();
        $softDeletedProvider = Provider::factory()->create(['deleted_at' => now()]);

        $response = $this->get(route('providers.index'));

        $response->assertStatus(200);
        $response->assertSee($activeProvider->company);
        $response->assertSee($softDeletedProvider->company);
    }

    /** @test */
    public function create_page_loads_successfully()
    {
        $response = $this->get(route('providers.create'));

        $response->assertStatus(200);
        // Updated based on actual view content
        $response->assertSee('Nuevo Proveedor'); 
    }

    /** @test */
    public function store_creates_new_provider_without_documents_and_redirects()
    {
        $providerData = Provider::factory()->make()->toArray();

        $response = $this->post(route('providers.store'), $providerData);

        $response->assertRedirect(route('providers.index'));
        $this->assertDatabaseHas('providers', ['company' => $providerData['company'], 'email' => $providerData['email']]);
        $response->assertSessionHas('success');
    }

    /** @test */
    public function store_creates_new_provider_with_documents_and_redirects()
    {
        $providerData = Provider::factory()->make()->toArray();
        $files = [
            UploadedFile::fake()->create('document1.pdf', 100, 'application/pdf'),
            UploadedFile::fake()->create('document2.pdf', 50, 'application/pdf'), // Changed to PDF
        ];
        $providerDataWithFiles = array_merge($providerData, ['documents' => $files]);

        $response = $this->post(route('providers.store'), $providerDataWithFiles);

        $response->assertRedirect(route('providers.index'));
        $this->assertDatabaseHas('providers', ['company' => $providerData['company'], 'email' => $providerData['email']]);
        
        $provider = Provider::firstWhere('email', $providerData['email']);
        $this->assertNotNull($provider);
        $this->assertCount(2, $provider->documents);

        foreach ($provider->documents as $document) {
            Storage::disk('public')->assertExists($document->file_path);
            // Example: $document->file_path would be like 'provider_documents/1/document1.pdf'
            $this->assertStringStartsWith("provider_documents/{$provider->id}/", $document->file_path);
        }
        $response->assertSessionHas('success');
    }
    
    /** @test */
    public function store_fails_with_invalid_email()
    {
        $providerData = Provider::factory()->make(['email' => 'not-an-email'])->toArray();

        $response = $this->post(route('providers.store'), $providerData);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('providers', ['company' => $providerData['company']]);
    }

    /** @test */
    public function store_fails_with_duplicate_email()
    {
        $existingProvider = Provider::factory()->create();
        // Ensure other potentially unique fields like 'company' are different for the new record
        $providerData = Provider::factory()->make([
            'email' => $existingProvider->email,
            'company' => $this->faker->unique()->company, 
        ])->toArray();

        $response = $this->post(route('providers.store'), $providerData);

        $response->assertSessionHasErrors('email');
        $this->assertEquals(1, Provider::where('email', $existingProvider->email)->count());
    }

    /** @test */
    public function show_displays_provider_with_documents()
    {
        $provider = Provider::factory()->create();
        $document1 = ProviderDocument::factory()->create(['provider_id' => $provider->id]);
        $document2 = ProviderDocument::factory()->create(['provider_id' => $provider->id]);

        $response = $this->get(route('providers.show', $provider));

        $response->assertStatus(200);
        $response->assertSee($provider->company);
        $response->assertSee($document1->name); // Using 'name' as per ProviderDocumentFactory
        $response->assertSee($document2->name);
    }

    /** @test */
    public function edit_page_loads_successfully_with_provider_data_and_documents()
    {
        $provider = Provider::factory()->create();
        $document = ProviderDocument::factory()->create(['provider_id' => $provider->id]);

        $response = $this->get(route('providers.edit', $provider));

        $response->assertStatus(200);
        $response->assertSee('Editar Proveedor');
        $response->assertSee($provider->company);
        $response->assertSee($provider->email);
        $response->assertSee($document->name);
    }

    /** @test */
    public function update_modifies_provider_details_without_new_documents_and_redirects()
    {
        $provider = Provider::factory()->create();
        $existingDocument = ProviderDocument::factory()->create(['provider_id' => $provider->id]);

        $updatedData = [
            'company' => 'Updated Test Company',
            'contact_name' => $provider->contact_name, // Keep others same or use factory make
            'phone' => $provider->phone,
            'email' => $provider->email, // Assuming email is not changed here to avoid unique validation issues
            'address' => $provider->address,
        ];

        $response = $this->put(route('providers.update', $provider), $updatedData);

        $response->assertRedirect(route('providers.index')); // Or providers.show, $provider
        $this->assertDatabaseHas('providers', ['id' => $provider->id, 'company' => 'Updated Test Company']);
        $this->assertDatabaseHas('provider_documents', ['id' => $existingDocument->id]); // Existing doc still there
        $response->assertSessionHas('success');
    }

    /** @test */
    public function update_modifies_provider_details_and_adds_new_documents()
    {
        $provider = Provider::factory()->create();
        $existingDocument = ProviderDocument::factory()->create(['provider_id' => $provider->id]);

        $updatedData = [
            'company' => 'Company With New Docs',
            'contact_name' => $provider->contact_name,
            'phone' => $provider->phone,
            'email' => $provider->email,
            'address' => $provider->address,
        ];
        $newFile = UploadedFile::fake()->create('new_document.pdf', 200, 'application/pdf');
        $updatedDataWithFile = array_merge($updatedData, ['documents' => [$newFile]]);

        $response = $this->put(route('providers.update', $provider), $updatedDataWithFile);

        $response->assertRedirect(route('providers.index')); // Or show, $provider
        $this->assertDatabaseHas('providers', ['id' => $provider->id, 'company' => 'Company With New Docs']);
        $this->assertDatabaseHas('provider_documents', ['id' => $existingDocument->id]); // Existing doc still there
        
        $this->assertCount(2, $provider->refresh()->documents); // Should have old + new
        $newDocumentRecord = $provider->documents()->where('name', 'new_document.pdf')->first();
        $this->assertNotNull($newDocumentRecord);
        Storage::disk('public')->assertExists($newDocumentRecord->file_path);
        $this->assertStringStartsWith("provider_documents/{$provider->id}/", $newDocumentRecord->file_path);

        $response->assertSessionHas('success');
    }
    
    /** @test */
    public function update_fails_with_invalid_email()
    {
        $provider = Provider::factory()->create();
        $originalEmail = $provider->email;
        $invalidData = array_merge(
            Provider::factory()->make(['company' => $provider->company])->toArray(), // keep other fields valid
            ['email' => 'not-an-email']
        );
        unset($invalidData['documents']); // Not testing document upload here

        $response = $this->put(route('providers.update', $provider), $invalidData);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseHas('providers', ['id' => $provider->id, 'email' => $originalEmail]);
    }

    /** @test */
    public function update_fails_with_duplicate_email()
    {
        $providerToUpdate = Provider::factory()->create();
        $otherProvider = Provider::factory()->create(); // This provider has the email we'll try to duplicate
        $originalEmail = $providerToUpdate->email;

        $invalidData = array_merge(
            Provider::factory()->make(['company' => $providerToUpdate->company])->toArray(), // keep other fields valid
            ['email' => $otherProvider->email] // Duplicate email
        );
        unset($invalidData['documents']);

        $response = $this->put(route('providers.update', $providerToUpdate), $invalidData);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseHas('providers', ['id' => $providerToUpdate->id, 'email' => $originalEmail]);
    }

    /** @test */
    public function destroy_soft_deletes_provider_and_redirects()
    {
        $provider = Provider::factory()->create();

        $response = $this->delete(route('providers.destroy', $provider));

        $response->assertRedirect(route('providers.index'));
        $this->assertSoftDeleted($provider);
        $response->assertSessionHas('success');
    }

    /** @test */
    public function restore_restores_soft_deleted_provider_and_redirects()
    {
        $provider = Provider::factory()->create(['deleted_at' => now()]);

        $response = $this->post(route('providers.restore', $provider->id));

        $response->assertRedirect(route('providers.index'));
        $this->assertFalse($provider->fresh()->trashed());
        $response->assertSessionHas('success');
    }

    /** @test */
    public function force_delete_permanently_deletes_provider_and_documents()
    {
        $provider = Provider::factory()->create();
        // Manually create and store a document file
        $documentFile = UploadedFile::fake()->create('test_doc.pdf');
        $documentPath = $documentFile->store("provider_documents/{$provider->id}", 'public');
        $document = ProviderDocument::factory()->create([
            'provider_id' => $provider->id,
            'file_path' => $documentPath,
            'name' => $documentFile->name,
        ]);

        Storage::disk('public')->assertExists($documentPath); // Confirm file exists before delete

        $response = $this->delete(route('providers.forceDelete', $provider->id));

        $response->assertRedirect(route('providers.index'));
        $this->assertDatabaseMissing('providers', ['id' => $provider->id]);
        $this->assertDatabaseMissing('provider_documents', ['id' => $document->id]);
        Storage::disk('public')->assertMissing($documentPath);
        $response->assertSessionHas('success');
    }

    /** @test */
    public function delete_document_deletes_specific_document_and_returns_ajax_success()
    {
        $provider = Provider::factory()->create();
        $documentFile = UploadedFile::fake()->create('doc_to_delete.pdf');
        $documentPath = $documentFile->store("provider_documents/{$provider->id}", 'public');
        $document = ProviderDocument::factory()->create([
            'provider_id' => $provider->id,
            'file_path' => $documentPath,
            'name' => $documentFile->name,
        ]);
        Storage::disk('public')->assertExists($documentPath);

        $response = $this->deleteJson(route('providers.documents.destroy', ['provider' => $provider->id, 'document' => $document->id]));
        
        $response->assertOk(); // Or $response->assertNoContent();
        $response->assertJson(['success' => true]); // Assuming this is the success response structure

        $this->assertDatabaseMissing('provider_documents', ['id' => $document->id]);
        Storage::disk('public')->assertMissing($documentPath);
    }

    /** @test */
    public function download_document_downloads_the_file()
    {
        $provider = Provider::factory()->create();
        $documentFile = UploadedFile::fake()->create('downloadable_doc.pdf', 10, 'application/pdf');
        $documentPath = $documentFile->store("provider_documents/{$provider->id}", 'public');
        $document = ProviderDocument::factory()->create([
            'provider_id' => $provider->id,
            'file_path' => $documentPath,
            'name' => $documentFile->name, // Store the original name for Content-Disposition
        ]);

        $response = $this->get(route('providers.documents.download', ['provider' => $provider->id, 'document' => $document->id]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', 'attachment; filename="' . $document->name . '"');
        $this->assertEquals(Storage::disk('public')->get($documentPath), $response->streamedContent());
    }
}
