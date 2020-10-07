<?php

namespace App\Http\Controllers\Admin\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function state(){
        return view('admin.configuration.state');
    }

    public function city(){
        return view('admin.configuration.city');
    }
}
