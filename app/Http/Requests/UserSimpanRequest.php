<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserSimpanRequest extends FormRequest
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
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|max:50',
            'alamat' => 'required|max:150',
            'nomor' => 'required|numeric'

        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama tidak boleh kosong.',
            'nama.max' => 'Nama user maksimal :max karakter.',
            'nama.regex' => 'Nama Tidak Boleh Terdapat Angka.',

            'email.required' => 'Email tidak boleh kosong.',
            'email.unique' => 'Email tersebut sudah ada dalam database, silahkan ganti nama email lain.',
            'email.email' => 'Format Email Pastikan Benar.',

            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password minimal :min karakter.',
            'password.max' => 'Password maksimal :max karakter.',

            'alamat.required' => 'Alamat tidak boleh kosong.',
            'alamat.max' => 'Alamat maksimal :max karakter.',
            
            'nomor.required' => 'Nomor tidak boleh kosong.',
            'nomor.numeric' => 'Nomor harus berupa angka.'
        ];
    }
}
