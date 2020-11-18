<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Language;

class PageRequest extends FormRequest
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
        foreach (Language::all() as $l) {
            $rules['name_'.$l->short] = 'required';
            $rules['url_'.$l->short] = 'required|unique:pages_lang,url,'.$this->getSegmentFromEnd().',page_id|regex:/^[a-zA-Z0-9][a-zA-Z0-9\-\_]+[a-zA-Z0-9]$/';
            $rules['canonical_url_'.$l->short] = 'sometimes|nullable|url';
        }
        
        return $rules;
    }
    
    private function getSegmentFromEnd($position_from_end = 1)
    {
        $segments = $this->segments();
        return $segments[sizeof($segments) - $position_from_end];
    }
}
