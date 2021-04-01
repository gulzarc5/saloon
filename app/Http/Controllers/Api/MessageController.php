<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Message\MessgeResource;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function clientMesseges(Request $request)
    {
        $vendor_type = $request->user()->clientType;
        if ($vendor_type == '1'){
            $message = Message::where('vendor_type','F')
            ->orWhere(function($q){
                $q->whereNull('vendor_type')
                ->where('type','V');
            })
            ->paginate(20);
        } else{
            $message = Message::where('vendor_type','S')
            ->orWhere(function($q){
                $q->whereNull('vendor_type')
                ->where('type','V');
            })
            ->paginate(20);
        }

        $response = [
            'status' => true,
            'message' => 'Service List',
            'total_page' => $message->lastPage(),
            'current_page' =>$message->currentPage(),
            'total_data' =>$message->total(),
            'has_more_page' =>$message->hasMorePages(),
            'data' => MessgeResource::collection($message),
        ];
        return response()->json($response, 200);
    }
    public function customerMesseges(Request $request)
    {
        $message = Message::where('type','C')->paginate(20);

        $response = [
            'status' => true,
            'message' => 'Service List',
            'total_page' => $message->lastPage(),
            'current_page' =>$message->currentPage(),
            'total_data' =>$message->total(),
            'has_more_page' =>$message->hasMorePages(),
            'data' => MessgeResource::collection($message),
        ];
        return response()->json($response, 200);
    }
}
