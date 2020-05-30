<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FoodStuff extends Model
{
    protected $table = 'food_stuff';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id', 'name', 'price', 'note', 'active'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function toArray()
    {
        $arr = parent::toArray();

        if (isset($arr['created_at']))
            $arr['created_at'] = date("Y-m-d H:i:s", strtotime($arr['created_at']));
        if (isset($arr['updated_at']))
            $arr['updated_at'] = date("Y-m-d H:i:s", strtotime($arr['updated_at']));

        return $arr;
    }
}
