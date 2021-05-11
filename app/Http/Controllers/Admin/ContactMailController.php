<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMail;
use Illuminate\Http\Request;
use DataTables;

class ContactMailController extends Controller
{
    public function index()
    {
        return view('admin.contact_mail.contact_mail_list');
    }

    public function indexAjax()
    {
        $model = ContactMail::with('category','subCategory','thirdCategory')->latest();
        return DataTables::eloquent($model)
            ->toJson();
    }
}
