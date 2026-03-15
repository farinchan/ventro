<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FnbSetting extends Model
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
}
