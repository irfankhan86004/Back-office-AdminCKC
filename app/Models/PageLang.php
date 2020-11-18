<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageLang extends Model
{
    protected $table = 'pages_lang';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['page_id', 'language_id', 'url', 'canonical_url', 'name', 'subname', 'title', 'keywords', 'description', 'text'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    
    public function page()
    {
        return $this->belongsTo('App\Models\Page');
    }
    
    public function language()
    {
        return $this->belongsTo('App\Models\Language')->orderBy('id', 'ASC');
    }
    
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }
}
