<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
            'last_name' => 'required|min:3|max:255',
            'first_name' => 'required|min:3|max:255',
            'email'	=> 'required|email',
            'media' => 'image',
        ];
        
        // Méthode POST = store
        if (! \Request::get('_method')) {
            $rules['password'] = 'required|min:6';
            $rules['email'] = 'required|email|unique:admins';
        }

        return $rules;
    }
}
