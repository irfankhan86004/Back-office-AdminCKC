<?php

namespace App\Models;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['roles'];

    public function admins()
    {
        return $this->belongsToMany('App\Models\Admin')->withTimestamps();
    }
}
