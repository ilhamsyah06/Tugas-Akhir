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
            'nama' => 'required|min:5|max:50',
            'email' => 'required|unique:users',
            'password' => 'required|min:5|max:50',
            'alamat' => 'required|min:7|max:150',
            'nomor' => 'required|numeric'

        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama tidak boleh kosong.',
            'nama.min' => 'Nama user minimal :min karakter.',
            'nama.max' => 'Nama user maksimal :max karakter.',

            'email.required' => 'Email tidak boleh kosong.',
            'email.unique' => 'Email tersebut sudah ada dalam database, silahkan ganti email lain.',

            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password minimal :min karakter.',
            'password.max' => 'Password maksimal :max karakter.',

            'alamat.required' => 'Alamat tidak boleh kosong.',
            'alamat.min' => 'Alamat minimal :min karakter.',
            'alamat.max' => 'Alamat maksimal :max karakter.',
            
            'nomor.required' => 'Nomor tidak boleh kosong.',
            'nomor.numeric' => 'Nomor harus berupa angka.'
        ];
    }
}
