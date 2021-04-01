<?php

namespace App\Http\Requests\Api\Customer\Service;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class ServiceListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'service_city' => 'required',
            'category_id' => 'required',
            'type' => 'required|in:1,2,3', //1 = main Category, 2 = Sub Category, 3 = third Category
            'latitude' => 'required',
            'longitude' => 'required',
            'client_type' => 'required:in:1,2', // 1 = freelauncer , 2 = Salon
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $response = [
            'status' => false,
            'message' => 'Validation Error',
            'error_code' => true,
            'error_message' => $errors,
        ];
        throw new HttpResponseException(
            response()->json($response, 200)
        );
    }
}
