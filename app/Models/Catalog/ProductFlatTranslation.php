<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class ProductFlatTranslation extends Model
{
    protected $fillable = [
        'product_flat_id',
        'locale',
        'name',
        'short_description',
        'description',
    ];

    public function productFlat()
    {
        return $this->belongsTo(ProductFlat::class);
    }
}
