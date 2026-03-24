<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FnbTaxOutlet extends Model
{

    protected $guarded =  [
        'id',
        'created_at',
        'updated_at',
    ];

    public function tax()
    {
        return $this->belongsTo(FnbTax::class, 'fnb_tax_id');
    }

    public function outlet()
    {
        return $this->belongsTo(FnbOutlet::class, 'fnb_outlet_id');
    }
}
