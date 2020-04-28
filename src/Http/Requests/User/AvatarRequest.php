<?php namespace Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class AvatarRequest extends FormRequest
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
            'file' => 'required|mimes:jpeg,bmp,png',
            'user' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'file.required' =>  __('Please enter the email address'),
            'file.mimes' => __('Not a valid email address'),
            'user.required' => __('Email already exists'),
            'user.numeric' => __('Email already exists')
        ];
    }
}