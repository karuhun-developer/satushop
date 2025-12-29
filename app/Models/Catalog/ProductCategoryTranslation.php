<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class ProductCategoryTranslation extends Model
{
    protected $fillable = [
        'product_category_id',
        'locale',
        'name',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
}
