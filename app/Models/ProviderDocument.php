<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderDocument extends Model
{
    protected $fillable = [
        'provider_id',
        'name',
        'file_path',
        'file_type',
        'file_size',
        'notes',
    ];

    /**
     * Get the provider that owns the document.
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }
}
