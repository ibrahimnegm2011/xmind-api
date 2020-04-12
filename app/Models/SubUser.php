<?php

namespace App\Model;


use App\User;

class SubUser extends User
{

    public function account(){
        return $this->belongsTo(Account::class, 'parent_id', 'id');
    }

}
