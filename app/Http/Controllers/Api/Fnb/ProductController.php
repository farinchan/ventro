<?php

namespace App\Http\Controllers\Api\Fnb;

use App\Http\Controllers\Controller;
use App\Http\Resources\Fnb\ProductResource;
use App\Models\FnbProduct;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class ProductController extends Controller
{
      public function index(Request $request)
      {

      try {
          $keyword = $request->input('q');
        $perPage = $request->input('per_page', 10);
        $categoryId = $request->input('category_id');

         $query = FnbProduct::where('fnb_business_id', $request->attributes->get('business_id'));
        $data = $query->when($keyword, function ($q) use ($keyword) {
            $q->where('name', 'like', "%$keyword%");
        })->when($categoryId, function ($q) use ($categoryId) {
            $q->where('category_id', $categoryId);
        })->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'message' => 'Products retrieved successfully',
            'meta' => [
              'query' => $keyword ,
              'path' => $data->path(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
                'hasNext' => $data->hasMorePages(),
                'hasPrevious' => $data->currentPage() > 1,
            ],
            'data' => ProductResource::collection($data),
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
          $product = FnbProduct::where('fnb_business_id', $request->attributes->get('business_id'))->find($id);
          if (! $product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
            ], Response::HTTP_NOT_FOUND);
          }

          return response()->json([
              'status' => 'success',
              'message' => 'Product retrieved successfully',
              'data' => ProductResource::make($product),
          ], Response::HTTP_OK);
        } catch (\Throwable $th) {
          return response()->json([
              'status' => 'error',
              'message' => $th->getMessage(),
          ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

      }
}
