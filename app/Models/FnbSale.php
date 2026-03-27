<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class FnbSale extends Model
{
    use HasUuids;

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
            'taxes' => 'array',
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'change_amount' => 'decimal:2',
        ];
    }

    public function outlet()
    {
        return $this->belongsTo(FnbOutlet::class, 'fnb_outlet_id');
    }

    public function staff()
    {
        return $this->belongsTo(FnbOutletStaff::class, 'fnb_outlet_staff_id');
    }

    public function table()
    {
        return $this->belongsTo(FnbTable::class, 'fnb_table_id');
    }

    public function costumer()
    {
        return $this->belongsTo(FnbCostumer::class, 'fnb_costumer_id');
    }

    public function items()
    {
        return $this->hasMany(FnbSaleItem::class, 'fnb_sale_id');
    }

    public function coupon()
    {
        return $this->belongsTo(FnbCoupon::class, 'fnb_coupon_id');
    }

    public function saleModeOutlet()
    {
        return $this->belongsTo(FnbSaleModeOutlet::class, 'fnb_sale_mode_outlet_id');
    }
}
