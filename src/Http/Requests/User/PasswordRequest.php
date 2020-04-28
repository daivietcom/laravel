<?php namespace Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
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
            'password_old' =>  'required|min:' . $this->passLen,
            'password' => 'required|confirmed|min:' . $this->passLen
        ];
    }

    public function messages()
    {
        return [
            'password_old.required' =>  __('This is a required field.'),
            'password_old.min' => __('Password too short.', ['minlength' => $this->passLen]),
            'password.required' =>  __('This is a required field.'),
            'password.confirmed' => __('Password do not match.'),
            'password.min' => __('Password too short.', ['minlength' => $this->passLen])
        ];
    }
}