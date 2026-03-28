<?php

namespace App\Http\Resources\Fnb;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'outlet' => OutletResource::make($this->whenLoaded('outlet')),
            'outlet_staff' => $this->when($this->relationLoaded('staff'), function () {
                $user = $this->staff?->user?->first();

                return $user ? UserResource::make($user) : null;
            }),
            'costumer' => $this->when($this->relationLoaded('costumer'), function () {
                if (! $this->costumer) {
                    return null;
                }

                return [
                    'id' => $this->costumer->id,
                    'name' => $this->costumer->name,
                    'phone' => $this->costumer->phone,
                    'email' => $this->costumer->email,
                ];
            }),
            'table' => $this->when($this->relationLoaded('table'), function () {
                if (! $this->table) {
                    return null;
                }

                return [
                    'id' => $this->table->id,
                    'name' => $this->table->name,
                    'location' => $this->table->location,
                    'capacity' => $this->table->capacity,
                ];
            }),
            'coupon' => $this->when(
                $this->relationLoaded('coupon'),
                fn () => $this->coupon ? CouponResource::make($this->coupon) : null,
            ),
            'sale_mode' => $this->when($this->relationLoaded('saleModeOutlet'), function () {
                if (! $this->saleModeOutlet) {
                    return null;
                }

                return [
                    'id' => $this->saleModeOutlet->id,
                    'name' => $this->saleModeOutlet->saleMode?->name,
                ];
            }),
            'items' => SaleItemResource::collection($this->whenLoaded('items')),
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'taxes' => $this->taxes,
            'tax_amount' => collect($this->taxes)->sum('amount'),
            'total' => $this->total,
            'paid_amount' => $this->paid_amount,
            'change_amount' => $this->change_amount,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'midtrans_transaction_id' => $this->midtrans_transaction_id,
            'qris' => [
                'name' => '-',
                'method' => '-',
                'url' => '-',
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
