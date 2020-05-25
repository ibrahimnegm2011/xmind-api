<?php

namespace App\Http\Controllers;

use App\Model\Shift;
use App\Model\SubscriptionPlan;
use Illuminate\Http\Request;

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

    public function show(Request $request)
    {

        dd('show');
    }

    public function create(Request $request)
    {

        dd('create');
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
