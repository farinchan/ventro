<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class businessController extends Controller
{
    public function index()
  {
    $pageConfigs = ['myLayout' => 'horizontal'];
    return view('content.back.home.index', ['pageConfigs' => $pageConfigs]);
  }
}
