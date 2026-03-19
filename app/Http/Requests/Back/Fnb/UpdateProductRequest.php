<?php

namespace App\Http\Requests\Back\Fnb;

use App\Models\FnbBusiness;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $fnbSlug = (string) $this->route('fnbSlug');
        $businessId = FnbBusiness::query()
            ->where('slug', $fnbSlug)
            ->orWhere('id', $fnbSlug)
            ->value('id');

        $productIdRules = ['required', 'integer'];
        $categoryRules = ['nullable', 'integer'];

        if ($businessId) {
            $productIdRules[] = Rule::exists('fnb_products', 'id')
                ->where(fn ($query) => $query->where('fnb_business_id', $businessId));
            $categoryRules[] = Rule::exists('fnb_product_categories', 'id')
                ->where(fn ($query) => $query->where('fnb_business_id', $businessId));
        } else {
            $productIdRules[] = Rule::exists('fnb_products', 'id');
            $categoryRules[] = Rule::exists('fnb_product_categories', 'id');
        }

        return [
            'id' => $productIdRules,
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'fnb_product_category_id' => $categoryRules,
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])],
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.name' => ['required', 'string', 'max:255'],
            'variants.*.price' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'id.required' => 'Product id is required.',
            'id.exists' => 'Selected product is invalid for this business.',
            'name.required' => 'Product name is required.',
            'image.image' => 'Product image must be a valid image file.',
            'image.mimes' => 'Product image must be a JPG, JPEG, PNG, or WEBP file.',
            'image.max' => 'Product image may not be greater than 2MB.',
            'fnb_product_category_id.exists' => 'Selected category is invalid for this business.',
            'status.required' => 'Product status is required.',
            'status.in' => 'Product status must be active or inactive.',
            'variants.required' => 'At least one variant is required.',
            'variants.array' => 'Variants format is invalid.',
            'variants.min' => 'At least one variant is required.',
            'variants.*.name.required' => 'Variant name is required.',
            'variants.*.price.required' => 'Variant price is required.',
            'variants.*.price.numeric' => 'Variant price must be a number.',
            'variants.*.price.min' => 'Variant price must be at least 0.',
        ];
    }
}
