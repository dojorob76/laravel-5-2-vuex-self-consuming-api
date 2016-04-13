<?php

namespace App\Http\Requests\ApiConsumer;

use Dingo\Api\Http\FormRequest;

class ApiConsumerRefreshTokenRequest extends FormRequest
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
            'reset_key' => 'required|reset_key_size|valid_reset_key:' . $this->get('email')
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        // The email field is hidden, so if it is compromised, return a generic message
        $emailMessage = 'We are unable to process your request at this time. Please refresh the page and try again.';
        $keyMessage = 'The Reset Key provided is not valid. Click the "Resend Reset Key" button to receive a new key.';

        return [
            'email.required'            => $emailMessage,
            'email.email'               => $emailMessage,
            'email.exists'              => $emailMessage,
            'reset_key.required'        => 'A valid reset key is required. Please enter one now.',
            'reset_key.reset_key_size'  => $keyMessage,
            'reset_key.valid_reset_key' => $keyMessage,
        ];
    }
}
