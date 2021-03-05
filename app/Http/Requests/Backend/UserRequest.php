<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name'          => 'required',
            'username'      => "required|max:20|unique:users,username,{$this->id}",
            'email'         => "required|email|max:255|unique:users,email,{$this->id}",
            'mobile'        => "required|numeric|unique:users,mobile,{$this->id}",
            'status'        => 'required',
            'receive_email' => 'required',
            'password'      => 'required_if:id,'.null.'|nullable|min:8',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required_if' => 'The password field is required.'
        ];
    }
}
