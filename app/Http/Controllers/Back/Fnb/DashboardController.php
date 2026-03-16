<?php

namespace App\Http\Controllers\Back\Fnb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  protected $fnbSlug;

  public function __construct(Request $request)
  {
    $this->fnbSlug = $request->route('fnbSlug');
  }

    public function index($fnbSlug)
    {
      $data = [
      ];
        return view('content.back.fnb.dashboard.index', $data);
    }
}
