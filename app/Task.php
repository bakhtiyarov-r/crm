<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title', 'description', 'opened', 'done', 'closed', 'deadline', 'immediate', 'drafts', 'canceled'   
    ];

    public function getDeadlineAttribute($value) {
        return \Carbon\Carbon::parse($value)->format('Y-m-d');
    }

    public function getCreatedAtAttribute($value) {
        return \Carbon\Carbon::parse($value)->format('Y-m-d');
    }

    public function documents()
    {
        return $this->hasMany('App\Document');
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
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
