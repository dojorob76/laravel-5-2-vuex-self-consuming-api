<?php

namespace App\Repositories\ApiConsumer;

interface ApiConsumerRepositoryInterface
{

    public function createNew($data);

    public function updateExisting($id, $data);

    public function deleteItems($ids);

    public function findById($id);

    public function findByEmail($email);

    public function findByToken($token);

    public function getAll();

    public function getLastCreated();

    public function getLastUpdated();

}