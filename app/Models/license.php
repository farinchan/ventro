<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class license extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function fnbBusinesses()
    {
        return $this->belongsToMany(FnbBusiness::class, 'fnb_business_licenses', 'license_id', 'fnb_business_id')
            ->withPivot('expiry_date', 'status')
            ->withTimestamps();
    }
}
