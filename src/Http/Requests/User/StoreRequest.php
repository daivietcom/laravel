<?php namespace Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    protected $passLen;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function __construct()
    {
        $this->passLen = config('site.password_length');
    }

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
            'email' => 'required_without:phone|nullable|email|unique:users',
            'phone' => 'required_without:email|nullable|unique:users',
            'display_name' => 'required|string',
            'password' => 'required|confirmed|min:' . $this->passLen
        ];
    }

    public function messages()
    {
        return [
            'email.required_without' =>  __('Please enter the email address or phone number.'),
            'email.email' => __('Not a valid email address.'),
            'email.unique' => __('Email address already exists.'),
            'phone.required_without' =>  __('Please enter the email address or phone number.'),
            'phone.unique' => __('Phone number already exists.'),
            'display_name.required' =>  __('This is a required field.'),
            'display_name.string' => __('Not a valid.'),
            'password.required' =>  __('This is a required field.'),
            'password.confirmed' => __('Password do not match.'),
            'password.min' => __('Password too short.', ['minlength' => $this->passLen])
        ];
    }
}