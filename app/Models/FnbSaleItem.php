<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FnbSaleItem extends Model
{
      protected $guarded =  [
        'id',
        'created_at',
        'updated_at',
      ];

      public function sale()
      {
          return $this->belongsTo(FnbSale::class, 'fnb_sale_id');
      }

      public function productVariant()
      {
          return $this->belongsTo(FnbProductVariant::class, 'fnb_product_variant_id');
      }
}
