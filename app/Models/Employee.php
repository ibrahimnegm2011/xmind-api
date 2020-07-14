<?php

namespace App\Model;


use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Employee extends Model
{
    protected $fillable = [
      'account_id', 'name' , 'email' , 'phone' , 'address' , 'salary' , 'join_date'

    ];
    protected $table = 'employees';

    /**
     * @return MorphOne
     */
    function user()
    {
        return $this->morphOne(User::class, 'loggable');
    }

    /**
     * @return BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function getAccountId()
    {
        return $this->account_id;
    }

    public function getAccount()
    {
        return $this->account;
    }
}
