<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FnbProductVariant extends Model
{
     protected $guarded =  [
      'id',
      'created_at',
      'updated_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(FnbProduct::class, 'fnb_product_id');
    }

    public function saleItems()
    {
        return $this->hasMany(FnbSaleItem::class, 'fnb_product_variant_id');
    }
}
