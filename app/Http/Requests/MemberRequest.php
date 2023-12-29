<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class MemberRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'name'  => 'required|string|max:100',
                    'email' => 'required|email|unique:member|max:100',
                    'phone' => 'required|digits_between:8,20',
                    'hobbies' => 'array',
                    'hobbies.*' => 'string|max:50'
                ];
                break;
            case 'PUT':
                return [
                    'name'  => 'string|max:100',
                    'email' => 'email|max:100',
                    'phone' => 'digits_between:8,20',
                    'hobbies' => 'array',
                    'hobbies.*' => 'string|max:50'
                ];
                break;
            default:
                return [];
                break;
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation errors',
            'errors' => $validator->errors(),
        ], 422));   
    }
}
