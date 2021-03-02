<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\SmsHelper\Sms;
use App\Models\Customer;
use Illuminate\Support\Str;
use App\Http\Resources\CustomerResource;
use App\Models\Client;

use App\Models\Order;
use App\Http\Resources\CustomerOrderHistoryResource;
use App\Models\Address;
use App\Models\RefundInfo;
use App\Models\UserBankAccount;
use App\Models\Wallet;
use App\SmsHelper\PushHelperVendor;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

    public function sendOtp(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'mobile' =>  ['required', 'digits:10', 'numeric'],
        ]);
        $mobile = $request->input('mobile');
        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Required Field Can not be Empty',
                'error_code' => true,
                'error_message' => $validator->errors(),

            ];
            return response()->json($response, 200);
        }
        $random = mt_rand(100000, 999999);
        $random = 11111;
        // $otp_code = $this->otp($ot, $request->input('mobile'));
        $user = Customer::firstOrCreate([
            'mobile' => $request->input('mobile')
        ]);
        if ($user) {
            $user->otp = $random;
            $user->save();
            $response = [
                'status' => true,
                'message' => 'Otp Sent Successfully',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }
    }

    public function customerOtpVerify(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'mobile' => 'required|digits:10|numeric',
            'otp' => 'required|numeric|digits:5'
        ]);
        $mobile = $request->input('mobile');
        $otp = $request->input('otp');
        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Required Field Can not be Empty',
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }

        $customer = Customer::where('mobile', $mobile)->where('otp', $otp);
        $check = $customer->count();
        if ($check > 0) {
            $customer_check = $customer->where('is_registered', 2)->count();
            if ($customer_check > 0) {
                $response = $this->otpVerifyData($mobile, $otp, 2);
                return response()->json($response, 200);
            } else {
                $response = $this->otpVerifyData($mobile, $otp, 1);
                return response()->json($response, 200);
            }
        } else {
            $response = [
                'status' => true,
                'message' => 'Sorry OTP is Invalid'
            ];
            return response()->json($response, 200);
        }
    }

    public function updateDetailsRegistration(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'name' => 'required|string',
            'gender' => 'required',
        ]);
        $name = $request->input('name');
        $gender = $request->input('gender');
        $lat = $request->input('lat');
        $long = $request->input('long');

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Required Field Can not be Empty',
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }
        $customer = $request->user();
        $customer->name = $name;
        $customer->gender = $gender;
        $customer->latitude = $lat;
        $customer->longitude = $long;
        if ($customer->save()) {
            $response = [
                'status' => true,
                'message' => 'Registration Details Updated Successfully!',
            ];
            return response()->json($response, 200);
        }
    }

    public function updateAddressRegistration(Request $request)
    {
        $customer = $request->user();
        $customer->state = $request->input('state');
        $customer->city = $request->input('city');
        $customer->address = $request->input('address');
        $customer->pin = $request->input('pin');

        // Shipping Address Save
        $address = new Address();
        $address->user_id = $request->user()->id;
        $address->name = $request->user()->name;
        $address->mobile = $request->user()->mobile;
        $address->state = $request->input('state');
        $address->city = $request->input('city');
        $address->pin = $request->input('pin');
        $address->address = $request->input('address');
        $address->latitude = $request->user()->latitude;
        $address->longitude = $request->user()->longitude;

        if ($customer->save() && $address->save()) {
            $response = [
                'status' => true,
                'message' => 'Address Updated Successfully'
            ];
            return response()->json($response, 200);
        }
    }
    public function profileFetch(Request $request)
    {

        $customer = $request->user();
        $response = [
            'status' => true,
            'message' => 'Customer Profile',
            'data' => new CustomerResource($customer),
        ];
        return response()->json($response, 200);
    }

    public function profileUpdate(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'name' => 'required',
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

        $customer = $request->user();
        $customer->name = $request->input('name');
        $customer->mobile = $request->input('mobile');
        $customer->email = $request->input('email');
        $customer->state = $request->input('state');
        $customer->city = $request->input('city');
        $customer->address = $request->input('address');
        $customer->pin = $request->input('pin');
        $customer->gender = $request->input('gender');
        $customer->latitude = $request->input('lat');
        $customer->longitude = $request->input('long');
        $customer->dob = $request->input('dob');
        if ($customer->save()) {
            $response = [
                'status' => true,
                'message' => 'Customer Data Updated Successfully',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong Please Try Again',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }
    }

    public function bankInfoInsert(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'bank_name' => 'required|string',
            'ac_no' =>  'required|numeric',
            'ifsc' =>  'required|regex:/^[A-Za-z]{4}\d{7}$/',
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
        $bankInfo->user_id = $request->user()->id;
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

    public function bankInfoList(Request $request)
    {
        $bankAccountList = UserBankAccount::where('user_id', $request->user()->id)->latest()->get();
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

    public function bankInfoUpdate(Request $request, $bank_info_id)
    {
        $validator =  Validator::make($request->all(), [
            'bank_name' => 'required|string',
            'ac_no' =>  'required|numeric',
            'ifsc' =>  'required|regex:/^[A-Za-z]{4}\d{7}$/',
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
        $validator =  Validator::make($request->all(), [
            'order_id' => 'required',
            'is_refund' => 'required',
            'account_id' =>  'required_if:is_refund,2'
        ], $messages);

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
                $title = "Dear Vendor : Your order is Cancelled With Order No : $order->id";

                PushHelperVendor::notification($user->firsbase_token, $title, $user->id, $client_type);
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

    public function updateFirebaseToken($id, $token)
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
        } else {
            $response = [
                'status' => false,
                'message' => 'User Not Found',
            ];
            return response()->json($response, 200);
        }
    }

    private function otpVerifyData($mobile, $otp, $type)
    {
        $customer = Customer::where('mobile', $mobile)->where('otp', $otp)->where('is_registered', $type)->first();
        $customer->mobile = $mobile;
        $customer->otp = $otp;
        if ($type == 2) {
            $customer->is_registered = 1;
        }
        $customer->api_token = Str::random(60);
        $customer->save();
        // Wallet
        $wallet = Wallet::firstOrCreate([
            'user_id' => $customer->id
        ]);

        $register_update_details = null;
        $address_details = null;
        if (!empty($customer->name && $customer->gender && $customer->latitude && $customer->longitude)) {
            $register_update_details = false;
        } else {
            $register_update_details = true;
        }
        if (!empty($customer->state && $customer->city && $customer->address && $customer->pin)) {
            $address_details = false;
        } else {
            $address_details = true;
        }
        if ($type == 2) {
            $response = [
                'status' => true,
                'customer' => $customer,
                'register_update_details' => $register_update_details,
                'address_details' => $address_details,
                'message' => 'Customer Registered Successfully!'
            ];
            return $response;
        } else {
            $response = [
                'status' => true,
                'customer' => $customer,
                'register_update_details' => $register_update_details,
                'address_details' => $address_details,
                'message' => 'Customer Logged In Successfully!'
            ];
            return $response;
        }
    }
}
