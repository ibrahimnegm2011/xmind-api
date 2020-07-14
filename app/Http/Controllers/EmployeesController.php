<?php
/**
 * Created by PhpStorm.
 * User: AbdElRahman Negm
 * Date: 6/24/2020
 * Time: 9:33 PM
 */

namespace App\Http\Controllers;


use App\Model\Employee;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;


class EmployeesController extends Controller
{


    private function filterList($query, $data)
    {
        $query = $this->filterStrings($query, ['name', 'email' , 'phone'], $data);
        if(isset($data['active'])){
            $query->whereHas('user', function (Builder $query) use ($data) {
                $query->where('active', $data['active'] );
            })->get();
        }


        return $query;
    }

    public function index(Request $request)
    {
        $data = $request->all();

        $validator = $this->adminValidator([

            'pagination' => 'numeric',
            'name' => 'string|max:100',
            'email' => 'string|max:100',
            'phone' => 'numeric',
            'active' => 'boolean',
        ], $data);

        if ($validator !== true) {
            return $validator;
        }

        $pagination = $request->pagination;
        if (is_null($request->pagination))
            $pagination = $this->pagination;


        $user = Auth::user()->getUserType();
        $account_id = Auth::user()->loggable->getAccountId();

        if ($user == 'account') {
            $query = Employee::where('account_id', $account_id)->with('user');

            $query = $this->filterList($query, $request->all());
            return $this->success($query->paginate($pagination));

        } else {

            return $this->fail('not_found', "Not Found", [], 404);

        }
    }

    public function create(Request $request)
    {
        $account_id = Auth::user()->loggable->getAccountId();
        $account = Auth::user()->loggable->getAccount();
        $employeeCount = Employee::where('account_id', $account_id)->count();
        $plan = $account->plan->employees;
        if ($employeeCount == $plan) {

            return $this->fail('Miss Match', "Not matching with your Plan", [], 404);

        } else {

            $data = $request->all();
            $data['account_id'] = $account->id;

            $validator = $this->adminValidator([
                'active' => 'boolean|required',
                'username' => 'required|string|max:100',
                'password' => 'required|string|max:100',
                'name' => 'required|string|max:100',
                'email' => [
                    'required',
                    'string',
                    'max:100',
                    'email',
                    Rule::unique('employees')->where(function ($query) use ($account_id) {
                        return $query->where('account_id', $account_id);
                    })
                ],
                'phone' => 'required|numeric',
                'address' => 'required|string|max:100',
                'salary' => 'required|numeric',
                'join_date' => 'required|date|date_format:Y-m-d',
            ], $data);
            if ($validator !== true) {
                return $validator;
            }

            $employee = Employee::create($data);

            $user = new User();
            $user->username = $data['username'];
            $user->active = $data['active'];
            $user->password = Hash::make($data['password']);
            $employee->user()->save($user);
            return $this->success($data);
        }
    }

    public function show($id)
    {

        $account_id = Auth::user()->loggable->getAccountId();

        $employees = Employee::where('account_id', $account_id)->with('user')->find($id);

        if (!isset($employees)) {
            return $this->fail('not_found', "Not Found", [], 404);
        } else {
            return $this->success($employees);
        }
    }

    public function update($id, Request $request)
    {
        $account_id = Auth::user()->loggable->getAccountId();
        $data = $request->all();

        $validator = validator($data, $roles = [
            'active' => 'boolean|required',
            'username' => 'required|string|max:100',
            'password' => 'required|string|max:100',
            'name' => 'required|string|max:100',
            'email' => [
                'required',
                'string',
                'max:100',
                'email',
                Rule::unique('employees')->ignore($id)->where(function ($query) use ($account_id) {
                    return $query->where('account_id', $account_id);
                })
            ],
            'phone' => 'required|numeric',
            'address' => 'required|string|max:100',
            'salary' => 'required|numeric',
            'join_date' => 'required|date|date_format:Y-m-d',
        ]);

        if ($validator->fails())
            return $this->validationFail($validator->errors()->toArray());


        $employee = Employee::where('account_id', $account_id)
            ->where('id', $id)
            ->with('user')->first();

        if (!$employee) {
            return $this->fail('Invalid', "Invalid Data", [], 404);
        }

        $employee->user->update([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'active' => $data['active']
        ]);

        $res = $employee->update($data);

        if ($res) {
            return $this->success($employee);
        } else {

            return $this->fail('update_fail', "Invalid Data", [], 404);

        }
    }

    public function delete($id)
    {
        $account_id = Auth::user()->loggable->getAccountId();
        $delete = Employee::with('user')->find($id);
        $delete->user->delete();
        $action = $delete->where('id', $id)->where('account_id', $account_id)->delete();
        if ($action) {
            return $this->success([
                'status' => 'success',
                "response" => ["action" => 'Deleted']
            ], 202);
        } else {
            return $this->fail('Invalid', "Not Deleted", [], 404);
        }
    }
}