<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FnbCoupon extends Model
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

    public function sales()
    {
        return $this->hasMany(FnbSale::class, 'fnb_coupon_id');
    }


}
