<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KategoriSimpanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama'  => 'required|min:3|max:100|unique:kategori',
        ];
    }

    public function messages()
    {
        return [
            'nama.required'  => 'Nama jenis barang harus ada',
            'nama.min'  => 'Nama jenis barang minimal :min karakter',
            'nama.max'  => 'Nama jenis barang maksimal :max karakter',
            'nama.unique'  => 'Nama jenis barang sudah terdaftar di database',
        ];
    }
}
