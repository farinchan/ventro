<?php

namespace App\Http\Controllers\Back\Fnb;

use App\Http\Controllers\Controller;
use App\Models\FnbBusiness;
use App\Models\FnbOutlet;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    protected $fnbSlug;

    protected $data;

    public function __construct(Request $request)
    {
        $this->fnbSlug = $request->route('fnbSlug');
        $this->data = [
            'fnbSlug' => $this->fnbSlug,
        ];
    }

    public function index()
    {
        return view('back.fnb.outlet.index');
    }

    public function create()
    {

        return view('content.back.fnb.outlet.create', $this->data);
    }

    public function store(Request $request, string $fnbSlug)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $business = FnbBusiness::query()
            ->where('slug', $fnbSlug)
            ->orWhere('id', $fnbSlug)
            ->firstOrFail();

        FnbOutlet::query()->create([
            'fnb_business_id' => $business->id,
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'latitude' => isset($validated['latitude']) ? (string) $validated['latitude'] : null,
            'longitude' => isset($validated['longitude']) ? (string) $validated['longitude'] : null,
        ]);

        return redirect()
            ->route('fnb.outlet.create', ['fnbSlug' => $business->slug])
            ->with('success', 'Outlet berhasil dibuat.');
    }

    public function edit(string $id)
    {
        return view('back.fnb.outlet.edit', compact('id'));
    }

    public function show(string $id)
    {
        return view('back.fnb.outlet.show', compact('id'));
    }
}
