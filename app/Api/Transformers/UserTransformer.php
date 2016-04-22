<?php

namespace App\Api\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'    => (int)$user->id,
            'name'  => $user->name,
            'email' => $user->email
        ];
    }
}