<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Freelancer;
use Hash;
class FreelancerController extends Controller
{
    public function freelancerRegistration(Request $request){
        $validator =  Validator::make($request->all(),[
	        'name'             => ['required', 'string', 'max:255'],
            'mobile'           => ['required','digits:10','numeric','unique:freelancers'],
            'email'            => 'unique:freelancers',
            'password'         => ['required', 'string', 'min:8', 'same:confirm_password'],
            'state'            => 'required|numeric',
            'city'             => 'required|numeric',
            'address'          => 'required|string',
            'opening_time'     => 'required|date_format:H:i:s',
            'closing_time'     => 'required|date_format:H:i:s'
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

        $freelancer = new Freelancer;
        $freelancer->name = $request->input('name');
        $freelancer->mobile = $request->input('mobile');
        $freelancer->email = $request->input('email');
        $freelancer->password = Hash::make($request->input('password'));
        $freelancer->work_experience = $request->input('work_experience');
        $freelancer->state = $request->input('state');
        $freelancer->city = $request->input('city');
        $freelancer->address = $request->input('address');
        $freelancer->latitude = $request->input('latitude');
        $freelancer->longitude = $request->input('longitude');
        $freelancer->service = $request->input('service');
        $freelancer->opening_time = $request->input('opening_time');
        $freelancer->closing_time = $request->input('closing_time');
        if ($freelancer->save()) {
            $response = [
                'status' => true,
                'message' => 'Freelancer Registered Successfully',
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
