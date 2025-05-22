<?php

namespace Tests\Unit;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function attributes_are_fillable()
    {
        $data = [
            'name' => 'John Doe',
            'company' => 'Doe Inc.',
            'rfc' => 'DOEJ880101XXX',
            'address' => '123 Main St, Anytown',
            'phone' => '555-1234',
            'email' => 'john.doe@example.com',
        ];

        $client = new Client($data);

        $this->assertEquals($data['name'], $client->name);
        $this->assertEquals($data['company'], $client->company);
        $this->assertEquals($data['rfc'], $client->rfc);
        $this->assertEquals($data['address'], $client->address);
        $this->assertEquals($data['phone'], $client->phone);
        $this->assertEquals($data['email'], $client->email);
    }

    /** @test */
    public function client_can_be_soft_deleted()
    {
        $client = Client::factory()->create();

        $this->assertFalse($client->trashed());

        $client->delete();

        $this->assertTrue($client->trashed());
        $this->assertNull(Client::find($client->id));
        $this->assertNotNull(Client::withTrashed()->find($client->id));
    }
}
