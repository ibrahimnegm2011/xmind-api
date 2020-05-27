<?php

namespace App\Model;


use App\User;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admins';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    function user(){
        return $this->morphOne(User::class, 'loggable');
    }
}
