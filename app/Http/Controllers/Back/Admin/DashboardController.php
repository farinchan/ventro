<?php

namespace App\Http\Controllers\Back\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('content.back.admin.dashboard');
    }
}
