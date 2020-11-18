<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCategoryLang extends Model
{
    protected $table = 'blog_categories_lang';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['blog_category_id', 'language_id', 'name', 'title', 'keywords', 'description'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function category()
    {
        return $this->belongsTo('App\Models\BlogCategory', 'blog_category_id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language')->orderBy('id', 'ASC');
    }
}
