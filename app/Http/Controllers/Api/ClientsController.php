<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
class ClientsController extends Controller
{
    public function clientRegistration(Request $request){
        $validator =  Validator::make($request->all(),[
	        'name'             => ['required', 'string', 'max:255'],
            'mobile'           => ['required','digits:10','numeric','unique:clients'],
            'email'            => 'unique:clients',
            'password'         => ['required', 'string', 'min:8', 'same:confirm_password'],
            'otp'              => 'required|digits:5|numeric',
            'state'            => 'required|numeric',
            'city'             => 'required|numeric',
            'address'          => 'required|string',
            'opening_time'     => 'required|date_format:H:i:s',
            'closing_time'     => 'required|date_format:H:i:s',
            'clientType'       => ['required',  Rule::in(['1', '2'])],
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

        $client = new Client;
        $client->name = $request->input('name');
        $client->mobile = $request->input('mobile');
        $client->email = $request->input('email');
        $client->password = Hash::make($request->input('password'));
        $client->otp = $request->input('otp');
        $client->work_experience = $request->input('work_experience');
        $client->state = $request->input('state');
        $client->city = $request->input('city');
        $client->address = $request->input('address');
        $client->latitude = $request->input('latitude');
        $client->longitude = $request->input('longitude');
        $client->opening_time = $request->input('opening_time');
        $client->closing_time = $request->input('closing_time');
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
                    'data' => $client,
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
        
    }
}
