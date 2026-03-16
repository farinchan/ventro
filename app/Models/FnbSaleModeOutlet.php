<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FnbSaleModeOutlet extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function saleMode()
    {
        return $this->belongsTo(FnbSaleMode::class, 'fnb_sale_mode_id');
    }

    public function outlet()
    {
        return $this->belongsTo(FnbOutlet::class, 'fnb_outlet_id');
    }
}
