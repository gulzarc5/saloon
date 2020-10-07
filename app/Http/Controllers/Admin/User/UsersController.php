<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function freelancer(){
        return view('admin.users.freelancer');
    }

    public function shop(){
        return view('admin.users.shop');
    }

    public function customer(){
        return view('admin.users.customer');
    }
}
