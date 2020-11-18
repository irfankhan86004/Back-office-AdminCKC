<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class LogEmail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::createFromFormat("Y-m-d H:i:s", $value)->format("d/m/Y - H:i");
    }
}
