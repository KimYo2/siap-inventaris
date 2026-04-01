<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user');

        return [
            'nip' => ['required', 'numeric', 'digits:18', Rule::unique('users', 'nip')->ignore($userId)],
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
        ];
    }
}
