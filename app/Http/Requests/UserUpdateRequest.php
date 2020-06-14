<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'name' => 'required|regex:/^[a-zA-Z ]+$/u|max:50',
            'email' => 'required|email',
            'password' => 'max:50',
            'alamat' => 'required|max:150',
            'nomorhp' => 'required|min:13|numeric'

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama tidak boleh kosong.',
            'name.regex' => 'Nama Tidak Boleh Terdapat Angka.',
            'name.max' => 'Nama user maksimal :max karakter.',

            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Pastikan Format Email Benar.',

            'password.required' => 'Password tidak boleh kosong.',
            'password.max' => 'Password maksimal :max karakter.',

            'alamat.required' => 'Alamat tidak boleh kosong.',
            'alamat.max' => 'Alamat maksimal :max karakter.',
            
            'nomorhp.required' => 'Nomor tidak boleh kosong.',
            'nomorhp.numeric' => 'Nomor harus berupa angka.',
            'nomorhp.min' => 'Nomor maksimal :min karakter.',

        ];
    }
}
