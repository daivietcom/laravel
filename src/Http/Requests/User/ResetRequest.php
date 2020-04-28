<?php namespace Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ResetRequest extends FormRequest
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
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:' . $this->passLen
        ];
    }

    public function messages()
    {
        return [
            'email.required' =>  __('Please enter the email address.'),
            'email.email' => __('Not a valid email address.'),
            'password.required' =>  __('This is a required field.'),
            'password.confirmed' => __('Password do not match.'),
            'password.min' => __('Password too short.', ['minlength' => $this->passLen])
        ];
    }
}