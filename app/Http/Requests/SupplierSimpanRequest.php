<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierSimpanRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama' => 'required|min:5|max:50',
            'email' => 'required|unique:supplier',
            'alamat' => 'required|min:7|max:150',
            'nomor' => 'required|numeric'

        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama Tidak Boleh Kosong.',
            'nama.min' => 'Nama Supplier Minimal :min Karakter.',
            'nama.max' => 'Nama Supplier Maksimal :max Karakter.',

            'email.required' => 'Email Tidak Boleh Kosong.',
            'email.unique' => 'Email Tersebut Sudah Ada Dalam Database, Silahkan Ganti Email Lain.',

            'alamat.required' => 'Alamat Tidak Boleh Kosong.',
            'alamat.min' => 'Alamat Minimal :min Karakter.',
            'alamat.max' => 'Alamat Maximal :max Karakter.',

            'nomor.required' => 'Nomor Tidak Boleh Kosong.',
            'nomor.numeric' => 'Nomor Harus Berupa Angka.'
        ];
    }
}
