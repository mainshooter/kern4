<?php

/**
 * Get a configuration value from the configuration array
 *
 * The goal of this function is to provide
 * a configuration value from the configuration array.
 *
 * The function can search until two dimensions in array,
 * to perform this search, the value of the argument
 * passed to the function, needs to have the keys
 * separated by backslash, 'key1\key2'
 * (database\host)
 *
 * @param  string $key
 * @return mixed
 */
function config($key = null)
{
	$config = require 'config/config.php';

	if ($key) {

		// verify the occurence of the / and be sure it is equal to 1.
		if (strpos($key, '/') && substr_count($key, '/') === 1)
		{
			$parts = explode('/', $key);

			return isset($config[$parts[0]][$parts[1]]) ?
			       $config[$parts[0]][$parts[1]] :
			       false;
		}

		return isset($config[$key]) ? $config[$key] : false;
	}
}

/**
 * Load and pass data to the view
 *
 * The view represents the presenation of our app,
 * it's the html structure of our app.
 *
 * The only reason for a view to exists is to
 * present the data to the use using a choosed format
 *
 * The view is and has to be STUPID, no logic here.
 *
 * @param  string     $path The path of the view we want to load
 * @param  array|null $data The data we want to pass in to view
 */
function view($path, array $data = null)
{
	if ($data) {
		/**
		 * PHP's built in function
		 *
		 * Creates variables using array keys as their names
		 * and array values as variable values
		 */
		extract($data);
	}

	$content = $path . config('view/view.extension') . '.php';

	$base = config('view/view.path') . '/' .
	        config('view/view.template') .
	        config('view/view.extension') . '.php';

	require $base;
}

/**
 * Http headers redirect
 *
 * @param  stiring $pat
 */
function redirect($path = null)
{
	if ($path) {
		header("Location: {$path}.php");
	}
}

/**
 * Security Purpose
 *
 * Escape the input data received from the users,
 * remember, all the input data is considered harmful
 * for our app, no matter what!
 *
 * Escape input data = clean input data of harmful characters
 *
 * @return mixed
 */

function escape($data = null)
{
	if ($data) {
		return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
	}
}


/**
 * Check whatever a value exists in an array
 *
 * We are using the ternary operator, a short
 * sintax for the if else control structure
 *
 * condition to check ? 'true' : 'false';
 * echo 1 == 1 ? 'true' : 'false';
 *
 * @param  array  $array The array to check
 * @param  string $key   The key to search for
 * @return boolean
 */
function exists($array, $key)
{
	return isset($array[$key]) ? $array[$key] : false;
}


/**
 * Security Purpose - CSRF
 *
 * Generate csrf token
 *
 */
function csrf_token()
{
	$_SESSION[config('csrf/csrf_name')] = password_hash(uniqid(rand(), true), PASSWORD_DEFAULT);

	return $_SESSION[config('csrf/csrf_name')];
}

/**
 * Security Purpose - CSRF
 *
 * Check csrf token
 *
 */
function csrf_verify()
{
	$csrf_value = $_POST[config('csrf/csrf_name')];

	$csrf_session = $_SESSION[config('csrf/csrf_name')];

	if (config('csrf/csrf_active') && isset($csrf_value)) {

		if ($csrf_value != $csrf_session) {

				die('CSRF PROTECTION ACTIVATED!');
		}
	}
}


/**
 * Work with SESSION
 *
 * Add a new element in the session superglobal array
 *
 * @param  string $key
 * @param  mixed $value
 */
function put($key = null, $value = null)
{
	if ($key && $value) {
		$_SESSION[$key] = $value;
	}
}

/**
 * Work with SESSION
 *
 * Check if the session superglobal array has an element
 *
 * We can provide a default value if it hasn't
 * and return back this value
 *
 * @param  string  $key
 * @param  mixed  $value
 * @return mixed
 */
function has($key, $value = null)
{
	if (isset($_SESSION[$key])) {
		return $_SESSION[$key];
	} elseif ($value) {
		return $value;
	}

	return false;
}


/**
 * Work with SESSION
 *
 * Unset an element from the session superglobal array
 * Reset the session superglobal array
 *
 * @param  string $key
 */
function del($key = null)
{
	if (isset($_SESSION[$key])) {
		unset($_SESSION[$key]);
		reset($_SESSION);
	}
}
function getCountOfAllToDos($userKey) {
	$sql = "SELECT id FROM tasks WHERE user_key=" . $userKey . " AND done=0";
	$todos = read_query($sql, array());

	if (empty($todos)) {
		return(0);
	}
	return(count($todos));
}
