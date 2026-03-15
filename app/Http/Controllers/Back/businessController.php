<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\Back\StoreBusinessRequest;
use App\Models\FnbBusiness;
use App\Models\FnbBusinessLicense;
use App\Models\license;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class businessController extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'horizontal'];
    $data = [
      'pageConfigs' => $pageConfigs,
      'fnb_businesses' => FnbBusiness::all(),
      'licenses' => license::all(),
    ];

    // dd($data);
    return view('content.back.home.index', $data);
  }

  public function store(StoreBusinessRequest $request)
  {
    $validatedData = $request->validated();
    $createdBusiness = null;

    // Handle logo upload if provided
    if ($request->hasFile('logo')) {
      $file = $request->file('logo');
      $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
      $filePath = $file->storeAs('public/logos', $fileName);
      $validatedData['logo'] = $filePath;
    }

    $baseSlug = Str::slug($validatedData['name']);
    $baseSlug = $baseSlug !== '' ? $baseSlug : 'business';
    $slug = $baseSlug;
    $counter = 1;

    while (FnbBusiness::query()->where('slug', $slug)->exists()) {
      $slug = $baseSlug . '-' . $counter;
      $counter++;
    }

    $businessPayload = [
      'logo' => $validatedData['logo'] ?? null,
      'name' => $validatedData['name'],
      'slug' => $slug,
      'description' => $validatedData['description'] ?? null,
      'license_id' => $validatedData['license_id'],
      'billing_cycle' => $validatedData['billing_cycle'] ?? null,
    ];

    // Create the business based on the type
    switch ($validatedData['type']) {
      case 'fnb':
        $createdBusiness = FnbBusiness::query()->create($businessPayload);
        break;
        // Future cases for 'retail' and 'laundry' can be added here
    }

    // Create the business license association
    if (isset($createdBusiness)) {
      if (Auth::check()) {
        $createdBusiness->businessUsers()->create([
          'user_id' => Auth::id(),
        ]);
      }
    }

    return redirect()->route('business.index')->with('success', 'Business created successfully.');
  }
}
