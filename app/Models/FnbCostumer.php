<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FnbCostumer extends Model
{
     protected $guarded =  [
      'id',
      'created_at',
      'updated_at',
    ];

    public function sales()
    {
        return $this->hasMany(FnbSale::class, 'customer_id');
    }

    public function outlets()
    {
        return $this->belongsToMany(FnbOutlet::class, 'fnb_costumer_outlet', 'fnb_customer_id', 'fnb_outlet_id')->withTimestamps();
    }

    public function businesses()
    {
        return $this->belongsToMany(FnbBusiness::class, 'fnb_costumer_business', 'fnb_customer_id', 'fnb_business_id')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
