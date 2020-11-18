<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageHistory extends Model
{
    protected $table = 'pages_history';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['page_id','language_id','before','after','admin_id'];

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
        return $this->belongsTo('App\Models\Language');
    }
    
    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }
}
