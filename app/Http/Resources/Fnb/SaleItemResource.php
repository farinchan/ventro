<?php

namespace App\Http\Resources\Fnb;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleItemResource extends JsonResource
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
            'fnb_product_variant_id' => $this->fnb_product_variant_id,
            'product' => $this->when($this->relationLoaded('productVariant'), function () {
                if (! $this->productVariant) {
                    return null;
                }

                return [
                    'id' => $this->productVariant->id,
                    'name' => $this->productVariant->name,
                    'price' => $this->productVariant->price,
                    'product_name' => $this->productVariant->product?->name,
                    'product_image' => $this->productVariant->product?->image,
                ];
            }),
            'unit_price' => $this->unit_price,
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'note' => $this->note,
        ];
    }
}
