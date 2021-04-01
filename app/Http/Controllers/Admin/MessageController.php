<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Message\MessageAddRequest;
use App\Models\Message;
use App\SmsHelper\MessagePush;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function list()
    {
        return view('admin.message.message_list');
    }
    public function listAjax(Request $request)
    {
        return datatables()->of(Message::orderBy('id','desc')->get())
        ->addIndexColumn()
        ->make(true);
    }

    public function sendForm()
    {
        return view('admin.message.send_message_form');
    }

    public function sendMessage(MessageAddRequest $request)
    {
        $message = new Message();
        $message->title = $request->input('title');
        $message->message = $request->input('message');
        $message->type = $request->input('user_type');
        $message->vendor_type = $request->input('vendor_type');
        if ($message->save()) {
            if ($message->vendor_type) {
                MessagePush::notification($message->title, $message->message,$message->type,$message->vendor_type);
            } else {
                MessagePush::notification($message->title, $message->message,$message->type);
            }
            
        }
        return back()->with('message','Push Message Sent Successfully');
    }
}
