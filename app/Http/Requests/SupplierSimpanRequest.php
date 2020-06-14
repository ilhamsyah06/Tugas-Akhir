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
            'nama' => 'required|regex:/^[a-zA-Z ]+$/u|max:50',
            'email' => 'required|email|unique:supplier',
            'alamat' => 'required|max:150',
            'nomor' => 'required|numeric'

        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama Tidak Boleh Kosong.',
            'nama.regex' => 'Nama Supplier Tidak Boleh Terdapat Angka.',
            'nama.max' => 'Nama Supplier Maksimal :max Karakter.',

            'email.required' => 'Email Tidak Boleh Kosong.',
            'email.email' => 'Format Email Harus Benar.',
            'email.unique' => 'Email Tersebut Sudah Ada Dalam Database, Silahkan Ganti Email Lain.',

            'alamat.required' => 'Alamat Tidak Boleh Kosong.',
            'alamat.max' => 'Alamat Maximal :max Karakter.',

            'nomor.required' => 'Nomor Tidak Boleh Kosong.',
            'nomor.numeric' => 'Nomor Harus Berupa Angka.'
        ];
    }
}
