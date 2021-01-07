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
use App\Http\Resources\CustomerResource;
use App\Models\Client;

use App\Models\Order;
use App\Http\Resources\CustomerOrderHistoryResource;
use App\Models\RefundInfo;
use App\Models\UserBankAccount;
use App\SmsHelper\PushHelper;

class CustomerController extends Controller
{
    public function signUpOtp($mobile,$user_type){
        // user_type  1 = Customer, 2 = Client

        if ($user_type == '1') {
            $check_user = Customer::where('mobile',$mobile)->count();
        } else {
            $check_user = Client::where('mobile',$mobile)->count();
        }

        if ($check_user > 0) {
            $response = [
                'status' => false,
                'message' => 'Sorry User Already Registered With Us',
                'otp' => null,
            ];
            return response()->json($response, 200);
        }


        $customer = SignUpOtp::firstOrCreate(['mobile' => $mobile,'user_type'=>$user_type]);
        $customer->otp =  11111;
        $customer->user_type =  $user_type;
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

    public function signUpOtpVerify($mobile,$otp,$user_type){
        $check = SignUpOtp::where('mobile',$mobile)->where('otp',$otp)->where('user_type',$user_type)->count();
        if($check > 0){
            SignUpOtp::where('mobile',$mobile)->where('user_type',$user_type)->delete();
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

    public function customerLogin(Request $request){
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

    public function profileFetch($id)
    {
        $customer = Customer::find($id);
        $response = [
            'status' => true,
            'message' => 'Customer Profile',
            'data' => new CustomerResource($customer),
        ];
        return response()->json($response, 200);
    }

    public function profileUpdate(Request $request,$id)
    {
        $validator =  Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required|numeric|digits:10|unique:customers,mobile,'.$id,
            'state' =>  'required',
            'city' =>  'required',
            'address' => 'required',
            'pin' =>  'required',
            'gender' =>  'required|in:M,F',
            'dob' =>  'required|date_format:"Y-m-d"'
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

        $customer = Customer::find($id);
        $customer->name = $request->input('name');
        $customer->mobile = $request->input('mobile');
        $customer->email = $request->input('email');
        $customer->state = $request->input('state');
        $customer->city = $request->input('city');
        $customer->address = $request->input('address');
        $customer->pin = $request->input('pin');
        $customer->gender = $request->input('gender');
        $customer->latitude = $request->input('latitude');
        $customer->longitude = $request->input('longitude');
        $customer->dob = $request->input('dob');
        if ($customer->save()) {
            $response = [
                'status' => true,
                'message' => 'Customer Data Updated Successfully',
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

    public function bankInfoInsert(Request $request,$user_id)
    {
        $validator =  Validator::make($request->all(), [
            'bank_name' => 'required|string',
            'ac_no' =>  'required',
            'ifsc' =>  'required',
            'branch_name' => 'required|string',
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
        $bankInfo = new UserBankAccount();
        $bankInfo->user_id = $user_id;
        $bankInfo->bank_name = $request->input('bank_name');
        $bankInfo->ac_no = $request->input('ac_no');
        $bankInfo->ifsc = $request->input('ifsc');
        $bankInfo->branch_name = $request->input('branch_name');
        $bankInfo->save();

        $response = [
            'status' => true,
            'message' => 'Bank Account Added Successfully',
            'error_code' => false,
            'error_message' => null,
        ];
        return response()->json($response, 200);
    }

    public function bankInfoList($user_id)
    {
        $bankAccountList = UserBankAccount::where('user_id', $user_id)->orderBy('id','desc')->get();
        $response = [
            'status' => true,
            'message' => 'Bank Account List',
            'data' => $bankAccountList,
        ];
        return response()->json($response, 200);
    }

    public function bankInfoFetch($bank_info_id)
    {
        $bankAccount = UserBankAccount::find($bank_info_id);
        $response = [
            'status' => true,
            'message' => 'Bank Account Data',
            'data' => $bankAccount,
        ];
        return response()->json($response, 200);
    }

    public function bankInfoUpdate(Request $request,$bank_info_id)
    {
        $validator =  Validator::make($request->all(), [
            'bank_name' => 'required|string',
            'ac_no' =>  'required',
            'ifsc' =>  'required',
            'branch_name' => 'required|string',
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

        $bankAccount = UserBankAccount::find($bank_info_id);
        if ($bankAccount) {
            $bankAccount->bank_name = $request->input('bank_name');
            $bankAccount->ac_no = $request->input('ac_no');
            $bankAccount->ifsc = $request->input('ifsc');
            $bankAccount->branch_name = $request->input('branch_name');
            $bankAccount->save();
        }
        $response = [
            'status' => true,
            'message' => 'Bank Account Updated Successfully',
            'error_code' => false,
            'error_message' => null,
        ];
        return response()->json($response, 200);
    }

    public function passwordChange(Request $request,$id)
    {
        $validator =  Validator::make($request->all(),[
            'current_pass' => ['required', 'string', 'min:8'],
            'new_password' => ['required', 'string', 'min:8', 'same:confirm_password'],
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

        $user =Customer::find($id);
        if ($user) {
            if(Hash::check($request->input('current_pass'), $user->password)){
                $user->password = Hash::make($request->input('confirm_password'));
                if ($user->save()) {
                    $response = [
                        'status' => true,
                        'message' => 'Password Changed Successfully',
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
            }else{
                $response = [
                    'status' => false,
                    'message' => 'Please Enter Correct Corrent Password',
                    'error_code' => false,
                    'error_message' => null,
                ];
                return response()->json($response, 200);
           }
        } else {
            $response = [
                'status' => false,
                'message' => 'User Not Found Please Try Again',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }
    }

    public function forgotOtp($mobile)
    {
        $customer = Customer::where('mobile',$mobile);
        if ($customer->count() == 0) {
            $response = [
                'status' => false,
                'message' => 'Sorry User Does Not Exist'
            ];
            return response()->json($response, 200);
        }

        $customer = $customer->first();
        $customer->otp = rand(11111,99999);
        if ($customer->save()) {
            $message = "OTP is $customer->otp . Please Do Not Share With Anyone";
            // Sms::smsSend($customer->mobile,$message);
            $response = [
                'status' => true,
                'message' => 'OTP Sent Successfully To Registered Mobile Number',
                'otp' => $customer->otp,
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong Please Try Again'
            ];
            return response()->json($response, 200);
        }
    }

    public function forgotPasswordChange(Request $request){
        $validator =  Validator::make($request->all(),[
            'mobile' => ['required', 'numeric', 'digits:10'],
            'otp' => ['required', 'numeric', 'digits:5'],
            'password' => ['required', 'string', 'min:8', 'same:confirm_password'],
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
        $mobile = $request->input('mobile');
        $otp = $request->input('otp');
        $customer = Customer::where('mobile',$mobile)->where('otp',$otp);
        if($customer->count() > 0){
            $customer = $customer->first();
            $customer->password =  Hash::make($request->input('confirm_password'));
            $customer->otp = null;
            $customer->save();
            $response = [
                'status' => true,
                'message' => 'Password Changed Successfully'
            ];
            return response()->json($response, 200);
        }else {
            $response = [
                'status' => false,
                'message' => 'Sorry OTP is Invalid'
            ];
            return response()->json($response, 200);
        }
    }

    public function orderHistory($user_id)
    {
        $order = Order::where('customer_id', $user_id)->orderBy('id', 'desc')->limit(50)->get();
        $response = [
            'status' => true,
            'message' => 'Order history',
            'data' => CustomerOrderHistoryResource::collection($order),
        ];

        return response()->json($response, 200);
    }

    public function orderCancel(Request $request)
    {
        $messages = [
            'required_if' => 'Please Select Account',
        ];
        //is_refund 2 = yes, 1 = No
        $validator =  Validator::make($request->all(),[
            'order_id' => 'required',
            'is_refund' => 'required',
            'account_id' =>  'required_if:is_refund,2'
        ],$messages);
       
        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Required Field Can not be Empty',
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }
        $order_id = $request->input('order_id');
        $is_refund = $request->input('is_refund');
        $account_id = $request->input('account_id');

        $order = Order::find($order_id);
        if ($order) {
            $order->order_status = 5;
            $order->save();
            if ($is_refund == 2) {
                $refund = new RefundInfo();
                $refund->order_id = $order->id;
                $refund->account_id = $account_id;
                $refund->amount = $order->advance_amount;
                if ($refund->save()) {
                    $order->refund_request = 2;
                    $order->save();
                }
            }
        // Send push
        $user = Client::find($order->vendor_id);
        if ($user->firsbase_token) {
            $client_type = $user->clientType == '1' ? 2 : 3;                   
            $title = "Dear Customer : Your order is Cancelled With Order No : $order->id";
            
            PushHelper::notification($user->firsbase_token,$title,$user->id,$client_type);
        }

        }
        $response = [
            'status' => true,
            'message' => 'Order Cancelled Successfully',
            'error_code' => false,
            'error_message' => null,
        ];
        return response()->json($response, 200);

    }

    public function updateFirebaseToken($id,$token)
    {
        $customer = Customer::find($id);
        if ($customer) {
            $customer->firsbase_token = $token;
            $customer->save();
            $response = [
                'status' => true,
                'message' => 'Firebase Token Updated Successfully',
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'message' => 'User Not Found',
            ];
            return response()->json($response, 200);
        }
    }
}
