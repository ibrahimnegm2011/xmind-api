<?php

namespace App\Http\Controllers;

use App\Model\Device;
use App\Model\Session;
use App\Model\SessionDevice;
use App\Model\Shift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SessionsController extends Controller
{

    public function create(Request $request)
    {
        $user = Auth::user();
        $account_id = $user->loggable->getAccountId();

        $data = $request->all();
        $data['account_id'] = $account_id;
        $data['user_id'] = $user->id;

        $check = $this->adminValidator([
            'user_id' => 'required|integer|exists:users,id',
            'account_id' => 'required|integer|exists:accounts,id',
            'shift_id' => [
                'required',
                Rule::exists('shifts', 'id')
                    ->where('status', 'started')
                    ->where('account_id', $account_id),
            ],
            'client_name' => 'string|max:100',
            'persons_no' => 'integer',
        ], $data);

        if ($check !== true)
            return $check;

        $data = [
            'user_id' => $user->id,
            'shift_id' => $data['shift_id'],
            'session_start' => date('Y-m-d H:i:s'),
            'status' => 'opened',
            'client_name' => $data['client_name'],
            'persons_no' => $data['persons_no'],
        ];

        $session = new Session();
        $session->fill($data);
        $session->save();

        return $this->success([
            'status' => 'success',
            'session' => $session->load(['sessionDevices', 'sessionFoods'])
        ]);
    }


    public function addDevice(Request $request)
    {
        $user = Auth::user();
        $account_id = $user->loggable->getAccountId();

        $data = $request->all();
        $data['account_id'] = $account_id;
        $data['user_id'] = $user->id;

        $shift = Shift::where('account_id', $account_id)->where('status', 'started')->first(['id']);

        if (!$shift) {
            return $this->fail("shift_not_started", "shift_not_started", [], 402);
        }

        $check = $this->adminValidator([
            'user_id' => 'required|integer|exists:users,id',
            'account_id' => 'required|integer|exists:accounts,id',
            'session_id' => [
                'required',
                Rule::exists('sessions', 'id')
                    ->where('status', 'opened')
                    ->where('shift_id', $shift->id)
            ],
            'device' => [
                'required',
                Rule::exists('devices', 'id')
                    ->where('active', true)
                    ->where('account_id', $account_id),
            ],
            'multi' => 'boolean',
        ], $data);

        if ($check !== true)
            return $check;

        $openedSessionDevice = SessionDevice::where('device_id', $data['device'])
            ->where('shift_id', $shift->id)->whereNull('end')->first();

        if ($openedSessionDevice) {
            return $this->fail("has_opened_device", "has_opened_device", [], 402);
        }


        $device = Device::find($data['device']);

        $data = [
            'user_id' => $user->id,
            'shift_id' => $shift->id,
            'session_id' => $data['session_id'],
            'device_id' => $data['device'],
            'start' => date('Y-m-d H:i:s'),
            'is_multi' => $data['multi'],
            'hour_rate' => $data['multi'] ? $device->multi_hour_rate : $device->normal_hour_rate
        ];

        $sessionDevice = new SessionDevice();
        $sessionDevice->fill($data);
        $sessionDevice->save();

        return $this->success([
            'status' => 'success',
            'sessionDevice' => $sessionDevice
        ]);
    }

    public function stopDevice(Request $request){
        $user = Auth::user();
        $account_id = $user->loggable->getAccountId();

        $data = $request->all();
        $data['account_id'] = $account_id;
        $data['user_id'] = $user->id;

        $shift = Shift::where('account_id', $account_id)->where('status', 'started')->first(['id']);
        if (!$shift) {
            return $this->fail("shift_not_started", "shift_not_started", [], 402);
        }

        $sessionDevice = SessionDevice::find($data['session_device_id']);
        if (!$sessionDevice) {
            return $this->fail("session_device_not_found", "session_device_not_started", [], 402);
        }


        $times = explode(':', $data['time_spent']);
        $hours = $times[0];
        $minutes = $times[1];

        $endTime = Carbon::make($sessionDevice->start)->copy()
            ->addHours($hours)
            ->addMinutes($minutes);

        $sessionDevice->time_spent = $data['time_spent'];
        $sessionDevice->end = $endTime;

        $sessionDevice->cost = $sessionDevice->calculateCost();
        $sessionDevice->save();

        return $this->success([
            'status' => 'success',
            'sessionDevice' => $sessionDevice
        ]);
    }

}
