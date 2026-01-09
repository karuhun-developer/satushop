<?php

namespace App\Models\Attribute;

use Illuminate\Database\Eloquent\Model;

class AttributeGroup extends Model
{
    protected $fillable = [
        'attribute_family_id',
        'attribute_id',
    ];

    public function family()
    {
        return $this->belongsTo(AttributeFamily::class, 'attribute_family_id');
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }
}
