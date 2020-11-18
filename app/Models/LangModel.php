<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language;

class LangModel extends Model
{
    public function getLangAttr($attr, $lang_id = null)
    {
        if ($lang_id == null) {
            $lang = Language::whereShort(\App::getLocale())->first();
            $lang_id = $lang->id;
        }

        foreach ($this->lang as $l) {
            if ($l->language_id == $lang_id) {
                return $l->{$attr};
            }
        }
    }
}
