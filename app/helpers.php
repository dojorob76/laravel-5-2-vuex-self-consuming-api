<?php

/**
 * Generate flash messages using the Flasher class to display to users.
 *
 * @param null $title
 * @param null $message
 * @return \App\Http\Flasher|mixed
 */
function flasher($title = null, $message = null)
{
    $flash = app('App\Http\Flasher');

    if (func_num_args() == 0) {
        return $flash;
    }

    return $flash->message($message, $title);
}

/**
 * Create and return a delimited string from an array.
 *
 * @param array $array
 * @param $delimiter (',', ':', '-', etc.)
 * @return string
 */
function getDelimitedStringFromArray($array, $delimiter)
{
    // Instantiate empty string
    $string = '';
    // Add each item of the array to the string
    foreach ($array as $item) {
        $string .= $item . $delimiter;
    }
    // Remove trailing delimiter
    $delimitedString = rtrim($string, $delimiter);

    return $delimitedString;
}

/**
 * Return the information from a JsonResponse in an array.
 *
 * @param \Illuminate\Http\JsonResponse $json
 * @return array
 */
function getJsonInfoArray(\Illuminate\Http\JsonResponse $json)
{
    $status = $json->getStatusCode();
    $info = $json->getData('message');
    $message = $info['message'];

    return ['message' => $message, 'status' => $status];
}

/**
 * Get and return the subdomain (if there is one, and it isn't 'www') from a Request.
 *
 * @param null $request
 * @return string|null
 */
function getSubdomain($request = null)
{
    // If a request object was not provided, capture the request
    if ($request == null) {
        $request = getCapturedRequest();
    }

    $host = $request->server('HTTP_HOST');
    $host_parts = explode('.', $host);

    $subdomain = count($host_parts) > 2 ? $host_parts[0] : null;

    if ($subdomain == 'www') {
        $subdomain = null;
    }

    return $subdomain;
}

/**
 * Set the status code for a JsonResponse based on the error provided by an Exception (or not) and a backup code.
 *
 * @param Exception $e
 * @param int $backup
 * @return int
 */
function setStatus($e, $backup)
{
    // If the exception does not have an explicitly defined HTTP status code...
    if (!$status = $e->getCode()) {
        // Return the backup error code instead
        return $backup;
    }
    // If the exception code is not an HTTP status code...
    if ($status > 530) {
        return $backup;
    }

    return $status;
}

/**
 * In the event that a request object is unknown, capture the request and return the instance.
 *
 * @return $this
 */
function getCapturedRequest()
{
    $request = \Illuminate\Http\Request::capture();

    return $request->instance();
}

/**
 * Get the correct path for a route based on which subdomain we are currently in.
 *
 * @param string $route
 * @param null $request
 * @return string
 */
function getPathForSubdomain($route, $request = null)
{
    // If a request object was not provided, capture the request
    if ($request == null) {
        $request = getCapturedRequest();
    }
    // Get the subdomain that we are currently in
    $subdomain = getSubdomain($request);
    // If we are not in a subdomain, return the route, otherwise prepend the subdomain + '-' to the route
    $path = !$subdomain ? $route : $subdomain . '-' . $route;

    return $path;
}

/**
 * Determine whether the correct variable type has been provided to a method, and return false if it has or return a
 * JsonResponse customized error message if it has not.
 *
 * @param string $expected - The type of variable that was expected,
 *                           Can be one of: 'array', 'string', 'int', 'num', 'intnum'
 * @param mixed $var - The variable that was provided
 * @param null $help - An OPTIONAL helper message to explain exactly what was expected
 * @return \Illuminate\Http\JsonResponse
 */
function miscastVar($expected, $var, $help = null)
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