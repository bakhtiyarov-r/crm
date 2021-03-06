<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersProfile extends Model
{
	protected $fillable = [
		'surname', 'phone', 'birthday', 'position', 'avatar'
	];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
