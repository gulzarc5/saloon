<?php

namespace App\Http\Controllers\Admin\ServiceCategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    public function index(){
        return view('admin.service_category.index');
    }
}
