<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FnbProductCategory extends Model
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

    public function products()
    {
        return $this->hasMany(FnbProduct::class, 'fnb_product_category_id');
    }
}
