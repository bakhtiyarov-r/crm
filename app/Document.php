<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public function task()
    {
    	return $this->belongsTo('App\Task');
    }
}
