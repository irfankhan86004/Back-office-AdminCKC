<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarouselLang extends Model
{
    protected $table = 'carousel_lang';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['carousel_id', 'language_id', 'title', 'subtitle', 'description', 'btn'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function carousel()
    {
        return $this->belongsTo('App\Models\Carousel');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language')->orderBy('id', 'ASC');
    }
}
