<?php

namespace App\Http\Controllers\Api\Fnb;

use App\Http\Controllers\Controller;
use App\Http\Resources\Fnb\SaleResource;
use App\Models\FnbSale;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        try {
            $q = $request->q;
            $paymentMethod = $request->payment_method;
            $saved = $request->saved;
            $outletId = $request->attributes->get('outlet_id');

            $sales = FnbSale::where('fnb_outlet_id', $outletId)
                ->with(['costumer', 'items.productVariant.product'])
                ->when($q, function ($query) use ($q) {
                    $query->where('invoice_number', 'like', "%{$q}%");
                })
                ->when($paymentMethod, function ($query) use ($paymentMethod) {
                    $query->where('payment_method', $paymentMethod);
                })
                ->when($saved, function ($query) use ($saved) {
                    $query->where('payment_method', null);
                })
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diambil',
                'data' => SaleResource::collection($sales),
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $outletId = $request->attributes->get('outlet_id');

            $sale = FnbSale::where('fnb_outlet_id', $outletId)
                ->with(['costumer', 'items.productVariant.product'])
                ->find($id);

            if (! $sale) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan',
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diambil',
                'data' => new SaleResource($sale),
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data gagal diambil',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
