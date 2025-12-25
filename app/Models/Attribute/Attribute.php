<?php

namespace App\Models\Attribute;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = [
        'attribute_family_id',
        'name',
        'order',
        'status',
    ];

    protected $casts = [
        'order' => 'integer',
        'status' => 'boolean',
    ];

    public function family()
    {
        return $this->belongsTo(AttributeFamily::class, 'attribute_family_id');
    }

    public function options()
    {
        return $this->hasMany(AttributeOption::class, 'attribute_id')->orderBy('order');
    }
}
