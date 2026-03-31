<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $existingUser = User::where('email', $value)->first();

                    if (! $existingUser) {
                        return;
                    }

                    if (! $existingUser->hasVerifiedEmail()) {
                        $fail('Email sudah terdaftar namun belum diverifikasi. Silakan cek inbox email untuk verifikasi.');
                        return;
                    }

                    $fail('Email sudah terdaftar.');
                },
            ],
            'password' => ['required', 'confirmed', Password::defaults()],
            'bank_sampah_master_id' => ['required', 'exists:bank_sampah_masters,id']
        ];
    }

    public function messages(): array
    {
        return [
            'bank_sampah_master_id.required' => 'Pilih bank sampah yang sesuai.',
            'bank_sampah_master_id.exists' => 'Bank sampah tidak valid.'
        ];
    }
}