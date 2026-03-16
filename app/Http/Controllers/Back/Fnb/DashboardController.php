<?php

namespace App\Http\Controllers\Back\Fnb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
      $data = [
      ];
        return view('content.fnb.dashboard.index', $data);
    }
}
