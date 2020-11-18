<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\MorphManyMedias;

use Carbon\Carbon;
use Hash;

class User extends LangModel
{
    use MorphManyMedias;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'user_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function getCreatedAtAttribute($value)
    {
        if ($value) {
            return Carbon::createFromFormat("Y-m-d H:i:s", $value)->format("d/m/Y - H:i");
        }
    }

    public function fullName()
    {
        $fullName = $this->first_name . ' ' . $this->last_name;
        return $fullName;
    }
}
