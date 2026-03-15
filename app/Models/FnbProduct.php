<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FnbProduct extends Model
{
     protected $guarded =  [
      'id',
      'created_at',
      'updated_at',
    ];

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
        return $this->hasMany(FnbProductVariant::class, 'fnb_product_variants_id');
    }


}
