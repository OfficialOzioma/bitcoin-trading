<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordSaveRequest extends FormRequest
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
        $rules = [
            'token' => 'required',
            'email' => 'required',
            'password' =>[
                'required',
                'string',
                'min:8',
                'strong_pass',// must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
            ],
            'password_confirmation' => 'required|min:8|same:password'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'token.required' => __('Verification code can\'t be empty'),
            'email.required' => __('Email can\'t be empty'),
            'password.required' => __('Password can\'t be empty'),
            'password.strong_pass' => __('Password must be consist of one uppercase, one lowercase and one number'),
            'password.regex' => __('Password must consist of one uppercase, one lowercase and one number'),
            'password_confirmation.required' => __('Confirm password can\'t be empty'),
            'password.min' => __('Password can\'t be less then 8 character'),
            'password_confirmation.same' =>__( 'Confirm password must be same as password'),
        ];
    }
}
