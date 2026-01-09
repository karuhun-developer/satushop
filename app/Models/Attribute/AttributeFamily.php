<?php

namespace App\Models\Attribute;

use Illuminate\Database\Eloquent\Model;

class AttributeFamily extends Model
{
    protected $fillable = [
        'code',
        'name',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function groups()
    {
        return $this->hasMany(AttributeGroup::class, 'attribute_family_id');
    }
}
