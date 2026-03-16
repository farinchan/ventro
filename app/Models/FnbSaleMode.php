<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FnbSaleMode extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function business()
    {
        return $this->belongsTo(FnbBusiness::class, 'fnb_business_id');
    }

    public function outlets()
    {
        return $this->belongsToMany(FnbOutlet::class, 'fnb_sale_mode_outlets', 'fnb_sale_mode_id', 'fnb_outlet_id')
                    ->withTimestamps();
    }
}
