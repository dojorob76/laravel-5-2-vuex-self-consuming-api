<?php

namespace App\Http\Requests\ApiConsumer;

use Dingo\Api\Http\FormRequest;

class ApiConsumerAccessRequest extends FormRequest
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
            'email'     => 'required|email|exists:api_consumers',
            'api_token' => 'required|valid_api_credentials:' . $this->get('email')
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
            'email.exists'                    => 'This email is not associated with an API account.',
            'api_token.required'              => 'Your API Access Token is required.',
            'api_token.valid_api_credentials' => 'These API credentials do not match our records.'
        ];
    }
}
