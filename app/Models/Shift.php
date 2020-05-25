<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $table = 'shifts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'shift_date', 'start', 'end', 'status', 'total_records', 'total_times', 'records_revenue',
        'food_revenue', 'total_revenue'
    ];


    public function toArray()
    {
        $arr = parent::toArray();

        $arr['shift_date'] = date("Y-m-d", strtotime($arr['shift_date']));

        if (isset($arr['created_at']))
            $arr['created_at'] = date("Y-m-d H:i:s", strtotime($arr['created_at']));
        if (isset($arr['updated_at']))
            $arr['updated_at'] = date("Y-m-d H:i:s", strtotime($arr['updated_at']));

        return $arr;
    }
}
