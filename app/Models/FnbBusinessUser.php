<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FnbBusinessUser extends Model
{
  use SoftDeletes;

     protected $guarded =  [
      'id',
      'created_at',
      'updated_at',
    ];

    public function business()
    {
        return $this->belongsTo(FnbBusiness::class, 'fnb_business_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
