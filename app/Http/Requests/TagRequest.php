<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
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
            'title' => 'required',
            'content' => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'title.required' => 'Le champs Titre est obligatoire',
            'content.required' => 'Le champs Contenu est obligatoire',
        ];

        return $messages;
    }
}
