<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SubscriptionPlansController extends Controller
{

    public function index(Request $request)
    {

        dd('index');
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
