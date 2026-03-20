<?php

namespace App\Http\Middleware;

use App\Models\FnbOutlet;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FnbOutletMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $outletId = $request->header('X-Outlet-ID');
        $outlet = FnbOutlet::find($outletId);
        if (!$outletId || !$outlet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Outlet ID provided in X-Outlet-ID header',
            ], 400);
        }

        // You can also set the outlet ID in the request attributes for later use
        $request->attributes->set('outlet_id', $outletId);
        $request->attributes->set('business_id', $outlet->fnb_business_id);

        return $next($request);
    }
}
