<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class EloquentRepository
{

    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Create a new Eloquent Model instance, store it in the DB, and return it.
     *
     * @param array $data
     * @return Model
     */
    public function createNew($data)
    {
        // Get the fillable fields for the model from the request data
        $fillableFields = $this->getFillableFieldsArray($data);

        // Create a new instance of the model with the fillable fields and return it
        return $this->model->create($fillableFields);
    }

    /**
     * Attempt to update an existing Eloquent model in the DB and return it, or return a ModelNotFoundException.
     *
     * @param int $id
     * @param array $data
     * @return Model|ModelNotFoundException
     */
    public function updateExisting($id, $data)
    {
        // Make sure we have a valid model instance
        $updated = $this->findById($id);

        if ($updated instanceof $this->model) {
            // Get the fillable fields for the model from the request data
            $fillableFields = $this->getFillableFieldsArray($data);

            foreach ($fillableFields as $key => $value) {
                // If the field value is different than the stored value, update it
                if ($updated->$key != $value) {
                    $updated->$key = $value;
                }
            }
            // Save the updated model
            $updated->save();
        }

        // Return the updated model or the ModelNotFoundException
        return $updated;
    }

    /**
     * Attempt to delete a single or multiple Eloquent model instance(s) from the DB and return the # of successfully
     * deleted instances.
     *
     * @param int|array $ids
     * @return int
     */
    public function deleteItems($ids)
    {
        return $this->model->destroy($ids);
    }

    /**
     * Gather all instances of an Eloquent model and return them in a Collection.
     *
     * @return Collection|static[]
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Gather all instances of an Eloquent model with relationship resources eager loaded and return them in a
     * Collection.
     *
     * @param array $resources
     * @return Collection|static[]
     */
    public function getAllWith($resources)
    {
        $resourceList = getDelimitedStringFromArray($resources, ', ');

        return $this->model->with($resourceList)->get();
    }

    /**
     * Gather all instances of an Eloquent model that have a specific column value and return them in a Collection.
     *
     * @param string $key
     * @param mixed $value
     * @return Collection|mixed
     */
    public function getByValue($key, $value)
    {
        return $this->model->where($key, $value)->get();
    }

    /**
     * Gather all instances of an Eloquent model that have a specific column value with a specific relationship
     * resource eager loaded and return them in a Collection.
     *
     * @param string $key
     * @param mixed $value
     * @param string $resource
     * @return Collection|static[]
     */
    public function getByValueWith($key, $value, $resource)
    {
        return $this->model->with([
            $resource => function ($q) use ($key, $value) {
                $q->where($key, $value);
            }
        ])->get();
    }

    /**
     * Find and return an Eloquent model instance by it's ID or return a ModelNotFoundException.
     *
     * @param int $id
     * @return Model|ModelNotFoundException
     */
    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Find and return an Eloquent model instance with relationship resources eager loaded or return a
     * ModelNotFoundException.
     *
     * @param int $id
     * @param array $resources
     * @return Collection|Model|ModelNotFoundException
     */
    public function findByIdWith($id, $resources)
    {
        // Get a comma delimited string from the resources array
        $resourceList = getDelimitedStringFromArray($resources, ', ');

        return $this->model->with($resourceList)->findOrFail($id);
    }

    /**
     * Find the first instance of an Eloquent model that has a specific column value and return it or return
     * ModelNotFoundException.
     *
     * @param string $key
     * @param mixed $value
     * @return Model|ModelNotFoundException
     */
    protected function findByValue($key, $value)
    {
        return $this->model->where($key, $value)->firstOrFail();
    }

    /**
     * Find and return the first instance of an Eloquent model that has a specific column value, or return a
     * ModelNotFoundException.
     *
     * @param string $key
     * @param mixed $value
     * @param string $resource
     * @return Model|static|ModelNotFoundException
     */
    protected function findByValueWith($key, $value, $resource)
    {
        return $this->model->with([
            $resource => function ($q) use ($key, $value) {
                $q->where($key, $value);
            }
        ])->firstOrFail();
    }

    /**
     * Get the most recently created instance of an Eloquent model and return it.
     *
     * @return Model|ModelNotFoundException
     */
    public function getLastCreated()
    {
        return $this->model->orderBy('created_at', 'desc')->firstOrFail();
    }

    /**
     * Get the most recently updated instance of an Eloquent model and return it.
     *
     * @return Model|ModelNotFoundException
     */
    public function getLastUpdated()
    {
        return $this->model->orderBy('updated_at', 'desc')->firstOrFail();
    }

    /**
     * Get the array of Fillable Fields ONLY from the data array provided by the request.
     *
     * @param array $data
     * @return array
     */
    private function getFillableFieldsArray($data)
    {
        $newModel = new $this->model;
        $fillable = $newModel->getFillable();

        $fillableFieldsArray = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $fillable)) {
                $fillableFieldsArray[$key] = $value;
            }
        }

        return $fillableFieldsArray;
    }
}