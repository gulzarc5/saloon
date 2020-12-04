<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Validator;

class AddressController extends Controller
{
    public function addAddress(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'name' => 'required',
            'mobile' => 'required|numeric|digits:10',
            'state' => 'required',
            'city' => 'required',
            'pin' => 'required',
            'address' => 'required',
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

        $address = new Address();
        $address->user_id = $request->input('user_id');
        $address->name = $request->input('name');
        $address->email = $request->input('email');
        $address->mobile = $request->input('mobile');
        $address->state = $request->input('state');
        $address->city = $request->input('city');
        $address->pin = $request->input('pin');
        $address->address = $request->input('address');
        $address->email = $request->input('email');
        $address->latitude = $request->input('latitude');
        $address->longitude = $request->input('longitude');
        $address->save();
        $response = [
            'status' => true,
            'message' => 'Address Added Successfully',
            'error_code' => false,
            'error_message' => null,
        ];
        return response()->json($response, 200);
    }

    public function addressList($customer_id)
    {
        $address =  Address::where('user_id',$customer_id)->get();
        $response = [
            'status' => true,
            'message' => 'Address List',
            'data' => $address,
        ];
        return response()->json($response, 200);
    }

    public function addressFetch($address_id)
    {
        $address = Address::find($address_id);
        $response = [
            'status' => true,
            'message' => 'Address Detail',
            'data' => $address,
        ];
        return response()->json($response, 200);
    }

    public function addressUpdate(Request $request,$address_id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'mobile' => 'required|numeric|digits:10',
            'state' => 'required',
            'city' => 'required',
            'pin' => 'required',
            'address' => 'required',
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

        $address = Address::find($address_id);
        $address->name = $request->input('name');
        $address->email = $request->input('email');
        $address->mobile = $request->input('mobile');
        $address->state = $request->input('state');
        $address->city = $request->input('city');
        $address->pin = $request->input('pin');
        $address->address = $request->input('address');
        $address->email = $request->input('email');
        $address->latitude = $request->input('latitude');
        $address->longitude = $request->input('longitude');
        $address->save();
        $response = [
            'status' => true,
            'message' => 'Address Added Successfully',
            'error_code' => false,
            'error_message' => null,
        ];
        return response()->json($response, 200);
    }
}
