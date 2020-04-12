<?php

namespace App\Model;


use App\User;

class Account extends User
{

    public function subUsers(){
        return $this->hasMany(SubUser::class, 'parent_id', 'id');
    }
}
