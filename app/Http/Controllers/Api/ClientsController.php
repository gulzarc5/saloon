<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\SignUpOtp;
use App\Models\ClientImage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use File;
use Image;
use App\SmsHelper\Sms;

use App\Models\Order;
use App\Http\Resources\Order\ClientOrderHistoryResource;
use App\Models\ClientSchedule;
use App\Models\RefundInfo;
use App\Models\UserBankAccount;

class ClientsController extends Controller
{
    public function clientRegistration(Request $request){
        $validator =  Validator::make($request->all(),[
	        'name'             => ['required', 'string', 'max:255'],
            'mobile'           => ['required','digits:10','numeric','unique:clients'],
            'password'         => ['required', 'string', 'min:8'],
            'otp'              => 'required|digits:5|numeric',
            'clientType'       => 'required|in:1,2',
            'service_city_id' => 'required',
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

        $otp = $request->input('otp');
        $mobile = $request->input('mobile');
        $check_otp = SignUpOtp::where('otp', $otp)->where('mobile',$mobile)->where('user_type',2)->count();
        if ($check_otp == 0) {
            $response = [
                'status' => false,
                'message' => 'Sorry OTP Does Not Matched',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }

        $client = new Client;
        $client->name = $request->input('name');
        $client->mobile = $request->input('mobile');
        $client->password = Hash::make($request->input('password'));
        $client->clientType = $request->input('clientType');
        $client->service_city_id = $request->input('service_city_id');
        if ($client->save()) {
            $response = [
                'status' => true,
                'message' => 'Client Registered Successfully',
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

    public function clientLogin(Request $request){
        $validator =  Validator::make($request->all(),[
            'mobile'           => ['required','digits:10','numeric'],
            'password'         => ['required', 'string', 'min:8'],
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
        $client = Client::where('mobile',$request->input('mobile'))->first();
        if ($client) {
            if(Hash::check($request->input('password'), $client->password)){
                $client->api_token = Str::random(60);
                $client->save();
                $response = [
                    'status' => true,
                    'message' => 'Client Successfully Logged In',
                    'error_code' => false,
                    'error_message' => null,
                    'data' => new ClientResource($client),
                ];
                return response()->json($response, 200);
            }else {
                $response = [
                    'status' => false,
                    'message' => 'Sorry !! Client Id Or Password Wrong',
                    'error_code' => false,
                    'error_message' => null,
                ];
                return response()->json($response, 200);
            }
        } else {
            $response = [
                'status' => false,
                'message' => 'Sorry !! Client Id Or Password Wrong',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }
    }

    public function clientProfile($id){
        $client = Client::find($id);
        $response = [
            'status' => true,
            'message' => 'Client Profile',
            'data' => new ClientResource($client),
        ];
        return response()->json($response, 200);
    }

    public function clientProfileUpdate(Request $request)
    {
        $client_id = $request->input('client_id');
        $validator =  Validator::make($request->all(),[
            'client_id' => 'required',
            'name' => 'required',
            'mobile' =>  'required|unique:clients,id,'.$client_id,
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'address_proof_file' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_proof_file' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'business_proof_file' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'state' => 'required',
            'city' =>  'required',
            'address' => 'required',
            'pin' =>  'required',
            'service_city_id' =>  'required',
            'work_experience' => 'required',
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
        $client = Client::find($client_id);
        $client->name = $request->input('name');
        $client->mobile = $request->input('mobile');
        $client->email = $request->input('email');
        $client->work_experience = $request->input('work_experience');
        $client->state = $request->input('state');
        $client->city = $request->input('city');
        $client->service_city_id = $request->input('service_city_id');
        $client->address = $request->input('address');
        $client->pin = $request->input('pin');
        $client->gst = $request->input('gst');
        $client->latitude = $request->input('latitude');
        $client->longitude = $request->input('longitude');
        $client->opening_time = $request->input('opening_time');
        $client->closing_time = $request->input('closing_time');
        $client->description = $request->input('description');
        $client->profile_status = 2;
        if($client->save()) {
            if($request->hasfile('profile_image')){
                $path = public_path().'/images/client/thumb/';
                File::exists($path) or File::makeDirectory($path, 0777, true, true);
                $path_thumb = public_path().'/images/client/';
                File::exists($path_thumb) or File::makeDirectory($path_thumb, 0777, true, true);

   
                $image = $request->file('profile_image');
                $image_name = time().date('Y-M-d').'.'.$image->getClientOriginalExtension();

                //Product Original Image
                $destination = public_path().'/images/client/';
                $img = Image::make($image->getRealPath());
                $img->save($destination.'/'.$image_name);

                //Product Thumbnail
                $destination = public_path().'/images/client/thumb';
                $img = Image::make($image->getRealPath());
                $img->resize(600, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destination.'/'.$image_name);
                $client->image = $image_name;
                $client->save();
            }

            if($request->hasfile('photo_proof_file')){   
                $photo_proof = $request->file('photo_proof_file');
                $photo_proof_name = time().date('Y-M-d').'.'.$photo_proof->getClientOriginalExtension();

                //Product Original Image
                $photo_proof->move(public_path('/images/client/files/'), $photo_proof_name);

                $client->photo_proof_file = $photo_proof_name;
                $client->address_proof = $request->input('address_proof');
                $client->save();
            }
            if($request->hasfile('address_proof_file')){   
                $address_proof = $request->file('address_proof_file');
                $address_proof_name = time().date('Y-M-d').'.'.$address_proof->getClientOriginalExtension();

                //Product Original Image
                $address_proof->move(public_path('/images/client/files/'), $address_proof_name);

                $client->address_proof_file = $address_proof_name;
                $client->photo_proof = $request->input('photo_proof');
                $client->save();
            }
            if($request->hasfile('business_proof_file')){   
                $business_proof = $request->file('business_proof_file');
                $business_proof_name = time().date('Y-M-d').'.'.$business_proof->getClientOriginalExtension();

                //Product Original Image
                $business_proof->move(public_path('/images/client/files/'), $business_proof_name);

                $client->business_proof_file = $business_proof_name;
                $client->business_proof = $request->input('business_proof');
                $client->save();
            }

            $response = [
                'status' => true,
                'message' => 'Client Data Updated Successfully',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'status' => false,
                'message' => 'Sorry Something Went Wrong Please Try Again',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }

    }

    public function galleryImageAdd(Request $request)
    {
        $client_id = $request->input('client_id');
        $image_count = ClientImage::where('client_id',$client_id)->count();
        $image_max = (12 - $image_count);
        if ($image_max == 0) {
            $messages = [
                'max' => 'Sorry Maximum :attribute Upload Limit Exceeded.',
            ];
        } else {
            $messages = [
                'max' => 'More '.$image_max.' :attribute Can Be Allowed To Upload.',
            ];
        }


        $validator =  Validator::make($request->all(),[
            'images' => 'required|max:'.$image_max,
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'client_id' => 'required'
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

        if($request->hasfile('images')){
            $path = public_path().'/images/client/thumb/';
            File::exists($path) or File::makeDirectory($path, 0777, true, true);
            $path_thumb = public_path().'/images/client/';
            File::exists($path_thumb) or File::makeDirectory($path_thumb, 0777, true, true);

            for ($i=0; $i < count($request->file('images')); $i++) {
                $image = $request->file('images')[$i];
                $image_name = $i.time().date('Y-M-d').'.'.$image->getClientOriginalExtension();

                //Product Original Image
                $destination = public_path().'/images/client/';
                $img = Image::make($image->getRealPath());
                $img->save($destination.'/'.$image_name);

                //Product Thumbnail
                $destination = public_path().'/images/client/thumb';
                $img = Image::make($image->getRealPath());
                $img->resize(600, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destination.'/'.$image_name);

                $client_images = new ClientImage();
                $client_images->image = $image_name;
                $client_images->client_id = $client_id;
                $client_images->save();
            }
        }
        $response = [
            'status' => true,
            'message' => 'Client Data Updated Successfully',
            'error_code' => false,
            'error_message' => null,
        ];
        return response()->json($response, 200);
    }

    public function galleryImageDelete($client_id,$image_id)
    {
        $images = ClientImage::find($image_id);
        $image_name = $images->image;

        $profile_picture_check = Client::where(['id'=>$client_id,'image'=>$image_name])->count();
        if ($profile_picture_check > 0) {
            $response = [
                'status' => false,
                'message' => 'Sorry !! This Image Can Not Be Deleted',
            ];
            return response()->json($response, 200);
        }

        $path = public_path().'/images/client/'.$image_name;
        $thumb_path = public_path().'/images/client/thumb/'.$image_name;

        if ( File::exists($path)) {
            File::delete($path);
        }

        if ( File::exists($thumb_path)) {
            File::delete($thumb_path);
        }

        $images->delete();
        $response = [
            'status' => true,
            'message' => 'Image Deleted Successfully',
        ];
        return response()->json($response, 200);
    }

    public function galleryImageSetThumb($client_id, $image_id)
    {
        $image = ClientImage::where('id',$image_id)->first();
        $client = Client::find($client_id);
        $client->image = $image->image;
        $client->save();
        $response = [
            'status' => true,
            'message' => 'Image Set As Profile Picture',
        ];
        return response()->json($response, 200);
    }

    public function clientChangePassword(Request $request,$client_id)
    {
        $validator =  Validator::make($request->all(),[
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8',
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

        $client = Client::find($client_id);

        if ($client) {
            if(Hash::check($request->input('current_password'), $client->password)){
                $client->password = Hash::make($request->input('new_password'));
                if ($client->save()) {
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
        $client = Client::where('mobile',$mobile);
        if ($client->count() == 0) {
            $response = [
                'status' => false,
                'message' => 'Sorry User Does Not Exist'
            ];
            return response()->json($response, 200);
        }

        $client = $client->first();
        $client->otp = rand(11111,99999);
        if ($client->save()) {
            $message = "OTP is $client->otp . Please Do Not Share With Anyone";
            // Sms::smsSend($client->mobile,$message);
            $response = [
                'status' => true,
                'message' => 'OTP Sent Successfully To Registered Mobile Number',
                'otp' => $client->otp,
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
        $client = Client::where('mobile',$mobile)->where('otp',$otp);
        if($client->count() > 0){
            $client = $client->first();
            $client->password =  Hash::make($request->input('confirm_password'));
            $client->otp = null;
            $client->save();
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

    public function orderHistory($client_id)
    {
        $order = Order::where('vendor_id', $client_id)->where('payment_status',2)->orderBy('id', 'desc')->limit(50)->get();
        $response = [
            'status' => true,
            'message' => 'Order history',
            'data' => ClientOrderHistoryResource::collection($order),
        ];

        return response()->json($response, 200);
    }

    public function clientScheduleUpdate(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'date' => ['required','date_format:Y-m-d'],
            'status' => 'required|in:1,2',
            'client_id' => 'required|numeric',
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
        $date = $request->input('date');
        $client_id = $request->input('client_id');
        $status = $request->input('status');
        if ($status == '1') {
            $job = ClientSchedule::firstOrNew(['date'=>$date,'user_id'=>$client_id]);
            if ($job) {
                $job->user_id = $client_id;
                $job->date = $date;
                $job->save();
            }
        } else {
            $job = ClientSchedule::firstOrNew(['date'=>$date,'user_id'=>$client_id]);
            if ($job) {
                $job->delete();
            }
        }
        $response = [
            'status' => true,
            'message' => 'Job Scheduled Successfully',
            'error_code' => false,
            'error_message' => null,
        ];
        return response()->json($response, 200);
    }

    public function orderStatus($order_id,$status)
    {
        $order = Order::find($order_id);
        if ($order) {
            $order->order_status = $status;
            $order->save();

            $user_account = UserBankAccount::where('user_id', $order->customer_id)->first();
            if ($status == '5') {
                $refund = new RefundInfo();
                $refund->order_id = $order->id;
                if ($user_account) {
                    $refund->account_id = $user_account->id;
                }
                $refund->amount = $order->advance_amount;
                if ($refund->save()) {
                    $order->refund_request = 2;
                    $order->save();
                }
            }
        }
        $response = [
            'status' => true,
            'message' => 'Order Status Updated Successfully',
        ];
        return response()->json($response, 200);
    }
}
