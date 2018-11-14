<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = "tasks";
    public $timestamps = true;

    public function documents()
    {
        return $this->hasMany('App\Document');
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function project()
    {
    	return $this->belongsTo('App\Project');
    }

    public function executors()
    {
        return $this->belongsToMany('App\User');
    }
}
