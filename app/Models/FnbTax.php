<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FnbTax extends Model
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

      public function taxOutlets()
      {
          return $this->hasMany(FnbTaxOutlet::class, 'fnb_tax_id');
      }

      public function outlets()
      {
          return $this->belongsToMany(FnbOutlet::class, 'fnb_tax_outlets', 'fnb_tax_id', 'fnb_outlet_id');
      }
}
