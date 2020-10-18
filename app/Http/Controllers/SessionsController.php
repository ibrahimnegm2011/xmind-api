<?php

namespace App\Http\Controllers;

use App\Model\FoodStuff;
use App\Model\Session;
use App\Model\Device;
use App\Model\SessionDevice;
use App\Model\SessionFood;
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

    public function stopDevice(Request $request)
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

    public function addFood(Request $request)
    {

        $user = Auth::user();
        $account_id = $user->loggable->getAccountId();
        $shift = Shift::where('account_id', $account_id)->where('status', 'started')->first(['id']);

        $data = $request->all();

        $data['user_id'] = $user->id;

        if (!$shift) {
            return $this->fail("shift_not_started", "shift_not_started", [], 402);
        }

        $check = $this->adminValidator([
            'user_id' => 'required|integer|exists:users,id',
            'session_id' => [
                'required',
                Rule::exists('sessions', 'id')
                    ->where('status', 'opened')
                    ->where('shift_id', $shift->id)
            ],
            'shift_id' => [
                'required',
                Rule::exists('shifts', 'id')
                    ->where('status', 'started')
                    ->where('account_id', $account_id),
            ],
            'item_id' => ['required',
                Rule::exists('food_stuff', 'id')
                    ->where('active', true)
                    ->where('account_id', $account_id),
            ],
            'quantity' => ['integer', 'required'],
            'cost' => ['numeric', 'required']
        ], $data);
        if ($check !== true)
            return $check;

        $food = FoodStuff::find($data['item_id']);

        $foodObject = [
            'user_id' => $user->id,
            'shift_id' => $shift->id,
            'session_id' => $data['session_id'],
            'item_id' => $food['id'],
            'quantity' => $data['quantity'],
            'item_price' => $food['price'],
            'cost' => $data['cost']
        ];

        $sessionFood = new SessionFood();
        $sessionFood->fill($foodObject);
        $sessionFood->save();

        return $this->success([
            'status' => 'success',
            'sessionFood' => $sessionFood
        ]);
    }

    public function closeSession($id, Request $request)
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

        $session = Session::where('shift_id', $shift->id)->where('status', 'opened')->find($id);
        if (!$session) {
            return $this->fail("session_not_opened", "session_not_opened", [], 402);
        }

        $sessionDevice = SessionDevice::where('shift_id', $shift->id)
            ->where('session_id', $session->id)
            ->where('end', '=', null)->first('end');
        if ($sessionDevice) {
            return $this->fail("devices_not_stopped", "devices_not_stopped", [], 402);
        }

        $check = $this->adminValidator([
            'user_id' => 'required|integer|exists:users,id',
            'account_id' => 'required|integer|exists:accounts,id',
            'paid' => 'numeric|required',
        ], $data);

        if ($check !== true)
            return $check;

        $totalDevices = SessionDevice::where('shift_id', $shift->id)
            ->where('session_id', $session->id)->sum('cost');
        $totalFoods = SessionFood::where('shift_id', $shift->id)->where('session_id', $session->id)->sum('cost');
        $total = $totalDevices + $totalFoods;
        $paid = $data['paid'];
        $res = $session->update([
            'session_end' => date('Y-m-d H:i:s'),
            'device_price' => $totalDevices,
            'food_price' => $totalFoods,
            'total_price' => $total,
            'status' => "closed",
            'paid' => $paid
        ]);
        if ($res) {
            return $this->success($session);
        } else {

            return $this->fail('Close_fail', "Invalid Data", [], 404);

        }
    }

    public function delete($id)
    {

        $user = Auth::user();
        $account_id = $user->loggable->getAccountId();
        $shift = Shift::where('account_id', $account_id)->where('status', 'started')->first(['id']);
        $sessions = Session::where('id', $id)->where('status', 'opened')->where('shift_id', $shift->id)->find($id);
        $device = SessionDevice::where('shift_id', $shift->id)
            ->where('session_id', $sessions->id)->first();
        $food = SessionFood::where('shift_id', $shift->id)
            ->where('session_id', $sessions->id)->first();

        if ($food == null & $device == null) {
            $action = $sessions->delete($id);
            if ($action) {
                return $this->success([
                    'status' => 'success',
                    "response" => ["action" => 'Deleted']
                ], 202);
            }
        } else {
            return $this->fail('Invalid', "This session is not empty", [], 404);
        }


    }

}