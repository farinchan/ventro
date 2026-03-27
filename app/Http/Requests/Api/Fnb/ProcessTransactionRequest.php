<?php

namespace App\Http\Requests\Api\Fnb;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ProcessTransactionRequest extends FormRequest
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
            'fnb_outlet_staff_id' => ['required', 'exists:fnb_outlet_staff,id'],
            'customer_name' => ['nullable', 'string'],
            'customer_phone' => ['nullable', 'string'],
            'fnb_table_id' => ['nullable', 'exists:fnb_tables,id'],
            'fnb_coupon_id' => ['nullable', 'exists:fnb_coupons,id'],
            'fnb_sale_items' => ['required', 'array', 'min:1'],
            'fnb_sale_items.*.fnb_product_variant_id' => ['required', 'exists:fnb_product_variants,id'],
            'fnb_sale_items.*.quantity' => ['required', 'integer', 'min:1'],
            'fnb_sale_items.*.note' => ['nullable', 'string'],
            'taxes' => ['nullable', 'array'],
            'taxes.*' => ['exists:fnb_taxes,id'],
            'fnb_sale_mode_outlet_id' => ['nullable', 'exists:fnb_sale_mode_outlets,id'],
            'paid_amount' => ['required', 'numeric'],
            'payment_method' => ['nullable', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'fnb_outlet_staff_id.required' => 'Staff harus dipilih.',
            'fnb_outlet_staff_id.exists' => 'Staff tidak ditemukan.',
            'fnb_sale_items.required' => 'Minimal satu item harus ditambahkan.',
            'fnb_sale_items.min' => 'Minimal satu item harus ditambahkan.',
            'fnb_sale_items.*.fnb_product_variant_id.required' => 'Produk harus dipilih.',
            'fnb_sale_items.*.fnb_product_variant_id.exists' => 'Produk tidak ditemukan.',
            'fnb_sale_items.*.quantity.required' => 'Jumlah item harus diisi.',
            'fnb_sale_items.*.quantity.min' => 'Jumlah item minimal 1.',
            'fnb_coupon_id.exists' => 'Kupon tidak ditemukan.',
            'taxes.*.exists' => 'Pajak tidak ditemukan.',
            'paid_amount.required' => 'Jumlah yang dibayar harus diisi.',
            'paid_amount.numeric' => 'Jumlah yang dibayar harus berupa angka.',
            'payment_method.required' => 'Metode pembayaran harus diisi.',
            'fnb_sale_mode_outlet_id.exists' => 'Mode penjualan tidak ditemukan.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new ValidationException($validator, response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'validation' => $validator->errors(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
