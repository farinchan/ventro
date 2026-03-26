<?php

namespace App\Http\Controllers\Api\Fnb;

use App\Http\Controllers\Controller;
use App\Models\FnbCoupon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        try {
            $coupons = FnbCoupon::where('fnb_business_id', $request->attributes->get('business_id'))
                ->where('is_active', 1)
                ->where('valid_from', '<=', now())
                ->where('valid_until', '>=', now())
                ->where(function ($query) {
                    $query->whereNull('usage_limit')
                        ->orWhereColumn('used_count', '<', 'usage_limit');
                })
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Kupon berhasil ditemukan',
                'data' => $coupons->map(function ($coupon) {
                    return [
                        'id' => $coupon->id,
                        'code' => $coupon->code,
                        'description' => $coupon->description,
                        'type' => $coupon->type,
                        'value' => $coupon->value,
                        'usage_limit' => $coupon->usage_limit,
                        'used_count' => $coupon->used_count,
                        'valid_from' => $coupon->valid_from,
                        'valid_until' => $coupon->valid_until,
                        'is_active' => $coupon->is_active,
                    ];
                }),
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'data' => null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Request $request, $code)
    {
        try {
            $coupon = FnbCoupon::where('fnb_business_id', $request->attributes->get('business_id'))->where('code', $code)->first();
            if (! $coupon) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kupon tidak ditemukan',
                    'data' => null,
                ], Response::HTTP_NOT_FOUND);
            }
            if ($coupon->is_active == 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kupon tidak aktif',
                    'data' => null,
                ], Response::HTTP_BAD_REQUEST);
            }
            if ($coupon->valid_from > now()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kupon belum berlaku',
                    'data' => null,
                ], Response::HTTP_BAD_REQUEST);
            }
            if ($coupon->valid_until < now()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kupon sudah berakhir',
                    'data' => null,
                ], Response::HTTP_BAD_REQUEST);
            }

            if (! is_null($coupon->usage_limit) && $coupon->used_count >= $coupon->usage_limit) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kupon sudah mencapai batas penggunaan',
                    'data' => null,
                ], Response::HTTP_BAD_REQUEST);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Kupon berhasil ditemukan',
                'data' => [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'description' => $coupon->description,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'usage_limit' => $coupon->usage_limit,
                    'used_count' => $coupon->used_count,
                    'valid_from' => $coupon->valid_from,
                    'valid_until' => $coupon->valid_until,
                    'is_active' => $coupon->is_active,
                ],
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'data' => null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function redeem(Request $request, $code)
    {
        try {
            $coupon = FnbCoupon::where('fnb_business_id', $request->attributes->get('business_id'))
                ->where('code', $code)
                ->first();
            if (! $coupon) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kupon tidak ditemukan',
                    'data' => null,
                ], Response::HTTP_NOT_FOUND);
            }
            if ($coupon->is_active == 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kupon tidak aktif',
                    'data' => null,
                ], Response::HTTP_BAD_REQUEST);
            }
            if ($coupon->valid_from > now()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kupon belum berlaku',
                    'data' => null,
                ], Response::HTTP_BAD_REQUEST);
            }
            if ($coupon->valid_until < now()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kupon sudah berakhir',
                    'data' => null,
                ], Response::HTTP_BAD_REQUEST);
            }

            if (! is_null($coupon->usage_limit) && $coupon->used_count >= $coupon->usage_limit) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kupon sudah mencapai batas penggunaan',
                    'data' => null,
                ], Response::HTTP_BAD_REQUEST);
            }
            DB::transaction(function () use ($coupon) {
                $coupon->used_count++;
                $coupon->save();
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Kupon berhasil ditukar',
                'data' => null,
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'data' => null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
