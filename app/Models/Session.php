<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'sessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'shift_id', 'session_start', 'session_end', 'status',
        'client_name', 'persons_no', 'device_price', 'food_price', 'total_price','paid'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'user_id', 'id');
    }

    public function sessionDevices(){
        return $this->hasMany(SessionDevice::class, 'session_id', 'id');
    }

    public function sessionFoods(){
        return $this->hasMany(SessionFood::class, 'session_id', 'id');
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
