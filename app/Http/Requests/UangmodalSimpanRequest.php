<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UangmodalSimpanRequest extends FormRequest
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
            'nominal' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'nominal.required' => 'Nominal tidak boleh kosong.',
            'nominal.numeric' => 'Nominal harus berupa numerik.'
        ];
    }
}
