<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBankSampahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $bankSampahId = $this->route('bank_sampah')->id;

        return [
            'kecamatan_id' => ['required', 'exists:kecamatans,id'],
            'kelurahan_id' => ['required', 'exists:kelurahans,id'],
            'rw' => ['required', 'string', 'max:10'],
            'status_terbentuk' => ['required', 'in:Sudah,Belum'],
            'nama_bank_sampah' => [
                'required',
                'string',
                'max:200',
                Rule::unique('bank_sampah_masters')->ignore($bankSampahId)
            ],
            'nomor_sk' => ['nullable', 'string', 'max:100'],
            'nama_direktur' => ['required', 'string', 'max:100'],
            'nomor_hp' => ['required', 'string', 'max:20'],
            'keterangan' => ['nullable', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'kecamatan_id.required' => 'Pilih kecamatan.',
            'kelurahan_id.required' => 'Pilih kelurahan.',
            'rw.required' => 'RW harus diisi.',
            'status_terbentuk.required' => 'Pilih status terbentuk.',
            'nama_bank_sampah.required' => 'Nama bank sampah harus diisi.',
            'nama_bank_sampah.unique' => 'Nama bank sampah sudah digunakan.',
            'nama_direktur.required' => 'Nama direktur harus diisi.',
            'nomor_hp.required' => 'Nomor HP harus diisi.',
        ];
    }

    public function attributes(): array
    {
        return [
            'kecamatan_id' => 'Kecamatan',
            'kelurahan_id' => 'Kelurahan',
            'rw' => 'RW',
            'status_terbentuk' => 'Status Terbentuk',
            'nama_bank_sampah' => 'Nama Bank Sampah',
            'nomor_sk' => 'Nomor SK',
            'nama_direktur' => 'Nama Direktur',
            'nomor_hp' => 'Nomor HP',
            'keterangan' => 'Keterangan'
        ];
    }
}