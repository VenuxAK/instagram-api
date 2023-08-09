<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            "name" => ["required", "string", "min:3", "max:255"],
            "username" => ["required", "string", "min:3", "max:255", Rule::unique('users', 'username')],
            "email" => ["required", "email","string", "min:3", "max:255", Rule::unique('users', 'email')],
            "password" => ["required", "string", "min:6", "max:255"],
        ];
    }

    public function messages()
    {
        return [
            "username.unique" => "Username has already been taken",
            "email.unique" => "Email has already been taken",
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
