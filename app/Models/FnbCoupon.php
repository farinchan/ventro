<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FnbCoupon extends Model
{
  use SoftDeletes;
    protected $guarded =  [
      'id',
      'created_at',
      'updated_at',
    ];

    public function business()
    {
        return $this->belongsTo(FnbBusiness::class, 'fnb_business_id');
    }

    public function sales()
    {
        return $this->hasMany(FnbSale::class, 'fnb_coupon_id');
    }


}
