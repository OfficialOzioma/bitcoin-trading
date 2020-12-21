<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferCoinRequest extends FormRequest
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
        $rule = [
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => ['required','numeric','min:1','max:999999.99999999'],
        ];

        return $rule;
    }
    public function messages()
    {
        return  [
            'wallet_id.required' => __('Please select a wallet'),
            'wallet_id.exists' => __('Invalid wallet'),
            'amount.required' => __('Coin amount can not be empty')
        ];
    }
}
