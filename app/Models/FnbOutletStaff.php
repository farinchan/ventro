<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class FnbOutletStaff extends Authenticatable
{
    use SoftDeletes;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];


    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'deleted_at' => 'datetime',
        ];
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(FnbOutlet::class, 'fnb_outlet_id');
    }

    public function businessUser(): BelongsTo
    {
        return $this->belongsTo(FnbBusinessUser::class, 'fnb_business_user_id');
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'fnb_business_users', 'id', 'user_id', 'fnb_business_user_id', 'id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(FnbSale::class, 'fnb_outlet_staff_id');
    }
}
