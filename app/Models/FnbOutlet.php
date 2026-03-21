<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class FnbOutlet extends Model
{
    use HasUuids;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function business()
    {
        return $this->belongsTo(FnbBusiness::class, 'fnb_business_id');
    }

    public function outletStaff()
{
    return $this->hasMany(FnbOutletStaff::class, 'fnb_outlet_id');
}

    public function customers()
    {
        return $this->belongsToMany(FnbCostumer::class, 'fnb_costumer_outlet', 'fnb_outlet_id', 'customer_id')->withTimestamps();
    }

    public function sales()
    {
        return $this->hasMany(FnbSale::class, 'fnb_outlet_id');
    }

    public function printers()
    {
        return $this->hasMany(FnbPrinter::class, 'fnb_outlet_id');
    }
}
