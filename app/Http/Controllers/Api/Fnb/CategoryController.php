<?php

namespace App\Http\Controllers\Api\Fnb;

use App\Http\Controllers\Controller;
use App\Http\Resources\Fnb\CategoryResource;
use App\Models\FnbProductCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
      try {
        $data = FnbProductCategory::where('business_id', $request->attributes->get('business_id'))->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Categories retrieved successfully',
            'data' => CategoryResource::collection($data),
        ], Response::HTTP_OK);
      } catch (\Throwable $th) {
        return response()->json([
            'status' => 'error',
            'message' => $th->getMessage(),
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
      }
    }
}
