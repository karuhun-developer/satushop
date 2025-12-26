<?php

namespace App\Models\Attribute;

use Illuminate\Database\Eloquent\Model;

class AttributeOptionTranslation extends Model
{
    protected $fillable = [
        'attribute_option_id',
        'locale',
        'name',
    ];

    public function attributeOption()
    {
        return $this->belongsTo(AttributeOption::class);
    }
}
