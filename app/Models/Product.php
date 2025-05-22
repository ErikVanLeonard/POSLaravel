<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Auditable;
    
    protected $fillable = [
        'nombre',
        'barcode',
        'precio_menudeo',
        'precio_mayoreo',
        'stock_actual',
        'category_id',
        'image_path'
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
