<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FnbBusiness extends Model
{
    protected $guarded =  [
      'id',
      'created_at',
      'updated_at',
    ];

    public function products()
    {
        return $this->hasMany(FnbProduct::class, 'fnb_business_id');
    }

    public function categories()
    {
        return $this->hasMany(FnbProductCategory::class, 'fnb_business_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'fnb_business_users', 'fnb_business_id', 'user_id')->withTimestamps();
    }

    public function outlets()
    {
        return $this->hasMany(FnbOutlet::class, 'fnb_business_id');
    }


}
