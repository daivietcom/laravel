<?php namespace Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'display_name' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'display_name.required' =>  __('This is a required field'),
            'display_name.string' => __('Not a valid')
        ];
    }
}