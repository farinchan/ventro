<?php

namespace App\Http\Requests\Back;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBusinessRequest extends FormRequest
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
        return [
            'type' => ['required', 'string', Rule::in(['fnb', 'retail', 'laundry'])],
            'logo' => ['nullable', 'image', 'max:5120'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'license_id' => ['required', 'exists:licenses,id'],
            'billing_cycle' => ['nullable', 'string', Rule::in(['monthly', 'yearly'])],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'type.required' => 'Business type is required.',
            'type.string' => 'Business type must be a string.',
            'type.in' => 'Business type must be one of the following: fnb, retail, laundry.',
            'logo.image' => 'Logo must be an image file.',
            'logo.max' => 'Logo must not exceed 2MB in size.',
            'name.required' => 'Business name is required.',
            'name.string' => 'Business name must be a string.',
            'name.max' => 'Business name must not exceed 255 characters.',
            'description.string' => 'Description must be a string.',
            'license_id.required' => 'License is required.',
            'license_id.exists' => 'Invalid license selected.',
            'billing_cycle.string' => 'Billing cycle must be a string.',
            'billing_cycle.in' => 'Billing cycle must be either monthly or yearly.',
        ];
    }
}
