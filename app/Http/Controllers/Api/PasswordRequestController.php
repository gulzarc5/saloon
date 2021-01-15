<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Customer;
use App\Models\PasswordRequest;
use Illuminate\Http\Request;
use Validator;
use Hash;

class PasswordRequestController extends Controller
{
    public function passwordRequest(Request $request)
    {
        // service type 1 = man, 2 = woman, 3= kids
        $validator =  Validator::make($request->all(),[
            'mobile_number' =>'required|numeric|digits:10',
            'user_type' =>'required|numeric|in:1,2',
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Required data Can not Be Empty',
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }

        $mobile = $request->input('mobile_number');
        $user_type = $request->input('user_type');
        $password_user_type = $user_type;
        if ($user_type == '1') {
            $user = Customer::where('mobile',$mobile)->first();
            $password_user_type = 1;
        } else {
            $user = Client::where('mobile',$mobile)->first();
            if ($user) {
                $password_user_type = $user->clientType == '1' ? 2 : 3;
            }
        }
        
        if ($user) {
            $password_request = new PasswordRequest();
            $password_request->user_id = $user->id;
            $password_request->user_type = $password_user_type;
            $password_request->save();

            $response = [
                'status' => true,
                'message' => 'Password Request Sent Successfully. We Will get back to you soon',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);

        } else {
            $response = [
                'status' => false,
                'message' => 'Sorry User Not Found',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }        
    }
}
