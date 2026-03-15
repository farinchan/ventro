<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FnbSale extends Model
{
     protected $guarded =  [
      'id',
      'created_at',
      'updated_at',
    ];

    public function outlet()
    {
        return $this->belongsTo(FnbOutlet::class, 'fnb_outlet_id');
    }

    public function staff()
    {
        return $this->belongsTo(FnbOutletStaff::class, 'fnb_outlet_staff_id');
    }

    public function items()
    {
        return $this->hasMany(FnbSaleItem::class, 'fnb_sale_id');
    }
}
