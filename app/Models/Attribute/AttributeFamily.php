<?php

namespace App\Models\Attribute;

use Illuminate\Database\Eloquent\Model;

class AttributeFamily extends Model
{
    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'attribute_family_id')->orderBy('order');
    }
}
