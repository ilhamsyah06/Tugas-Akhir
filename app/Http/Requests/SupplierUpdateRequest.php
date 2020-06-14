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
            'name' => 'required|regex:/^[a-zA-Z ]+$/u|min:5|max:50',
            'email' => 'required|email',
            'alamat' => 'required|max:150',
            'nomorhp' => 'required|numeric'

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama Tidak Boleh Kosong.',
            'name.min' => 'Nama Supplier Minimal :min Karakter.',
            'name.regex' => 'Nama Supplier Tidak Boleh Terdapat Angka.',
            'name.max' => 'Nama Supplier Maksimal :max Karakter.',

            'email.required' => 'Email Tidak Boleh Kosong.',
            'email.email' => 'Format Email Harus Benar.',

            'alamat.required' => 'Alamat Tidak Boleh Kosong.',
            'alamat.max' => 'Alamat Maximal :max Karakter.',

            'nomorhp.required' => 'Nomor Tidak Boleh Kosong.',
            'nomorhp.numeric' => 'Nomor Harus Berupa Angka.'
        ];
    }
}
