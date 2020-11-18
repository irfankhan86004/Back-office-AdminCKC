<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Language;

class MenuRequest extends FormRequest
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
            'position' 		=> 'required|numeric|min:0',
            'parent_id'		=> 'required|numeric|min:1',
        ];

        foreach (Language::all() as $l) {
            $rules['name_'.$l->short] = 'required';
        }

        return $rules;
    }
}
