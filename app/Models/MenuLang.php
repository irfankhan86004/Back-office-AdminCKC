<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuLang extends Model
{
    protected $table = 'menu_lang';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['menu_id', 'language_id', 'name', 'title', 'keywords', 'description'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    
    public function menu()
    {
        return $this->belongsTo('App\Models\Menu');
    }
    
    public function language()
    {
        return $this->belongsTo('App\Models\Language')->orderBy('id', 'ASC');
    }
}
