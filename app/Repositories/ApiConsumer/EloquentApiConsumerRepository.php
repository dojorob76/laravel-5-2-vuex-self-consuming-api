<?php

namespace App\Repositories\ApiConsumer;

use App\ApiConsumer;
use App\Repositories\EloquentRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EloquentApiConsumerRepository extends EloquentRepository implements ApiConsumerRepositoryInterface
{

    private $model;

    /**
     * EloquentApiConsumerRepository constructor.
     *
     * @param ApiConsumer $model
     */
    public function __construct(ApiConsumer $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }

    /**
     * Attempt to find and return an ApiConsumer by their email address or return a ModelNotFoundException.
     *
     * @param string $email
     * @return ApiConsumer|ModelNotFoundException
     */
    public function findByEmail($email)
    {
        return $this->findByValue('email', $email);
    }

    /**
     * Attempt to find and return an ApiConsumer by their token or return a ModelNotFoundException.
     *
     * @param string $token
     * @return ApiConsumer|ModelNotFoundException
     */
    public function findByToken($token)
    {
        return $this->findByValue('token', $token);
    }
}