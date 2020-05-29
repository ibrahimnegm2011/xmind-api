<?php

namespace App\Model;


use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Account extends Model
{
    protected $table = 'accounts';


    /**
     * @return MorphOne
     */
    function user(){
        return $this->morphOne(User::class, 'loggable');
    }

    /**
     * @return HasMany
     */
    public function employees(){
        return $this->hasMany(Employee::class, 'account_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function plan(){
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id', 'id');
    }


    public function getAccountId(){
        return $this->id;
    }

    public function getAccount(){
        return $this;
    }
}
