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
        $outletId = $request->header('X-Tenant-ID');
        if (! $outletId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tenant ID is required in the X-Tenant-ID header',
            ], 400);
        }

        // You can also set the outlet ID in the request attributes for later use
        $request->attributes->set('outlet_id', $outletId);
        $request->attributes->set('business_id', FnbOutlet::where('id', $outletId)->value('business_id'));

        return $next($request);
    }
}
