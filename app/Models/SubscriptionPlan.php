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
        'name', 'desc', 'active', 'branches', 'ps4', 'foods_items', 'sub_users',
        'is_monthly', 'monthly_cost', 'is_annual', 'annual_cost'
    ];
}
