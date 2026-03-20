<?php

namespace App\Http\Controllers\Api\Fnb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FnbApiController extends Controller
{
    protected $outletId;
    public function __construct(Request $request)
    {
        $this->outletId = $request->header('X-Outlet-ID');
    }
}
