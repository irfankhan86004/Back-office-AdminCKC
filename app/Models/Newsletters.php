<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletters extends Model
{
    protected $table = 'newsletters';
    
    protected $guarded = ['id'];
    
    protected $hidden = [];
}
