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
use App\Services\WalletAmountService;
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
                'message' => 'Validation error',
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
            $message = "OTP is $random . Please do not share with anyone";
            Sms::smsSend($mobile,$message);
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
        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Validation Error',
                'data' => null,
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }
        $mobile = $request->input('mobile');
        $otp = $request->input('otp');

        $customer = Customer::where('mobile', $mobile)->where('otp', $otp);
        $check = $customer->count();
        if ($check > 0) {
            $customer_data = $customer->first();
            $response = [
                'status' => true,
                'message' => 'Success',
                'data' => $customer_data,
                'error_code' => false,
                'error_message' => null,
            ];            
            return response()->json($response, 200);
            
        } else {
            $response = [
                'status' => false,
                'message' => 'Sorry OTP is Invalid',
                'data' => null,
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }
    }

    public function updateDetailsRegistration(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'user_id' => 'required',
            'name' => 'required|string',
            'gender' => 'required',
            'email' => 'nullable|email',
        ]);
        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Validation Error',
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }
        $user_id = $request->input('user_id');
        $name = $request->input('name');
        $gender = $request->input('gender');
        $email = $request->input('email');

        $customer = Customer::find($user_id);
        $customer->name = $name;
        $customer->gender = $gender;
        $customer->email = $email;
        $customer->api_token = Str::random(60);
        $customer->is_registered = 2;
        if ($customer->save()) {
            $customer->save();          
            Wallet::firstOrCreate([
                'user_id' => $customer->id
            ]);
            $response = [
                'status' => false,
                'message' => 'Registration Details Updated Successfully!',
                'error_code' => false,
                'error_message' => null,
                'data' => $customer
            ];
            return response()->json($response, 200);
        }
    }

    public function updateAddressRegistration(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'user_id' => 'required',
            'state' => 'required|string',
            'city' => 'required',
            'address' => 'required',
            'pin' => 'required'
        ]);
        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Validation Error',
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }
        $user_id = $request->input('user_id');
        $customer = Customer::find($user_id);
        $customer->state = $request->input('state');
        $customer->city = $request->input('city');
        $customer->address = $request->input('address');
        $customer->pin = $request->input('pin');

        // Shipping Address Save
        $address = new Address();
        $address->user_id = $request->input('user_id');
        $address->name = $customer->name;
        $address->mobile = $customer->mobile;
        $address->state = $request->input('state');
        $address->city = $request->input('city');
        $address->pin = $request->input('pin');
        $address->address = $request->input('address');
        $address->latitude = $customer->latitude;
        $address->longitude = $customer->longitude;

        if ($customer->save() && $address->save()) {
            $response = [
                'status' => true,
                'message' => 'Address Updated Successfully'
            ];
            return response()->json($response, 200);
        }
    }
    public function profileFetch($user_id)
    {
        $customer = Customer::find($user_id);
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
            'user_id' => 'required',
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
                'message' => 'Validation Error',
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }

        $customer = Customer::find($request->input('user_id'));
        $customer->name = $request->input('name');
        $customer->state = $request->input('state');
        $customer->city = $request->input('city');
        $customer->address = $request->input('address');
        $customer->pin = $request->input('pin');
        $customer->gender = $request->input('gender');
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
                'message' => 'Validation Error',
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
                'message' => 'Validation Error',
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

    public function orderHistory(Request $request)
    {
        $user_id = $request->user()->id;
        $order = Order::where('customer_id', $user_id)->orderBy('id', 'desc')->paginate(10);
        $response = [
            'status' => true,
            'message' => 'Order history',
            'total_page' => $order->lastPage(),
            'current_page' =>$order->currentPage(),
            'total_data' =>$order->total(),
            'has_more_page' =>$order->hasMorePages(),
            'data' => CustomerOrderHistoryResource::collection($order),
        ];

        return response()->json($response, 200);
    }

    public function orderCancel(Request $request)
    {
        //is_refund 2 = yes, 1 = No
        $validator =  Validator::make($request->all(), [
            'order_id' => 'required',
            'is_refund' => 'required|in:1,2',
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Validation Error',
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }
        $order_id = $request->input('order_id');
        $is_refund = $request->input('is_refund');

        $order = Order::find($order_id);
        if ($order) {
            $order->order_status = 5;
            $order->save();
            if (($order->payment_status == 2) && ($order->advance_amount > 0) && ($is_refund=='2')) {
                WalletAmountService::walletCredit($order->customer_id,$order->advance_amount,"Order Cancel Amount Credited To Wallet");
            }elseif (($order->wallet_pay > 0) && ($is_refund=='2')) {
                WalletAmountService::walletCredit($order->customer_id,$order->wallet_pay,"Order Cancel Amount Credited To Wallet");
            }
            // Send push
            if (isset($order->client->firsbase_token) && !empty($order->client->firsbase_token)) {
                $title = "Dear Vendor : An order Cancelled By ".$order->client->name." With Order Id $order->id Please Check";
                PushHelperVendor::notification($order->client->firsbase_token, $title, $order->vendor_id, 2);
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

    public function orderVendorCancelAcceptReject(Request $request,$order_id,$status)
    {
        //status 1 = cancel, 2 = change vendor

        $order = Order::find($order_id);
        if ($status == 1) {
            if ($order) {
                $order->order_status = 5;
                $order->save();
                if (($order->payment_status == 2) && ($order->advance_amount > 0)) {
                    WalletAmountService::walletCredit($order->customer_id,$order->advance_amount,"Order Cancel Amount Credited To Wallet");
                }elseif (($order->wallet_pay > 0)) {
                    WalletAmountService::walletCredit($order->customer_id,$order->wallet_pay,"Order Cancel Amount Credited To Wallet");
                }
                // Send push
                $user = Client::find($order->vendor_id);
                if ($user->firsbase_token) {
                    $client_type = $user->clientType == '1' ? 2 : 3;
                    $title = "Dear Vendor : Your order is Cancelled By Customer With Order No : $order->id";
                    PushHelperVendor::notification($user->firsbase_token, $title, $user->id, $client_type);
                }
            }
            $response = [
                'status' => true,
                'message' => 'Order Cancelled And Paid Amount Credited To Wallet',
            ];
            return response()->json($response, 200);
        } else {
            if ($order) {
                $order->order_status = 6;
                $order->save();
            }
            $response = [
                'status' => true,
                'message' => 'Vendor Change Request Sent Successfully to admin',
            ];
            return response()->json($response, 200);
        }
        
    }
}
