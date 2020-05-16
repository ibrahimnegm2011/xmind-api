<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\SubscriptionPlan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SubscriptionPlansController extends Controller
{

    public function index(Request $request)
    {
        $check = $this->adminValidator([
            'pagination' => 'numeric',
            'name' => 'string|max:255',
        ], $request->all());

        if ($check !== true)
            return $check;

        $pagination = $request->pagination;
        if (is_null($request->pagination))
            $pagination = $this->pagination;

        $data = SubscriptionPlan::select('id', 'name', 'active', 'is_monthly', 'monthly_cost', 'is_annual', 'annual_cost', 'created_at');

        if ($request->name) {
            $data = $data->where('name', 'Like', "%{$request->name}%");
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
