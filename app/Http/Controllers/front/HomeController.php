<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
  {

    $pageConfigs = ['myLayout' => 'front'];
    $data = [
      'user' => Auth::user(),
    ];
    return view('content.front.home', ['pageConfigs' => $pageConfigs, compact('data')]);
  }
}
