<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function hasSudo($permission)
    {
        if ($this->slug == $permission) {
            return true;
        }
        
    }

    public function hasPermission($permission)
    {
        return $this->role->permissions->contains('slug', $permission);
    }

    public function getCreatedAtAttribute($value) {
        return \Carbon\Carbon::parse($value)->format('Y-m-d');
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function profile()
    {
        return $this->hasOne('App\UsersProfile');
    }

    public function projects()
    {
        return $this->hasMany('App\Project');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function userProjects()
    {
        return $this->belongsToMany('App\Project');
    }

    public function userTasks()
    {
        return $this->belongsToMany('App\Task');
    }

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

}
