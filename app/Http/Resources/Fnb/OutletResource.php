<?php

namespace App\Http\Resources\Fnb;

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
                'license' => $this->business->license ? [
                    'name' => $this->business->license->name,
                    'description' => $this->business->license->description,
                    'max_transactions_per_day' => $this->business->license->max_transactions_per_day,
                    'max_users' => $this->business->license->max_users,
                    'price' => $this->business->license->price,
                ] : null,
                'billing_cycle' => $this->business->billing_cycle,
                'expiry_date' => $this->business->expiry_date,
            ] : null,
            'staff' => $this->outletStaff ? $this->outletStaff->map(function ($staff) {
                $user = $staff->businessUser?->user;
                if (! $user) {
                    return null;
                }

                return [
                    'id' => $user->id,
                    'photo' => $user->photo,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'outlet_staff_id' => $staff->id,
                ];
            })->filter()->values() : [],
        ];
    }
}
