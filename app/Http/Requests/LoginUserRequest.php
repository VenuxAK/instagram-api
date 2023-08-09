<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class LoginUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "email" => ["required", "email", "string", "max:255", Rule::exists('users', 'email')],
            "password" => ["required", "max:255", "string"]
        ];
    }

    public function messages()
    {
        return [
            "email.exists" => "The selected email address does not match with our records."
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                "status" => 422,
                "statusText" => "Unprocessable Content",
                "errors" => $validator->errors()
            ], 422)
        );
    }
}
