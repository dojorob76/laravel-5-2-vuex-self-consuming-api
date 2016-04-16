<?php

namespace App\Http\Requests\ApiConsumer;

use Dingo\Api\Http\FormRequest;

class ApiConsumerResetKeyRequest extends FormRequest
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
            'consumer_id' => 'required|numeric|exists:api_consumers,id|model_match:' . $this->segment(2)
        ];
    }

    public function messages()
    {
        // The consumer_id field is hidden, so if anything fails validation, return a generic message
        $message = 'A reset key could not be created. Please refresh the page and try again.';

        return [
            'consumer_id.required'    => $message,
            'consumer_id.numeric'     => $message,
            'consumer_id.exists'      => $message,
            'consumer_id.model_match' => $message
        ];
    }

}