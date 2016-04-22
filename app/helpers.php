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
 * Get the ID of a model from the route path of the page that a form request originated from. If the route is a
 * standard 'MODELNAME/MODELID', the MODELID will be popped off the end, otherwise the index of the model ID within
 * the path should be passed through, and that value will be returned.
 *
 * @param int|null $num
 * @return mixed
 */
function getModelMatch($num = null)
{
    $route = Session::previousUrl();
    $routeParts = explode('/', $route);

    return $num === null ? array_pop($routeParts) : $routeParts[$num - 1];
}

/**
 * Retrieve and return a specific cookie value by it's key from the Request header.
 *
 * @param \Illuminate\Http\Request $request
 * @param string $key
 * @return string
 */
function getHeaderCookie(\Illuminate\Http\Request $request, $key)
{
    $cookies = $request->header('cookie');
    $cookieParts = explode(' ', $cookies);

    $cookie = '';

    foreach ($cookieParts as $crumb) {
        if (substr($crumb, 0, strlen($key)) === $key) {
            $cookie .= trim(ltrim($crumb, $key));
            break;
        }
    }

    return $cookie;
}