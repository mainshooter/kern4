<?php

/**
 * Includes the functions files
 *
 * Using the require PHP's built in construction, it
 * will include the required file at the specified path
 * Failing to do so, will halt ( stop ) the execution of the script
 * rising a Fatal Error and a Parse Error
 * 
 */
require 'includes/functions.php';

require 'includes/db.php';

/*
 * Security Purpose - Session
 *
 * Blocks Javascript to get our cookie session ( document.cookie )
 * Using the ini_set(directive name, value) PHP built in function,
 * we are setting a configuration directive
 * in the PHP.ini file
 */
ini_set('session.cookie_httponly', true);


/**
 * Start a new session with a session id
 *
 * Session is used to persist data
 *
 * Automatically creates a cookie that will store 
 * the session id used for getting the session name 
 * and value from the session file stored on server
 */
session_start();

/**
 * Security Purpose - Session
 *
 * Regenerate the session id value from the file stored on server
 * Passing true to this PHP built in function will 
 * delete the old session file and it will create
 * a new one, passing false will create another file and so on.
 */
session_regenerate_id(true);


/**
 * Set the default local time zone used by all the date/time functions
 */
date_default_timezone_set(config('local_time_zone'));


// check if the connection configuration is set to true or not
if (config('database/connection')) {

    // connect to database
	$conn = connect();

    // check connection
	if(!$conn) {
		die('Could not connect to database');
	}	
}