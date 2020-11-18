<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Tag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'layout', 'position', 'order', 'content'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function pages()
    {
        return $this->belongsToMany('App\Models\Page', 'tags_pages', 'tag_id', 'page_id');
    }

    public function posts()
    {
        return $this->belongsToMany('App\Models\BlogPost', 'tags_blog_posts', 'tag_id', 'blog_post_id');
    }

    public function pageCategories()
    {
        return $this->belongsToMany('App\Models\PageCategory', 'tags_page_categories', 'tag_id', 'page_category_id');
    }

    public function blogCategories()
    {
        return $this->belongsToMany('App\Models\BlogCategory', 'tags_blog_categories', 'tag_id', 'blog_category_id');
    }

    public static function positions()
    {
        return [
            'head' => 'Dans la balise Head',
            'body' => 'Dans la balise Body',
        ];
    }

    public function getCreatedAtAttribute($value)
    {
        if ($value) {
            return Carbon::createFromFormat("Y-m-d H:i:s", $value)->format("d/m/Y - H:i");
        }
    }
}
