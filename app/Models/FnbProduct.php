<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class FnbProduct extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::url($value) : 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=7F9CF5&background=EBF4FF',
        );
    }

    public function business()
    {
        return $this->belongsTo(FnbBusiness::class, 'fnb_business_id');
    }

    public function category()
    {
        return $this->belongsTo(FnbProductCategory::class, 'fnb_product_category_id');
    }

    public function variants()
    {
        return $this->hasMany(FnbProductVariant::class, 'fnb_product_id');
    }
}
