<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FnbOutletStaff extends Model
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

    public function sales()
    {
        return $this->hasMany(FnbSale::class, 'fnb_outlet_staff_id');
    }
}
