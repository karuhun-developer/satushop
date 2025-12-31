<?php

namespace App\Models\Attribute;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = [
        'attribute_family_id',
        'code',
        'name',
        'order',
        'status',
    ];

    protected $casts = [
        'order' => 'integer',
        'status' => 'boolean',
    ];

    public function translations()
    {
        return $this->hasMany(AttributeTranslation::class);
    }

    public function family()
    {
        return $this->belongsTo(AttributeFamily::class, 'attribute_family_id');
    }

    public function options()
    {
        return $this->hasMany(AttributeOption::class, 'attribute_id')->orderBy('order');
    }

    public function productAttributes()
    {
        return $this->hasMany(\App\Models\Catalog\ProductAttribute::class, 'attribute_id');
    }
}
