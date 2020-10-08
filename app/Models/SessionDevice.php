<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SessionDevice extends Model
{
    protected $table = 'session_devices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'shift_id', 'session_id', 'device_id', 'start', 'end', 'time_spent',
        'is_multi', 'hour_rate', 'cost'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id', 'id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
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
