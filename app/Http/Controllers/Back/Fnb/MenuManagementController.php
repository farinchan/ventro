<?php

namespace App\Http\Controllers\Back\Fnb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Back\Fnb\DeleteProductRequest;
use App\Http\Requests\Back\Fnb\StoreProductRequest;
use App\Http\Requests\Back\Fnb\UpdateProductRequest;
use App\Models\FnbBusiness;
use App\Models\FnbProduct;
use App\Models\FnbProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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

    public function product()
    {
        $data = [
            'fnbSlug' => $this->fnbSlug,
            'fnb' => $this->fnb,
            'products' => FnbProduct::query()
                ->where('fnb_business_id', $this->fnb?->id)
                ->with(['category', 'variants'])
                ->latest()
                ->get(),
        ];

        // return response()->json($data);
        return view('content.back.fnb.menu.product', $data);
    }

    public function productCreate()
    {
        $data = [
            'fnbSlug' => $this->fnbSlug,
            'fnb' => $this->fnb,
            'categories' => FnbProductCategory::query()
                ->where('fnb_business_id', $this->fnb?->id)
                ->latest()
                ->get(),
        ];

        return view('content.back.fnb.menu.product-create', $data);
    }

    public function productEdit(string $slug, int $id)
    {
        $business = $this->resolveBusinessOrFail();

        $data = [
            'fnbSlug' => $this->fnbSlug,
            'fnb' => $this->fnb,
            'categories' => FnbProductCategory::query()
                ->where('fnb_business_id', $business->id)
                ->latest()
                ->get(),
            'product' => FnbProduct::query()
                ->where('id', $id)
                ->where('fnb_business_id', $business->id)
                ->with('variants')
                ->first(),
        ];

        // dd($id);

        return view('content.back.fnb.menu.product-edit', $data);
    }

    public function productStore(StoreProductRequest $request): RedirectResponse
    {
        $business = $this->resolveBusinessOrFail();
        $validated = $request->validated();
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = FnbProduct::query()->create([
            'fnb_business_id' => $business->id,
            'fnb_product_category_id' => $validated['fnb_product_category_id'] ?? null,
            'image' => $imagePath,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ]);

        $product->variants()->createMany(
            collect($validated['variants'])
                ->map(fn (array $variant) => [
                    'name' => $variant['name'],
                    'price' => $variant['price'],
                ])
                ->values()
                ->all(),
        );

        return redirect()
            ->route('fnb.menu.product.index', ['fnbSlug' => $this->fnbSlug])
            ->with('success', 'Product created successfully!');
    }

    public function productUpdate(UpdateProductRequest $request): RedirectResponse
    {
        $business = $this->resolveBusinessOrFail();
        $validated = $request->validated();

        $product = FnbProduct::query()
            ->where('id', $validated['id'])
            ->where('fnb_business_id', $business->id)
            ->firstOrFail();

        $imagePath = $product->getRawOriginal('image');

        if ($request->hasFile('image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'fnb_product_category_id' => $validated['fnb_product_category_id'] ?? null,
            'image' => $imagePath,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
        ]);

        $product->variants()->delete();
        $product->variants()->createMany(
            collect($validated['variants'])
                ->map(fn (array $variant) => [
                    'name' => $variant['name'],
                    'price' => $variant['price'],
                ])
                ->values()
                ->all(),
        );

        return redirect()
            ->route('fnb.menu.product.index', ['fnbSlug' => $this->fnbSlug])
            ->with('success', 'Product updated successfully!');
    }

    public function productDestroy(DeleteProductRequest $request): RedirectResponse
    {
        $business = $this->resolveBusinessOrFail();
        $validated = $request->validated();

        $product = FnbProduct::query()
            ->where('id', $validated['id'])
            ->where('fnb_business_id', $business->id)
            ->firstOrFail();

        $imagePath = $product->getRawOriginal('image');

        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        $product->delete();

        return redirect()
            ->route('fnb.menu.product.index', ['fnbSlug' => $this->fnbSlug])
            ->with('success', 'Product deleted successfully!');
    }

    public function saleMode()
    {
        $data = [
            'fnbSlug' => $this->fnbSlug,
            'fnb' => $this->fnb,
        ];

        return view('content.back.fnb.menu.sale-mode', $data);
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
