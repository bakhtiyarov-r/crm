<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	protected $hidden = [
        'slug', 'name',
    ];

    public function permissions()
    {
        return $this->belongsToMany('App\Permission');
    }
    
}
