<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        // 'precio', // Se reemplazará o complementará con precio_menudeo y precio_mayoreo
        // 'cantidad', // Se reemplazará o complementará con stock_actual
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
