<?php

namespace App\Repositories\User;

use App\Repositories\AppRepositoryInterface;

interface UserRepositoryInterface extends AppRepositoryInterface
{

    public function findByName($name);

    public function findByNameWith($name, $resources);

    public function findByEmail($email);

    public function findByEmailWith($email, $resources);

    public function updateTokenKey($id, $tokenKey);
}