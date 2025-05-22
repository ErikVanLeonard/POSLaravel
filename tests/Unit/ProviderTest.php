<?php

namespace Tests\Unit;

use App\Models\Provider;
use App\Models\ProviderDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\SoftDeletes; // Will be used by the model
use Tests\TestCase;

class ProviderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function attributes_are_fillable()
    {
        $data = [
            'company' => 'Test Company LLC',
            'contact_name' => 'John Contact',
            'phone' => '555-0001',
            'email' => 'contact@testcompany.com',
            'address' => '123 Provider St, Providerville',
            'order_website' => 'http://orders.testcompany.com',
            'billing_email' => 'billing@testcompany.com',
            'order_phone' => '555-0002',
        ];

        $provider = new Provider($data);

        $this->assertEquals($data['company'], $provider->company);
        $this->assertEquals($data['contact_name'], $provider->contact_name);
        $this->assertEquals($data['phone'], $provider->phone);
        $this->assertEquals($data['email'], $provider->email);
        $this->assertEquals($data['address'], $provider->address);
        $this->assertEquals($data['order_website'], $provider->order_website);
        $this->assertEquals($data['billing_email'], $provider->billing_email);
        $this->assertEquals($data['order_phone'], $provider->order_phone);
    }

    /** @test */
    public function provider_has_many_documents()
    {
        $provider = Provider::factory()->create();
        ProviderDocument::factory()->count(3)->create(['provider_id' => $provider->id]);

        $this->assertCount(3, $provider->documents);
        $this->assertInstanceOf(ProviderDocument::class, $provider->documents->first());
    }

    /** @test */
    public function provider_can_be_soft_deleted()
    {
        $provider = Provider::factory()->create();

        $this->assertFalse($provider->trashed());

        $provider->delete();

        $this->assertTrue($provider->trashed());
        $this->assertNull(Provider::find($provider->id));
        $this->assertNotNull(Provider::withTrashed()->find($provider->id));
        $this->assertEquals($provider->id, Provider::withTrashed()->find($provider->id)->id);
    }
}
