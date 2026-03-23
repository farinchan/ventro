<?php

namespace App\Http\Controllers\Api\Fnb;

use App\Http\Controllers\Controller;
use App\Models\FnbSaleModeOutlet;
use App\Models\FnbTable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UtilityController extends Controller
{
    public function saleMode(Request $request){
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
           })
        ], Response::HTTP_OK);

      } catch (\Throwable $th) {
        return response()->json([
          'status' => 'error',
          'message' => $th->getMessage()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
      }

    }

    public function table(Request $request){
      try {
        $tables = FnbTable::where('fnb_outlet_id', $request->attributes->get('outlet_id'))->get();
        return response()->json([
           'status' => 'success',
           'message' => 'Tables retrieved successfully',
           'data' => $tables->map(function ($item) {
            return [
              'id' => $item->id,
              'name' => $item->name,
              'location' => $item->location,
              'status' => $item->status,
              'capacity' => $item->capacity,
            ];
           })
        ], Response::HTTP_OK);
      } catch (\Throwable $th) {
        return response()->json([
          'status' => 'error',
          'message' => $th->getMessage()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
      }
    }
}
