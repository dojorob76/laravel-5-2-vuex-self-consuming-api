<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{

    public function createNew($data);

    public function updateExisting($id, $data);

    public function deleteItems($ids);

    public function findByName($name);

    public function findByNameWith($name, $resources);

    public function findByEmail($email);

    public function findByEmailWith($email, $resources);

    public function findById($id);

    public function findByIdWith($id, $resources);

    public function getAll();

    public function getAllWith($resources);

    public function getLastCreated();

    public function getLastUpdated();
}