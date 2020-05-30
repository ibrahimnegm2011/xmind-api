<?php

namespace App\Http\Controllers;

use App\Model\FoodStuff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FoodsController extends Controller
{

    private function filterList($query, $data)
    {
        $query = $this->filterStrings($query, ['name', 'type'], $data);

        if(isset($data['active']) && !is_null($data['active'])){
            $query->where('active', $data['active']);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $check = $this->adminValidator([
            'pagination' => 'numeric',
            'name' => 'max:255',
            'price' => 'numeric|max:255',
            'active' => 'boolean',
        ], $request->all());

        if ($check !== true)
            return $check;

        $pagination = $request->pagination;
        if (is_null($request->pagination))
            $pagination = $this->pagination;

        $query = FoodStuff::select('id', 'account_id', 'name', 'price', 'active');

        $query = $this->filterList($query, $request->all());

        return $this->success($query->paginate($pagination));
    }

    public function show($id)
    {
        $data = ['id' => $id];
        $data['account_id'] = Auth::user()->loggable->getAccountId();

        $check = $this->adminValidator([
            'id' => 'required|integer|exists:food_stuff,id',
            'account_id' => 'required|integer|exists:accounts,id',
        ], $data);

        if ($check !== true)
            return $check;

        $food = FoodStuff::find($id);

        if(!$food){
            return $this->fail('not_found', "Not Found", [], 404);
        }else{
            return $this->success($food);
        }
    }

    public function create(Request $request)
    {
        //check package validation
        $account = Auth::user()->loggable->getAccount();
        $foodsCount = FoodStuff::where('account_id', $account->id)->count();
        if($account->plan->food_stuff != 0 && $account->plan->food_stuff <= $foodsCount){
            return $this->fail('package_exceeded', "Package Exceeded", [], 402);
        }

        $data = $request->all();
        $data['account_id'] = $account->id;

        $check = $this->adminValidator([
            'account_id' => 'required|integer|exists:accounts,id',
            'name' => 'required|string|max:100',
            'price' => 'required|numeric',
            'active' => 'required|boolean',
        ], $data);

        if ($check !== true)
            return $check;

        $food = new FoodStuff();
        $food->fill($data);
        $food->save();

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
            'id' => 'required|integer|exists:food_stuff,id',
            'account_id' => 'required|integer|exists:accounts,id',
            'name' => 'required|string|max:100',
            'price' => 'required|numeric',
            'active' => 'required|boolean'
        ], $data);

        if ($check !== true)
            return $check;

        unset($data['id']);
        unset($data['account_id']);

        $food = FoodStuff::find($id);
        $food->update($data);

        return $this->success([
            'status' => 'success',
            "response" => ["action" => 'The request has been accepted for processing']
        ], 202);
    }

    public function delete($id, Request $request)
    {
        $check = $this->adminValidator([
            'id' => 'required|integer|exists:food_stuff,id'
        ], ['id' => $id]);

        if ($check !== true)
            return $check;

        $delete = FoodStuff::where('id', $id)->delete();

        if($delete)
            return $this->success([
                'status' => 'success',
                "response" => ["action" => 'The request has been accepted for processing']
            ], 202);
        else
            return $this->fail('method_not_allowed', "Method Not Allowed", [], 405);

    }
}
