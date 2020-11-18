<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedirectU extends Model
{
    protected $table = 'redirects';

    protected $fillable = ['active', 'type', 'origin_url', 'page_id', 'blog_post_id', 'link', 'target'];
    
    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }
    
    public function post()
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id');
    }
    
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
    
    public function setOriginUrlAttribute($value)
    {
        $parse_url_value = parse_url($value);
        if (isset($parse_url_value['path'])) {
            $value = $parse_url_value['path'];
            if (isset($parse_url_value['query'])) {
                $value .= '?'.$parse_url_value['query'];
            }
        }
        $this->attributes['origin_url'] = trim($value);
    }
	
	public static function getTypes()
	{
		return [
			'permanent' => 301,
			'temporary' => 307,
		];
	}
	
	public static function getSelectTypes()
	{
		$types = [];
		foreach (self::getTypes() as $type => $code) {
			$types[$type] = ucfirst($type).' ('.$code.')';
		}
		return $types;
	}
	
	public static function getSelectTargets()
	{
		return [
			'_self' => 'La même fenêtre',
			'_blank' => 'Une nouvelle fenêtre'
		];
	}
	
	public function showRedirection()
	{
		if ($this->page){
			return '<a href="'.route('pages.edit', $this->page->id).'">Page #'.$this->page->id.' - '.$this->page->getAttr('name').'('.$this->page->getAttr('url').')</a>';
		} elseif ($this->post){
			return '<a href="'.route('articles-blog.edit', $this->post->id).'">Article #'.$this->post->id.' - '.$this->post->getAttr('name').'</a>';
		} elseif(isset($this->link)){
			return $this->link;
		} else {
			return "<span class='text-danger'><i class='fas fa-warning'></i>&nbsp;&nbsp;Pas de redirection&nbsp;&nbsp;<i class='fas fa-warning'></i></span>";
		}
	}
}
