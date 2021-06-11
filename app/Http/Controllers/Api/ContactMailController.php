<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMail;
use App\Models\JobCategory;
use App\Models\SubCategory;
use App\Models\ThirdLevelCategory;
use Illuminate\Http\Request;
use Validator;

class ContactMailController extends Controller
{
    public function insert(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'category_id' => 'required|numeric',
            'sub_category_id' => 'nullable|numeric',
            'third_category_id' => 'nullable|numeric',
            'name' => 'required',
            'mobile' => 'required|numeric|digits:10',
            'message' => 'nullable|string',
            'booking_date' => 'required|date|date_format:Y-m-d',
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

        if (!empty($request->input('category_id'))) {
            $check_category = JobCategory::where('id', $request->input('category_id'))->count();

            if ($check_category == 0) {
                $response = [
                    'status' => false,
                    'message' => 'Sorry Category Not Found. Please Check category id you are sending',
                    'error_code' => true,
                    'error_message' => null,
                ];
                return response()->json($response, 200);
            }
        }

        if (!empty($request->input('sub_category_id'))) {
            $check_category = SubCategory::where('id', $request->input('sub_category_id'))->count();

            if ($check_category == 0) {
                $response = [
                    'status' => false,
                    'message' => 'Sorry Sub Category Not Found. Please Check Sub category id you are sending',
                    'error_code' => true,
                    'error_message' => null,
                ];
                return response()->json($response, 200);
            }
        }
        if (!empty($request->input('third_category_id'))) {
            $check_category = ThirdLevelCategory::where('id', $request->input('third_category_id'))->count();

            if ($check_category == 0) {
                $response = [
                    'status' => false,
                    'message' => 'Sorry Third Category Not Found. Please Check Third category id you are sending',
                    'error_code' => true,
                    'error_message' => null,
                ];
                return response()->json($response, 200);
            }
        }

        $contact_mail = new ContactMail();
        $contact_mail->category_id = $request->input('category_id');
        $contact_mail->sub_category_id = $request->input('sub_category_id');
        $contact_mail->third_category_id = $request->input('third_category_id');
        $contact_mail->name = $request->input('name');
        $contact_mail->mobile = $request->input('mobile');
        $contact_mail->message = $request->input('message');
        $contact_mail->booking_date = $request->input('booking_date');
        $contact_mail->save();

        $response = [
            'status' => true,
            'message' => 'Thanks For Contacting Us We will get back to you soon',
            'error_code' => false,
            'error_message' => null,
        ];
        return response()->json($response, 200);
    }
}
