<?php

namespace App\Models\Attribute;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = [
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

    public function options()
    {
        return $this->hasMany(AttributeOption::class, 'attribute_id')->orderBy('order');
    }

    public function groups()
    {
        return $this->hasMany(AttributeGroup::class, 'attribute_id');
    }

    public function productAttributes()
    {
        return $this->hasMany(\App\Models\Catalog\ProductAttribute::class, 'attribute_id');
    }
}
