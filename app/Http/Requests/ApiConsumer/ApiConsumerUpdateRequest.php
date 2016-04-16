<?php

namespace App\Http\Requests\ApiConsumer;

use Dingo\Api\Http\FormRequest;

class ApiConsumerUpdateRequest extends FormRequest
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
            'id'    => 'required|numeric|model_match:' . $this->segment(2),
            'email' => 'required|email|unique:api_consumers,email,' . $this->get('id'),
            'level' => 'sometimes|required|numeric|between:0,9|admin_only:' . $this->fullUrl() . ',admin'
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        // The ID field is hidden, so if it is compromised, return a generic message
        $idMessage = 'The submitted values have been compromised. Please refresh the page and try again.';

        return [
            'id.required'      => $idMessage,
            'id.numeric'       => $idMessage,
            'id.model_match'   => $idMessage,
            'email.unique'     => 'This email address is already connected to an API Account.',
            'level.admin_only' => 'API Access Levels may only be updated by an administrator.'
        ];
    }
}
