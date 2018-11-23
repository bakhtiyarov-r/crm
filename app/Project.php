<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title', 'description', 'opened', 'closed'
    ];

    public function getCreatedAtAttribute($value) {
        return \Carbon\Carbon::parse($value)->format('Y-m-d');
    }


	public function tasks()
    {
        return $this->hasMany('App\Task');
    }

    public function documents()
    {
        return $this->hasManyThrough('App\Document', 'App\Task');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function executors()
    {
        return $this->belongsToMany('App\User');
    }
}
