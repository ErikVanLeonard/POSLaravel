<?php

namespace Database\Factories;

use App\Models\Provider;
use App\Models\ProviderDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProviderDocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProviderDocument::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'provider_id' => Provider::factory(),
            'name' => $this->faker->word . '_statement.pdf', // Changed from document_name
            'file_path' => 'documents/provider_' . $this->faker->uuid . '.pdf', // Changed from document_path
            'file_type' => 'application/pdf',
            'file_size' => $this->faker->numberBetween(100, 5000), // kilobytes
            'notes' => $this->faker->optional()->sentence,
            // 'uploaded_at' is not in the ProviderDocument fillable array, created_at/updated_at will handle timestamping
        ];
    }
}
