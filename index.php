<?php
$alert = '<style>
	.alert {top: -30px;}
</style>;';
/**
 * Includes the main app file
 *
 * Using the require PHP's built in construction, it
 * will include the required file at the specified path
 * Failing to do so, will halt ( stop ) the execution of the script
 * rising a Fatal Error and a Parse Error
 *
 */
require 'app.php';
require 'models/user.php';
require 'models/task.php';

// array variable for passing data to view ( just in case )
$data = [];

// check if the cookie exists
if (isset($_COOKIE[config('cookie/cookie_name')])) {

	// if exists, grab user's tasks from tasks table
	$data = getTasks($_COOKIE[config('cookie/cookie_name')], $conn);

    // mark task as done if the tid_done is found in the query string
	(isset($_GET['tid_done'])) ? markTaskAsDone($_COOKIE[config('cookie/cookie_name')], $_GET['tid_done'], $conn) : false;

    // mark task as undone if the tid_undone is found in the query string
	(isset($_GET['tid_undone'])) ? undoneTask($_COOKIE[config('cookie/cookie_name')], $_GET['tid_undone'], $conn) : false;

    // delete all tasks for the current user only if all tasks are already done
	delTasks($_COOKIE[config('cookie/cookie_name')], $conn);

} else {
	// create a new user
	createUser($conn);
}

//check if there is a form post request
if($_SERVER['REQUEST_METHOD'] === 'POST') {

	if (ISSET($_POST['name'])) {
		$name = escape(trim($_POST['name']));
		if (!empty($name) && strlen($name) <= 47) {
			global $alert;
			// create a new task
			$alert = "<style>
				.alert {top: 30px;}
			</style>";
			newTask($_COOKIE[config('cookie/cookie_name')], $name, $conn);
		}
	}
	else if (ISSET($_FILES['profile'])) {
		addProfile($_FILES['profile'], $_COOKIE['user_key']);
	}

}
// load the view and pass(if exists) some data
view('index', $data);
