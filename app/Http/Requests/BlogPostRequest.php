<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Language;

class BlogPostRequest extends FormRequest
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
        $rules = ['categories' => 'required'];

        foreach (Language::all() as $l) {
            $rules['name_'.$l->short] = 'required';
            $rules['url_'.$l->short] = 'nullable|unique:blog_posts_lang,url,'.$this->getSegmentFromEnd().',blog_post_id';
        }

        return $rules;
    }
    
    private function getSegmentFromEnd($position_from_end = 1)
    {
        $segments = $this->segments();
        return $segments[sizeof($segments) - $position_from_end];
    }
}
