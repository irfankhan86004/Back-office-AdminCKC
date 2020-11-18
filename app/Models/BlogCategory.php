<?php

namespace App\Models;

use App\Models\BlogCategoryLang;

class BlogCategory extends LangModel
{
    protected $table = 'blog_categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['position'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function lang()
    {
        return $this->hasMany('App\Models\BlogCategoryLang');
    }

    public function getAttr($attribut)
    {
        return $this->getLangAttr($attribut);
    }

    public function posts()
    {
        return $this->belongsToMany('App\Models\BlogPost', 'blog_posts_categories', 'blog_category_id', 'blog_post_id');
    }

    public static function create_select()
    {
        $select = [];
        foreach (BlogCategoryLang::where('language_id', 1)->orderBy('name', 'ASC')->get() as $blogCategoryLang) {
            if ($blogCategoryLang->category) {
                $select[$blogCategoryLang->category->id] = $blogCategoryLang->category->getAttr('name');
            }
        }
        return $select;
    }
}
