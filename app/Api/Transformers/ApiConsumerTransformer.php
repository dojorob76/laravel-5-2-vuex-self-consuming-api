<?php

namespace App\Api\Transformers;

use App\ApiConsumer;
use League\Fractal\TransformerAbstract;

class ApiConsumerTransformer extends TransformerAbstract
{

    /**
     * @param ApiConsumer $apiConsumer
     * @return array
     */
    public function transform(ApiConsumer $apiConsumer)
    {
        return [
            'id'    => (int)$apiConsumer->id,
            'email' => $apiConsumer->email
        ];
    }
}