<?php

namespace App\Http\Controllers\Back\Fnb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
      $data = [
      ];
        return view('content.fnb.product.index', $data);
    }
}
