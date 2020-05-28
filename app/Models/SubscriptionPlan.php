<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $table = 'subscription_plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'desc', 'active', 'branches', 'devices', 'food_stuff', 'employees',
        'is_monthly', 'monthly_cost', 'is_annual', 'annual_cost'
    ];

    protected $casts = [
        'active' => 'boolean',
        'is_monthly' => 'boolean',
        'is_annual' => 'boolean'
    ];


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
