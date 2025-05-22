<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = [
        'company',
        'contact_name',
        'phone',
        'email',
        'address',
        'order_website',
        'billing_email',
        'order_phone',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Get the documents for the provider.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(ProviderDocument::class);
    }
}
