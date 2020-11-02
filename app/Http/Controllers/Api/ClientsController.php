<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\SignUpOtp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class ClientsController extends Controller
{
    public function clientRegistration(Request $request){
        $validator =  Validator::make($request->all(),[
	        'name'             => ['required', 'string', 'max:255'],
            'mobile'           => ['required','digits:10','numeric','unique:clients'],
            'password'         => ['required', 'string', 'min:8'],
            'otp'              => 'required|digits:5|numeric',
            'clientType'       => 'required|in:1,2',
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
        $validator =  Validator::make($request->all(),[

        ]);
    }
}
