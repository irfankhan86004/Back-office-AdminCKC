<?php

namespace App\Models;

use App\Traits\MorphManyMedias;
use Illuminate\Database\Eloquent\Model;

class Carousel extends LangModel
{
    use MorphManyMedias;

    protected $table = 'carousel';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['position', 'page_id', 'blog_post_id', 'link', 'target', 'published', 'background_slide', 'background_btn', 'link'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['picture_id', 'logo_id'];

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function page()
    {
        return $this->belongsTo('App\Models\Page', 'page_id');
    }

    public function post()
    {
        return $this->belongsTo('App\Models\BlogPost', 'blog_post_id');
    }

    // Affiche textuellement pour l'admin
    public function lien_url()
    {
        if ($this->link != '' && $this->link != null) {
            return $this->link;
        } elseif ($this->post) {
            return 'Article #'.$this->post->id.' - '.$this->post->getAttr('name');
        } elseif ($this->page) {
            return 'Page #'.$this->page->id.' - '.$this->page->getAttr('name').' ('.$this->page->getAttr('url').')';
        }
    }

    // Retourne l'url brut
    public function url()
    {
        if ($this->link != '' && $this->link != null) {
            return $this->link;
        } elseif ($this->post) {
            return route('post.edit', [$this->post->id, str_slug($this->post->name)]);
        } elseif ($this->page) {
            return route('page', str_slug($this->page->url));
        }
    }

    public function lang()
    {
        return $this->hasMany('App\Models\CarouselLang');
    }

    public function getAttr($attribut)
    {
        return $this->getLangAttr($attribut);
    }

    public function getPicture($width = null, $height = null)
    {
        if (!empty($this->getMedias()[0])) {
            return $this->getMedias()[0]->route($width, $height);
        } else {
            return 'https://via.placeholder.com/80x80?text=' . config('app.name');
        }
    }
}
