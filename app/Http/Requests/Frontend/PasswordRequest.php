<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
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
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    // $request_pass = $this->request->get('current_password');
                    $user_pass    = auth()->user()->password;
                    if (!Hash::check($value, $user_pass)) {
                        $fail(
                            'The '.
                            Str::of($attribute)->replace('_', ' ').
                            ' is invalid.');
                    }
                },
            ],
            'password'         => 'required|confirmed',
        ];
    }
}
