<?php

namespace App\Http\Resources\Fnb;

use App\Models\FnbBusiness;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OutletResource extends JsonResource
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
            'name' => $this->name,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'phone' => $this->phone,
            'email' => $this->email,
            'business' => $this->business ? [
                'id' => $this->business->id,
                'logo' => $this->business->logo,
                'name' => $this->business->name,
                'slug' => $this->business->slug,
                'domain' => $this->business->domain,
                'description' => $this->business->description,
                'license' => $this->business->license,
                'billing_cycle' => $this->business->billing_cycle,
                'expiry_date' => $this->business->expiry_date,
            ] : null,
            // 'staff' => $this->staff ? [
            //     'id' => $this->staff->id,
            //     'photo' => $this->staff->photo,
            //     'username' => $this->staff->username,
            //     'name' => $this->staff->name,
            //     'email' => $this->staff->email,
            //     'phone' => $this->staff->phone,
            //     'role' => $this->staff->role,
            //     'is_active' => $this->staff->is_active,
            //     'created_at' => $this->staff->created_at,
            //     'updated_at' => $this->staff->updated_at,
            // ] : null,
        ];
    }
}
