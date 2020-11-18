<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaLink extends Model
{
    protected $table = 'medias_links';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['media_id_1', 'media_id_2'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    
    public function media1()
    {
        return $this->belongsTo('App\Models\Media', 'media_id_1');
    }
    
    public function media2()
    {
        return $this->belongsTo('App\Models\Media', 'media_id_2');
    }
}
