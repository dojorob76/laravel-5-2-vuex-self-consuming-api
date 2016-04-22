<?php

namespace App\Http\Requests\Authentication;

use Dingo\Api\Http\FormRequest;

class UserLoginRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'     => 'required|max:255',
            'password'  => 'required|min:8',
            'token_key' => 'required'
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        // The 'token_key' field is hidden, so if it fails, return a generic message
        return [
            'token_key.required' => 'Whoops! Something went wrong. Please reload the page and try again.'
        ];
    }
}
