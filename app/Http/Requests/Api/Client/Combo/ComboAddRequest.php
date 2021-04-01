<?php

namespace App\Http\Requests\Api\Client\Combo;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class ComboAddRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'main_category' => 'required',
            'combo_name' => 'required',
            'service_name' => 'present|array|min:1',
            'service_name.*' => 'required|string',
            'price' => 'present|array|min:1',
            'price.*' => 'required|numeric|min:1',
            'mrp' => 'present|array|min:1',
            'mrp.*' => 'required|numeric|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
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
