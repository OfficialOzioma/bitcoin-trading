<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PlanSaveRequest extends FormRequest
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
            'plan_name' => 'required|max:255',
            'duration' => 'required|integer|min:1',
            'amount' => 'required|numeric',
            'bonus_type' => 'required|integer',
            'bonus' => 'required',
            'status' => 'required|integer',
        ];
        if ($this->bonus_type == DISCOUNT_TYPE_PERCENTAGE) {
            $rule['bonus'] = 'numeric|min:0|max:99';
        } else {
            $rule['bonus'] = 'numeric';
        }
        if ($this->image) {
            $rule['image'] = 'image|mimes:jpg,jpeg,png|max:2000';
        }

        return $rule;
    }
}
