<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'company_id', 'title', 'description',
    ];

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

}
