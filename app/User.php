<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    public $timestamps= false;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username','last_login'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'api_token'
    ];


    public function loggable()
    {
        return $this->morphTo();
    }


    public function toArray()
    {
        $arr = parent::toArray();

        $userTypeData = $this->loggable->toArray();
        unset($userTypeData['id']);

        $arr['type'] = $this->getUserType();
        $arr["{$arr['type']}_id"] = $arr['loggable_id'];
        unset($arr['loggable_type']);
        unset($arr['loggable_id']);

        $arr += $userTypeData;

        return $arr;
    }


    public function getUserType(){
        return strtolower(explode("\\", $this->loggable_type)[2]);
    }
}
