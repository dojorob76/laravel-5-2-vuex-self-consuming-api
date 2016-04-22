<?php

namespace App\Services;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\AppRepositoryInterface;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Utilities\RequestResponseUtilityTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ModelService
{

    use RequestResponseUtilityTrait, DispatchesJobs;

    private $repo;
    private $model;

    public function __construct(AppRepositoryInterface $repo, Model $model)
    {
        $this->repo = $repo;
        $this->model = $model;
    }

    /**
     * @param array $data - Associative array containing the values that the model instance should be created with
     * @return Model|JsonResponse
     */
    public function createNewInstance($data)
    {
        if (app()->environment() == 'local') {
            // Make sure we received the correct input type
            $helpMsg = 'create new ' . $this->getModelName() . ' data array';
            $this->miscastVar('array', $data, $helpMsg);
        }

        // Attempt to create the new model instance
        try {
            $instance = $this->repo->createNew($data);
        } catch (Exception $e) {
            // The instance could not be created, so return JsonResponse error
            return $this->getJsonErrorForException($e, 422);
        }

        // Return the new model instance
        return $instance;
    }

    /**
     * @param int $id - The ID of the model instance we want to update
     * @param array $data - Associative array containing the values that the model instance should be updated with
     * @return Model|JsonResponse
     */
    public function updateExistingInstance($id, $data)
    {
        if (app()->environment() == 'local') {
            // Make sure we received the correct input types
            $idHelpMsg = 'the ID of the ' . $this->getModelName() . ' that should be updated';
            $dataHelpMsg = 'update existing ' . $this->getModelName() . ' data array';
            $this->miscastVar('intnum', $id, $idHelpMsg);
            $this->miscastVar('array', $data, $dataHelpMsg);
        }
        // Attempt to update the model instance
        try {
            $instance = $this->repo->updateExisting($id, $data);
        } catch (Exception $e) {
            // The model instance could not be updated, so return JsonResponse error
            return $this->getJsonErrorForException($e, 422);
        }

        // Return the updated instance
        return $instance;
    }

    /**
     * @param int $id - The ID of the single instance that should be deleted.
     *
     * @return JsonResponse
     */
    public function destroySingleInstance($id)
    {
        if (app()->environment() == 'local') {
            // Make sure we received the correct input type
            $helpMsg = 'the ID of a the ' . $this->getModelName() . ' that should be deleted';
            $this->miscastVar('intnum', $id, $helpMsg);
        }
        // Attempt to retrieve the instance
        $instance = $this->findInstanceById($id);

        if ($instance instanceof $this->model) {
            // The instance exists, so it can be deleted
            return $this->destroyInstances([$id]);
        }

        // The instance could not be retrieved, so return the JsonResponse error
        return $instance;
    }

    /**
     * @param array $ids - a simple numeric array containing the ID(s) of model instance(s) that should be deleted.
     *
     * @return JsonResponse
     */
    public function destroyInstances($ids)
    {
        $modelName = $this->getModelName();

        if (app()->environment() == 'local') {
            // Make sure we received the correct input type
            $helpMsg = 'the IDs of the ' . str_plural($modelName) . ' that should be deleted';
            $this->miscastVar('array', $ids, $helpMsg);
        }
        // Attempt to delete the model instance(s)
        try {
            $response = $this->repo->deleteItems($ids);
        } catch (Exception $e) {
            return $this->getJsonErrorForException($e, 422);
        }

        // Set success/failure responses
        $sMsg = count($ids) === 1 ? $modelName . ' has' : str_plural($modelName) . ' have';
        $fMsg = count($ids) === 1 ? $modelName . ' was not' : str_plural($modelName) . ' have not been';
        // Return the appropriate response based on success or failure - add the status to the array for Dingo responses
        $success = response()->json(['message' => $response . ' ' . $sMsg . ' been deleted.', 'status' => 200], 200);
        $fail = response()->json(['message' => 'The ' . $fMsg . ' deleted.', 'status' => 422], 422);

        return $response === 0 ? $fail : $success;
    }

    /**
     * @return JsonResponse|Collection
     */
    public function getAllInstances()
    {
        // Attempt to retrieve all instances of the model
        try {
            $instances = $this->repo->getAll();
        } catch (Exception $e) {
            // The instances could not be retrieved, so return JsonResponse error
            return $this->getJsonErrorForException($e, 422);
        }

        // Return all model instances
        return $instances;
    }

    /**
     * @param int $id - The ID of the model instance we want to retrieve
     * @return JsonResponse|Model
     */
    public function findInstanceById($id)
    {
        if (app()->environment() == 'local') {
            // Make sure we received the correct input type
            $helpMsg = 'the ID of a specific ' . $this->getModelName();
            $this->miscastVar('intnum', $id, $helpMsg);
        }

        // Attempt to retrieve the instance by it's ID
        try {
            $instance = $this->repo->findById($id);
        } catch (ModelNotFoundException $e) {
            // The model instance does not exist, so return the JsonResponse error
            return $this->getJsonErrorForException($e, 404);
        } catch (Exception $e) {
            // Something else has gone awry, so return the JsonResponse error
            return $this->getJsonErrorForException($e, 422);
        }

        // Return the model instance
        return $instance;
    }

    /**
     * @param string $col - The DB Column name we are searching
     * @param mixed $val - The value we are searching for
     * @param string $type - The type of variable that $val should be (i.e., 'string', 'int', etc.)
     * @return Model|JsonResponse
     */
    public function findInstanceByValue($col, $val, $type = null)
    {
        if (app()->environment() == 'local' && $type != null) {
            // Make sure we received the correct input type
            $helpMsg = 'the ' . $col . ' value of a specific ' . $this->getModelName();
            $this->miscastVar($type, $val, $helpMsg);
        }
        // Attempt to retrieve the model instance by a specific value
        try {
            $instance = $this->repo->findByValue($col, $val);
        } catch (ModelNotFoundException $e) {
            // The model instance does not exist, so return the JsonResponse error
            return $this->getJsonErrorForException($e, 404);
        } catch (Exception $e) {
            // Something else has gone awry, so return the JsonResponse error
            return $this->getJsonErrorForException($e, 422);
        }

        // Return the model instance
        return $instance;
    }

    /**
     * Helper function to determine whether the correct variable type has been provided to a method, which returns a
     * customized JsonResponse error message ONLY if it has not.
     *
     * @param string $expected - The type of variable that was expected,
     *                           Can be one of: 'array', 'string', 'int', 'num', 'intnum'
     * @param mixed $var - The variable that was provided
     * @param null $help - An OPTIONAL helper message to explain exactly what was expected
     * @return \Illuminate\Http\JsonResponse
     */
    public function miscastVar($expected, $var, $help = null)
    {
        // Get the actual type of the variable that was passed through
        $type = gettype($var);
        // Instantiate empty string for message start
        $mStart = '';
        // Set execute to false so nothing will happen if the variable type is correct
        $execute = false;

        switch ($expected) {
            case 'array':
                if ($type != 'array') {
                    $mStart = 'an ARRAY';
                    $execute = true;
                }
                break;
            case 'string':
                if ($type != 'string') {
                    $mStart = 'a STRING';
                    $execute = true;
                }
                break;
            case 'int':
                if ($type != 'integer') {
                    $mStart = 'an INTEGER';
                    $execute = true;
                }
                break;
            case 'numeric':
                if (!is_numeric($var)) {
                    $mStart = 'a NUMBER';
                    $execute = true;
                }
                break;
            case 'intnum': // Integer cast to string by API (can only be checked as numeric)
                if (!is_numeric($var)) {
                    $mStart = 'an INTEGER';
                    $execute = true;
                }
                break;
            default:
                $execute = false;
                $mStart = '';
        }

        // If the variable was miscast, return the JsonResponse error with the customized message
        if ($execute == true) {
            // Inject the help message if one was provided
            if ($help != null) {
                $mStart .= ' (' . $help . ')';
            }
            // Set the end of the JsonResponse error message
            $mEnd = ' is required, but a variable of type ' . strtoupper($type) . 'was provided instead.';

            return response()->json(['message' => $mStart . $mEnd], 422);
        }
    }

    /**
     * Generate a string that converts 'MyGreatModel' to 'My Great Model' for readability purposes.
     *
     * @return string
     */
    private function getModelName()
    {
        $orig = class_basename($this->model);
        $snake = snake_case($orig);
        $nameParts = explode('_', $snake);
        $modelName = '';
        foreach ($nameParts as $namePart) {
            $modelName .= ucfirst($namePart) . ' ';
        }

        return trim($modelName);
    }

}