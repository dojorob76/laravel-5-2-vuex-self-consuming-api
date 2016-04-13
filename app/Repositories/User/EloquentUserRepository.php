<?php

namespace App\Repositories\User;

use App\User;
use App\Repositories\EloquentRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EloquentUserRepository extends EloquentRepository implements UserRepositoryInterface
{

    private $model;

    /**
     * EloquentUserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }

    /**
     * Attempt to find and return a User by their name or return a ModelNotFoundException.
     *
     * @param string $name
     * @return User|ModelNotFoundException
     */
    public function findByName($name)
    {
        return $this->findByValue('name', $name);
    }

    /**
     * Attempt to find and return a User by their name with a specific relationship resource eager loaded, or return
     * a ModelNotFoundException.
     *
     * @param string $name
     * @param string $resource
     * @return User|ModelNotFoundException|static
     */
    public function findByNameWith($name, $resource)
    {
        return $this->findByValueWith('name', $name, $resource);
    }

    /**
     * Attempt to find and return a User by their email address or return a ModelNotFoundException.
     *
     * @param string $email
     * @return User|ModelNotFoundException
     */
    public function findByEmail($email)
    {
        return $this->findByValue('email', $email);
    }

    /**
     * Attempt to find and return a User by their email address with a specific relationship resource eager loaded, or
     * return a ModelNotFoundException.
     *
     * @param string $email
     * @param string $resource
     * @return User|ModelNotFoundException|static
     */
    public function findByEmailWith($email, $resource)
    {
        return $this->findByValueWith('email', $email, $resource);
    }
}