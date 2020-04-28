<?php namespace Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'code' => 'required|unique:groups',
            'name' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'code.required' =>  __('This is a required field.'),
            'code.unique' => __('Group code already exists.'),
            'name.required' =>  __('This is a required field.'),
        ];
    }
}