<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public static function generateSelect()
    {
        foreach (Language::all() as $language) {
            $languages[$language->id] = $language->name;
        }
        return $languages;
    }
}
