<?php

namespace Tests\Unit;

use App\Models\Provider;
use App\Models\ProviderDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProviderDocumentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function attributes_are_fillable()
    {
        $provider = Provider::factory()->create();

        $data = [
            'provider_id' => $provider->id,
            'name' => 'Tax_Statement_2023.pdf',
            'file_path' => 'provider_documents/tax_statement_2023.pdf',
            'file_type' => 'application/pdf',
            'file_size' => 2048, // KB
            'notes' => 'Annual tax statement for year 2023.',
        ];

        $document = new ProviderDocument($data);

        $this->assertEquals($data['provider_id'], $document->provider_id);
        $this->assertEquals($data['name'], $document->name);
        $this->assertEquals($data['file_path'], $document->file_path);
        $this->assertEquals($data['file_type'], $document->file_type);
        $this->assertEquals($data['file_size'], $document->file_size);
        $this->assertEquals($data['notes'], $document->notes);
    }

    /** @test */
    public function document_belongs_to_a_provider()
    {
        $provider = Provider::factory()->create();
        $document = ProviderDocument::factory()->create(['provider_id' => $provider->id]);

        $this->assertInstanceOf(Provider::class, $document->provider);
        $this->assertEquals($provider->id, $document->provider->id);
    }
}
