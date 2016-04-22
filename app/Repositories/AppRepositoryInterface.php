<?php

namespace App\Repositories;

interface AppRepositoryInterface
{

    public function createNew($data);

    public function updateExisting($id, $data);

    public function deleteItems($ids);

    public function getAll();

    public function getAllWith($resources);

    public function findById($id);

    public function findByIdWith($id, $resources);

    public function findByValue($key, $value);

    public function findByValueWith($key, $value, $resource);

    public function getByValue($key, $value);

    public function getByValueWith($key, $value, $resource);

    public function getLastCreated();

    public function getLastUpdated();
}