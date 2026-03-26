<?php

namespace App\Http\Controllers\Api\Fnb;

use App\Http\Controllers\Controller;
use App\Models\FnbSaleModeOutlet;
use App\Models\FnbTable;
use App\Models\FnbTaxOutlet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class UtilityController extends Controller
{
    public function saleMode(Request $request)
    {
        try {
            $saleModes = FnbSaleModeOutlet::with('saleMode')->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Sale modes retrieved successfully',
                'data' => $saleModes->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->saleMode->name,
                        'description' => $item->saleMode->description,
                    ];
                }),
            ], Response::HTTP_OK);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function table(Request $request)
    {
        try {
            $tables = FnbTable::where('fnb_outlet_id', $request->attributes->get('outlet_id'))->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Tables retrieved successfully',
                'data' => $tables->groupBy('location')->map(function (Collection $items, string $location) {
                    return [
                        'location' => $location,
                        'tables' => $items->map(function (FnbTable $item) {
                            return [
                                'id' => $item->id,
                                'name' => $item->name,
                                'status' => $item->status,
                                'capacity' => $item->capacity,
                            ];
                        })->values(),
                    ];
                })->values(),
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function tax(Request $request)
    {
        try {
            $taxes = FnbTaxOutlet::with('tax')->where('fnb_outlet_id', $request->attributes->get('outlet_id'))->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Taxes retrieved successfully',
                'data' => $taxes->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->tax->name,
                        'description' => $item->tax->description,
                        'percent' => $item->tax->percent,
                    ];
                }),
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
