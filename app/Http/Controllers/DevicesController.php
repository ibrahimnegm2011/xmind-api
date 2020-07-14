<?php

namespace App\Http\Controllers;

use App\Model\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DevicesController extends Controller
{

    private function filterList($query, $data)
    {
        $query->where("account_id", Auth::user()->loggable->getAccountId());

        $query = $this->filterStrings($query, ['name', 'type'], $data);

        if(isset($data['active']) && !is_null($data['active'])){
            $query->where('active', $data['active']);
        }

        if(isset($data['has_multi']) && !is_null($data['has_multi'])){
            $query->where('has_multi', $data['has_multi']);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $check = $this->adminValidator([
            'pagination' => 'numeric',
            'name' => 'max:255',
            'type' => 'max:255',
            'active' => 'boolean',
            'has_multi' => 'boolean',
        ], $request->all());

        if ($check !== true)
            return $check;

        if (is_null($request->pagination))
            $pagination = $this->pagination;

        $query = Device::select('id', 'account_id', 'name', 'type', 'active',
            'normal_hour_rate', 'has_multi', 'multi_hour_rate');

        $query = $this->filterList($query, $request->all());

        return $this->success($query->paginate($pagination));
    }

    public function show($id)
    {
        $data = ['id' => $id];
        $data['account_id'] = Auth::user()->loggable->getAccountId();

        $check = $this->adminValidator([
            'id' => 'required|integer|exists:devices,id',
            'account_id' => 'required|integer|exists:accounts,id',
        ], $data);

        if ($check !== true)
            return $check;

        $device = Device::find($id);

        if(!$device){
            return $this->fail('not_found', "Not Found", [], 404);
        }else{
            return $this->success($device);
        }
    }

    public function create(Request $request)
    {
        //check package validation
        $account = Auth::user()->loggable->getAccount();
        $devicesCount = Device::where('account_id', $account->id)->count();
        if($account->plan->devices != 0 && $account->plan->devices <= $devicesCount){
            return $this->fail('package_exceeded', "Package Exceeded", [], 402);
        }

        $data = $request->all();
        $data['account_id'] = $account->id;

        $check = $this->adminValidator([
            'account_id' => 'required|integer|exists:accounts,id',
            'name' => 'required|string|max:100',
            'type' => 'required|string|max:100',
            'active' => 'required|boolean',
            'has_multi' => 'required|boolean',
            'normal_hour_rate' => 'required|numeric',
            'multi_hour_rate' => 'required|numeric',
        ], $data);

        if ($check !== true)
            return $check;

        $device = new Device();
        $device->fill($data);
        $device->save();

        return $this->success([
            'status' => 'success',
            "response" => ["action" => 'Created']
        ], 201);
    }

    public function update($id, Request $request)
    {
        $data = $request->all();
        $data['id'] = $id;
        $data['account_id'] = Auth::user()->loggable->getAccountId();


        $check = $this->adminValidator([
            'id' => 'required|integer|exists:devices,id',
            'account_id' => 'required|integer|exists:accounts,id',
            'name' => 'required|string|max:100',
            'type' => 'required|string|max:100',
            'active' => 'required|boolean',
            'has_multi' => 'required|boolean',
            'normal_hour_rate' => 'required|numeric',
            'multi_hour_rate' => 'required|numeric',
        ], $data);

        if ($check !== true)
            return $check;

        unset($data['id']);
        unset($data['account_id']);

        $device = Device::find($id);
        $device->update($data);

        return $this->success([
            'status' => 'success',
            "response" => ["action" => 'The request has been accepted for processing']
        ], 202);
    }

    public function delete($id, Request $request)
    {
        $check = $this->adminValidator([
            'id' => 'required|integer|exists:devices,id'
        ], ['id' => $id]);

        if ($check !== true)
            return $check;

        $delete = Device::where('id', $id)->delete();

        if($delete)
            return $this->success([
                'status' => 'success',
                "response" => ["action" => 'The request has been accepted for processing']
            ], 202);
        else
            return $this->fail('method_not_allowed', "Method Not Allowed", [], 405);

    }
}
