<?php

namespace App\Http\Controllers;

use App\Model\Shift;
use App\Model\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftsController extends Controller
{

    public function index(Request $request)
    {
        $check = $this->adminValidator([
            'pagination' => 'numeric',
            'username' => 'numeric',
            'date' => 'date|max:255',
        ], $request->all());

        if ($check !== true)
            return $check;

        $pagination = $request->pagination;
        if (is_null($request->pagination))
            $pagination = $this->pagination;

        $data = Shift::select('id', 'user_id', 'shift_date', 'start', 'end', 'status', 'total_records',
            'total_times', 'records_revenue', 'food_revenue', 'total_revenue');

        if ($request->user_id) {
            $data = $data->where('user_id', $request->user_id);
        }
        if ($request->date) {
            $data = $data->where('shift_date', $request->date);
        }

        return $this->success($data->paginate($pagination));
    }

    public function current(Request $request)
    {
        $user = Auth::user();
        $account_id = $user->loggable->getAccountId();

        $currentShift = Shift::where('account_id', $account_id)->where('status', 'started')->first();

        if (!$currentShift) {
            return $this->success([
                'shift' => null,
                'creatable' => true,
                'canAccess' => true
            ]);
        }

        if ($user->getUserType() == 'account' || $currentShift->user_id == $user->id) {
            $data = [
                'shift' => $currentShift,
                'creatable' => false,
                'canAccess' => true
            ];
        } else {
            $data = [
                'shift' => [
                    'user_id' => $currentShift->user_id,
                    'name' => $currentShift->user->name,
                    'date' => $currentShift->shift_date,
                    'start' => $currentShift->start
                ],
                'creatable' => false,
                'canAccess' => false,
            ];
        }

        return $this->success($data);
    }

    public function show(Request $request)
    {

        dd('show');
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $account_id = $user->loggable->getAccountId();

        $currentShift = Shift::where('account_id', $account_id)->where('status', 'started')->first();

        if ($currentShift) {
            return $this->fail("shift_already_started", "shift_already_started", [], 402);
        }

        $data = [
            'user_id' => $user->id,
            'account_id' => $account_id,
            'shift_date' => date('Y-m-d'),
            'start' => date('H:i:s'),
            'status' => 'started'
        ];

        $shift = new Shift();
        $shift->fill($data);
        $shift->save();

        return $this->success([
            'status' => 'success',
            'shift' => $shift
        ]);
    }

    public function update(Request $request)
    {

        dd('update');
    }

    public function delete(Request $request)
    {
        dd('delete');

    }
}
