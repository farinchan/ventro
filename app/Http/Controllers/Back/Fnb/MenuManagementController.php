<?php

namespace App\Http\Controllers\Back\Fnb;

use App\Http\Controllers\Controller;
use App\Models\FnbBusiness;
use App\Models\FnbProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MenuManagementController extends Controller
{
    protected ?FnbBusiness $fnb;
    protected ?string $fnbSlug;

    public function __construct(Request $request)
    {
        $this->fnbSlug = $request->route('fnbSlug');
        $this->fnb = $this->resolveBusiness();
    }

    public function category()
    {
        $data = [
            'fnbSlug' => $this->fnbSlug,
            'categories' => FnbProductCategory::query()
                ->where('fnb_business_id', $this->fnb?->id)
                ->withCount('products')
                ->latest()
                ->get(),
            'fnb' => $this->fnb,
        ];

        // return response()->json($data);

        return view('content.back.fnb.menu.category', $data);
    }

    public function categoryStore(Request $request): RedirectResponse
    {
        $business = $this->resolveBusinessOrFail();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('fnb_product_categories', 'name')->where(
                    fn ($query) => $query->where('fnb_business_id', $business->id),
                ),
            ],
            'description' => ['nullable', 'string'],
        ]);

        FnbProductCategory::query()->create([
            'fnb_business_id' => $business->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Category created successfully!');
    }

    public function categoryUpdate(Request $request): RedirectResponse
    {
        $business = $this->resolveBusinessOrFail();

        $validated = $request->validate([
            'id' => ['required', 'integer'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('fnb_product_categories', 'name')
                    ->where(fn ($query) => $query->where('fnb_business_id', $business->id))
                    ->ignore($request->input('id')),
            ],
            'description' => ['nullable', 'string'],
        ]);

        $category = FnbProductCategory::query()
            ->where('id', $validated['id'])
            ->where('fnb_business_id', $business->id)
            ->firstOrFail();

        $category->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Category updated successfully!');
    }

    public function categoryDestroy(Request $request): RedirectResponse
    {
        $business = $this->resolveBusinessOrFail();

        $validated = $request->validate([
            'id' => ['required', 'integer'],
        ]);

        $category = FnbProductCategory::query()
            ->where('id', $validated['id'])
            ->where('fnb_business_id', $business->id)
            ->firstOrFail();

        $category->delete();

        return redirect()->back()->with('success', 'Category deleted successfully!');
    }

    public function product(): RedirectResponse
    {
        return redirect()->back()->with('info', 'Product page is not implemented yet.');
    }

    public function productStore(): RedirectResponse
    {
        return redirect()->back();
    }

    public function productUpdate(): RedirectResponse
    {
        return redirect()->back();
    }

    public function productDelete(): RedirectResponse
    {
        return redirect()->back();
    }

    public function sales(): RedirectResponse
    {
        return redirect()->back()->with('info', 'Sales mode page is not implemented yet.');
    }

    protected function resolveBusiness(): ?FnbBusiness
    {
        if (! $this->fnbSlug) {
            return null;
        }

        return FnbBusiness::query()
            ->where('slug', $this->fnbSlug)
            ->orWhere('id', $this->fnbSlug)
            ->first();
    }

    protected function resolveBusinessOrFail(): FnbBusiness
    {
        return $this->fnb ?? FnbBusiness::query()
            ->where('slug', $this->fnbSlug)
            ->orWhere('id', $this->fnbSlug)
            ->firstOrFail();
    }
}
