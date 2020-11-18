<?php

namespace App\Models;

use App\Traits\MorphManyMedias;
use Illuminate\Database\Eloquent\Model;
use DB;

class Page extends LangModel
{
    use MorphManyMedias;

    protected $table = 'pages';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['position', 'published', 'footer', 'admin_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function lang()
    {
        return $this->hasMany('App\Models\PageLang');
    }

    public function getAttr($attribut, $lang_id = null)
    {
        return $this->getLangAttr($attribut, $lang_id);
    }

    public function history()
    {
        return $this->hasMany('App\Models\PageHistory');
    }

    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }

    public function product()
    {
        return $this->hasOne('App\Models\Product');
    }

    public function scopeFooter($query)
    {
        return $query->where('footer', true);
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function url()
    {
        return route('page', [$this->getAttr('url')]);
    }

    public static function create_select()
    {
        $select = [];
        foreach (PageLang::orderBy('name', 'ASC')->get() as $pl) {
            if ($pl->page) {
                $select[$pl->page->id] = $pl->page->getAttr('name');
            }
        }
        return $select;
    }
}
