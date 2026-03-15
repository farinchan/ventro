<?php

namespace App\Http\Requests\Back\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
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
     * @return array<string, array<int, string|Password>>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', Password::defaults()],
            'role' => ['required', 'string', Rule::in(['user', 'admin'])],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'username.required' => 'Username is required.',
            'username.string' => 'Username must be a string.',
            'username.max' => 'Username must not exceed 255 characters.',
            'username.unique' => 'Username has already been taken.',
            'name.required' => 'Full name is required.',
            'name.string' => 'Full name must be a string.',
            'name.max' => 'Full name must not exceed 255 characters.',
            'email.required' => 'Email is required.',
            'email.string' => 'Email must be a string.',
            'email.lowercase' => 'Email must be in lowercase.',
            'email.email' => 'Email must be a valid email address.',
            'email.max' => 'Email must not exceed 255 characters.',
            'email.unique' => 'Email has already been taken.',
            'phone.string' => 'Phone number must be a string.',
            'phone.max' => 'Phone number must not exceed 30 characters.',
            'password.required' => 'Password is required.',
            'password.password' => 'Password must meet the default complexity requirements.',
            'role.required' => 'User role is required.',
            'role.string' => 'User role must be a string.',
            'role.in' => 'User role must be one of the following: user, admin.',
            'is_active.required' => 'Please select user status.',
            'is_active.boolean' => 'User status is invalid.',
        ];
    }
}
