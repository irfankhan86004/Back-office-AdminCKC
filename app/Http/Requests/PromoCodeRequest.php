<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromoCodeRequest extends FormRequest
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
        $rules = [
            'name' => 'required',
            'code' => 'required',
            'value' => 'required|numeric',
        ];

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'name.required' => 'Le champs Nom est obligatoire',
            'code.required' => 'Le champs Code est obligatoire',
            'value.required' => 'Le champs Valeur est obligatoire',
            'value.numeric' => 'Le champs Valeur doit être numérique',
        ];

        return $messages;
    }
}
