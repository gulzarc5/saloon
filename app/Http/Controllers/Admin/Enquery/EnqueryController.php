<?php

namespace App\Http\Controllers\Admin\Enquery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EnqueryController extends Controller
{
    public function feedback()
    {
        return view('admin.enquery.feedback');
    }

    public function enquery()
    {
        return view('admin.enquery.enquery');
    }
}
