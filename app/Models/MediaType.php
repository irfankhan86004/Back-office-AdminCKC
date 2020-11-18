<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaType extends Model
{
    protected $table = 'medias_types';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['media_id','type_id','type','position'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    
    public function media()
    {
        return $this->belongsTo('App\Models\Media');
    }
}
