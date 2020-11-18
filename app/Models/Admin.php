<?php

namespace App\Models;

use Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\BelongsToMedia;

class Admin extends Authenticatable
{
    use BelongsToMedia;

    protected $table = 'admins';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['media_id', 'reception', 'email', 'last_name', 'first_name', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Set the password for the user.
     *
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role')->withTimestamps();
    }

    public function displayName()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function hasPermission($id)
    {
        foreach ($this->roles as $r) {
            if ($r->id == $id) {
                return true;
                break;
            }
        }
        return false;
    }

    public function gravatar($email, $s = 150, $d = 'mm', $r = 'g', $img = false, $atts = array())
    {
        if ($this->media && file_exists(config('paths.medias').$this->media->name)) {
            return $this->media->route($s, $s);
        } else {
            $url  = 'https://www.gravatar.com/avatar/';
            $url .= md5(strtolower(trim($email)));
            $url .= "?s=$s&d=$d&r=$r";
            if ($img) {
                $url = '<img src="' . $url . '"';
                foreach ($atts as $key => $val) {
                    $url .= ' ' . $key . '="' . $val . '"';
                }
                $url .= ' />';
            }
            return $url;
        }
    }

    public function canAccessMedia($media)
    {
        $access = true;
        if ($this->medias_status == 'subsidiary_validator') {
            if (!in_array($media->admin_id, $this->accessibleAdmins())) {
                $access = false;
            }
        } elseif ($this->medias_status == 'subsidiary_contributor') {
            if ($media->admin_id != $this->id) {
                $access = false;
            }
        }
        return $access;
    }

    public function canDeleteMedia($media)
    {
        return ($this->mediasAdmin() || (!$this->mediasAdmin() && $media->admin_id == $this->id));
    }

    public function mediasAdmin()
    {
        return $this->medias_status == 'standard';
    }

    public function defaultMediaStatus()
    {
        if ($this->medias_status == 'standard') {
            return 'published';
        } elseif ($this->medias_status == 'subsidiary_validator') {
            return 'checked';
        } else {
            return 'submitted';
        }
    }

    public function availableMediaStatus()
    {
        $status = status();
        if ($this->medias_status != 'standard') {
            unset($status['published']);
            if ($this->medias_status != 'subsidiary_validator') {
                unset($status['checked']);
            }
        }
        return $status;
    }
}
