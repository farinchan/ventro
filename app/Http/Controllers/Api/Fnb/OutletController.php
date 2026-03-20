<?php

namespace App\Http\Controllers\Api\Fnb;

use App\Http\Controllers\Controller;
use App\Http\Resources\Fnb\OutletResource;
use App\Models\FnbBusinessUser;
use App\Models\FnbOutletStaff;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class OutletController extends Controller
{
    public function index(Request $request)
    {
        try {

        $data = FnbBusinessUser::where('user_id', Auth::id())->with('outlets')->firstOrFail()->outlets;

          return response()->json([
            'status' => 'success',
            'message' => 'Outlets retrieved successfully',
            'data' => OutletResource::collection($data),
          ], Response::HTTP_OK);

        } catch (\Throwable $th) {
          return response()->json([
            'status' => 'error',
            'message' => $th->getMessage(),
          ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
