<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\SmsHelper\Sms;
use Validator;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function sendOtp($mobile){
        $otp = rand(111111,999999);

        $request_info = urldecode("Your OTP is $otp . Please Do Not Share This Otp To Any One. Thank you");
        Sms::smsSend($mobile,$request_info);
        $data = [
            'mobile' => $mobile,
            'otp' => $otp,
        ];
        $response = [
            'status' => true,
            'message' => 'OTP Send Successfully Please Verify',
            'data' => $data,
        ];
        return response()->json($response, 200);
    }

    public function customerRegistration(Request $request){
        $validator =  Validator::make($request->all(),[
	        'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'same:confirm_password'],
            'mobile' =>  ['required','digits:10','numeric','unique:customers'],
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Required Field Can not be Empty',
                'error_code' => true,
                'error_message' => $validator->errors(),

            ];
            return response()->json($response, 200);
        }

        $customer = new Customer();
        $customer->name = $request->input('name');
        $customer->mobile = $request->input('mobile');
        $customer->password = Hash::make($request->input('password'));
        if ($customer->save()) {
            $response = [
                'status' => true,
                'message' => 'Customer Registered Successfully',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }else{
        	$response = [
                'status' => false,
                'message' => 'Something Went Wrong Please Try Again',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }
    }
}
