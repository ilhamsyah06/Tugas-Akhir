<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierUpdateRequest extends FormRequest
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
            'name' => 'required|min:5|max:50',
            'email' => 'required',
            'alamat' => 'required|min:7|max:150',
            'nomorhp' => 'required|numeric'

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama Tidak Boleh Kosong.',
            'name.min' => 'Nama Supplier Minimal :min Karakter.',
            'name.max' => 'Nama Supplier Maksimal :max Karakter.',

            'email.required' => 'Email Tidak Boleh Kosong.',

            'alamat.required' => 'Alamat Tidak Boleh Kosong.',
            'alamat.min' => 'Alamat Minimal :min Karakter.',
            'alamat.max' => 'Alamat Maximal :max Karakter.',

            'nomorhp.required' => 'Nomor Tidak Boleh Kosong.',
            'nomorhp.numeric' => 'Nomor Harus Berupa Angka.'
        ];
    }
}
