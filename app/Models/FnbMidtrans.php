<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FnbMidtrans extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function fnbBusiness()
    {
        return $this->belongsTo(FnbBusiness::class);
    }
}
