<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class FnbBusiness extends Model
{
    use HasUuids;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    protected function logo(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? asset('storage/'.$value) : 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=7F9CF5&background=EBF4FF',
        );
    }

    public function license()
    {
        return $this->belongsTo(license::class, 'license_id');
    }

    public function products()
    {
        return $this->hasMany(FnbProduct::class, 'fnb_business_id');
    }

    public function categories()
    {
        return $this->hasMany(FnbProductCategory::class, 'fnb_business_id');
    }

    public function businessUsers()
    {
        return $this->hasMany(FnbBusinessUser::class, 'fnb_business_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'fnb_business_users', 'fnb_business_id', 'user_id')->withTimestamps();
    }

    public function outlets()
    {
        return $this->hasMany(FnbOutlet::class, 'fnb_business_id');
    }

    public function sale()
    {
        return $this->belongsToMany(FnbSale::class, 'fnb_business_sales', 'fnb_business_id', 'fnb_sale_id')->withTimestamps();
    }
}
