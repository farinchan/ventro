<?php

namespace App\Http\Requests\Back\Fnb;

use App\Models\FnbBusiness;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteProductRequest extends FormRequest
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

        if ($businessId) {
            $productIdRules[] = Rule::exists('fnb_products', 'id')
                ->where(fn ($query) => $query->where('fnb_business_id', $businessId));
        } else {
            $productIdRules[] = Rule::exists('fnb_products', 'id');
        }

        return [
            'id' => $productIdRules,
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
        ];
    }
}
