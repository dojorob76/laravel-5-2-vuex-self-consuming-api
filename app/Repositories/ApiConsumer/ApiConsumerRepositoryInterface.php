<?php

namespace App\Repositories\ApiConsumer;

use App\Repositories\AppRepositoryInterface;

interface ApiConsumerRepositoryInterface extends AppRepositoryInterface
{

    public function findByEmail($email);

    public function findByToken($token);

}