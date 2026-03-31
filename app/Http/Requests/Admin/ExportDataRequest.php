<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ExportDataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'in:master,operasional,laporan'],
            'format' => ['required', 'in:excel,pdf'],
            'kecamatan_id' => ['nullable', 'exists:kecamatans,id'],
            'kelurahan_id' => ['nullable', 'exists:kelurahans,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'tahun' => ['nullable', 'integer', 'min:2020', 'max:' . date('Y')]
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Pilih jenis data yang akan diexport.',
            'format.required' => 'Pilih format export.',
            'end_date.after_or_equal' => 'Tanggal akhir harus sama atau setelah tanggal mulai.'
        ];
    }
}