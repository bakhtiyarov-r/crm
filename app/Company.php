<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function departments()
    {
        return $this->hasMany('App\Department');
    }

    public function projects()
    {
        return $this->hasMany('App\Project');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }

    public function owner()
    {
    	return $this->hasOne('App\User', 'id', 'owner_id');
    }
}
