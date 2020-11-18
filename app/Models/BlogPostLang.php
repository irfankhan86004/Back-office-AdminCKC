<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPostLang extends Model
{
    protected $table = 'blog_posts_lang';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['blog_post_id', 'language_id', 'name', 'url', 'title', 'keywords', 'description', 'text'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function article()
    {
        return $this->belongsTo('App\Models\BlogPost', 'blog_post_id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language')->orderBy('id', 'ASC');
    }
}
