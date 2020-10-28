<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\SmsHelper\Sms;
use Validator;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use App\Models\SignUpOtp;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function signUpOtp($mobile){
        $customer = Customer::where('mobile',$mobile);
        if ($customer->count() > 0) {
            $response = [
                'status' => true,
                'message' => 'Sorry Mobile Number Already Registered With Us'
            ];
            return response()->json($response, 200);
        }

        $customer = SignUpOtp::firstOrCreate(['mobile' => $mobile]);
        $customer->otp =  rand(11111,99999);
        if($customer->save()) {
            $message = "OTP is $customer->otp . Please Do Not Share With Anyone";
            // Sms::smsSend($customer->mobile,$message);
            $response = [
                'status' => true,
                'message' => 'Otp Sent to Your Mobile Number',
                'otp' => $customer->otp,
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => true,
                'message' => 'Sorry Mobile Number Already Registered With Us'
            ];
            return response()->json($response, 200);
        }
    }

    public function signUpOtpVerify($mobile,$otp){
        $check = SignUpOtp::where('mobile',$mobile)->where('otp',$otp)->count();
        if($check > 0){
            SignUpOtp::where('mobile',$mobile)->delete();
            $response = [
                'status' => true,
                'message' => 'OTP Verified Successfully'
            ];
            return response()->json($response, 200);
        }else {
            $response = [
                'status' => true,
                'message' => 'Sorry OTP is Invalid'
            ];
            return response()->json($response, 200);
        }
    }


    public function customerRegistration(Request $request){
        $validator =  Validator::make($request->all(),[
	        'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'same:confirm_password'],
            'mobile' =>  ['required','digits:10','numeric','unique:customers'],
            'email' =>  'unique:customers',
            'state' =>  'required',
            'city' =>  'required',
            'gender' =>  'required|in:M,F',
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
        $customer->email = $request->input('email');
        $customer->state = $request->input('state');
        $customer->city = $request->input('city');
        $customer->address = $request->input('address');
        $customer->pin = $request->input('pin');
        $customer->gender = $request->input('gender');
        $customer->latitude = $request->input('latitude');
        $customer->longitude = $request->input('longitude');
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

    public function customerLogin(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'mobile' => 'required|numeric|digits:10',
            'password' => 'required|min:8',
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

        $customer = Customer::where('mobile',$request->input('mobile'))->first();
        if ($customer) {
            if(Hash::check($request->input('password'), $customer->password)){
                $customer->api_token = Str::random(60);
                $customer->save();
                $response = [
                    'status' => true,
                    'message' => 'User Successfully Logged In',
                    'error_code' => false,
                    'error_message' => null,
                    'data' => $customer,
                ];
                return response()->json($response, 200);
            }else {
                $response = [
                    'status' => false,
                    'message' => 'Sorry !! User Id Or Password Wrong',
                    'error_code' => false,
                    'error_message' => null,
                ];
                return response()->json($response, 200);
            }
        } else {
            $response = [
                'status' => false,
                'message' => 'Sorry !! User Id Or Password Wrong',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }
    }
}
