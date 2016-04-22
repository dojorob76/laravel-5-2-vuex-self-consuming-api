<?php

namespace App\Services\Users;

use App\User;
use Exception;
use App\Services\ModelService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserService extends ModelService
{

    protected $userRepo;
    protected $model;

    /**
     * UserService constructor.
     *
     * @param UserRepositoryInterface $userRepo
     * @param User $model
     */
    public function __construct(UserRepositoryInterface $userRepo, User $model)
    {
        $this->userRepo = $userRepo;
        parent::__construct($userRepo, $model);
    }

    /**
     * @return Collection|JsonResponse
     */
    public function getAllUsers()
    {
        return $this->getAllInstances();
    }

    /**
     * @param int $id
     * @return User|JsonResponse
     */
    public function findUserById($id)
    {
        return $this->findInstanceById($id);
    }

    /**
     * @param string $email
     * @return User|JsonResponse
     */
    public function findUserByEmail($email)
    {
        return $this->findInstanceByValue('email', $email, 'string');
    }

    /**
     * @param array $data
     * @return User|JsonResponse
     */
    public function createNewUser($data)
    {
        // Encrypt the Password for storage
        $data['password'] = bcrypt($data['password']);

        return $this->createNewInstance($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return User|JsonResponse
     */
    public function updateExistingUser($id, $data)
    {
        return $this->updateExistingInstance($id, $data);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroySingleUser($id)
    {
        return $this->destroySingleInstance($id);
    }

    /**
     * @param array $ids
     * @return JsonResponse
     */
    public function destroyUsers($ids)
    {
        return $this->destroyInstances($ids);
    }

    /**
     * @param int $id
     * @param string $tokenKey
     * @return User|JsonResponse
     */
    public function updateUserTokenKey($id, $tokenKey)
    {
        if (app()->environment() == 'local') {
            // Make sure we received the correct input types
            $idHelp = 'the ID of the User whose token key should be updated';
            $tokenHelp = 'the new token key to set on the User';
            $this->miscastVar('intnum', $id, $idHelp);
            $this->miscastVar('string', $tokenKey, $tokenHelp);
        }

        // Attempt to Update the User's Token Key, or return a JsonResponse error
        try {
            $user = $this->userRepo->updateTokenKey($id, $tokenKey);
        } catch (ModelNotFoundException $e) {
            // The model instance does not exist, so return the JsonResponse error
            return $this->getJsonErrorForException($e, 404);
        } catch (Exception $e) {
            // Something else has gone awry, so return the JsonResponse error
            return $this->getJsonErrorForException($e, 422);
        }

        return $user;
    }
}