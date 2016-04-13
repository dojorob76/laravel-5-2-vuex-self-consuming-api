<?php

namespace App\Http\Requests\ApiConsumer;

use App\Utilities\ApiTokenManager;
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

    // Static Rules applicable to all update requests
    protected $rules = [
        'id'    => 'required|numeric',
        'email' => 'required|email'
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = $this->rules;

        // Rules applicable to all update requests that include logic so must be called within a function
        $rules['id'] = 'model_match:' . $this->segment(2);
        $rules['email'] = 'unique:api_consumers,email,' . $this->get('id');

        // If we are in the admin subdomain, the level field is available and should be validated
        if (getSubdomain() == 'admin') {
            $rules['level'] = 'required|numeric|between:0,9';
        } else {
            // If we are not in the admin subdomain, the level field may NOT be included, so if it is, ABORT
            if ($this->has('level')) {
                abort(401, 'You do not have permission to edit API Access Levels. Please ask an admin for help.');
            }
        }

        return $rules;
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
            'id.required'    => $idMessage,
            'id.numeric'     => $idMessage,
            'id.model_match' => $idMessage,
            'email.unique'   => 'This email address is already connected to an API Account.'
        ];
    }
}
