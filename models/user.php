<?php

/**
 * Create a new user using a sort of counter with a cookie
 *
 * First we will get the hightest user_key column value from user table.
 *
 * If there is a record found, the value will be incremented and
 * added to the user table as a new row, for a new user.
 * Next, store the value in a cookie to identity the users
 * using the value from data base.
 *
 * If there is no record found, then this is the first user,
 * the first record in users table, so the value will be 1.
 * Next, store the value in a cookie to identity the users
 * using the value from data base.
 *
 * Repet this porcess for every new visitator that will
 * atuomtically become a user.
 *
 * @param  object $conn
 */
function createUser($conn)
{
    //select the highest value for the user_key column
	$result = query("SELECT MAX(user_key) AS max_user_key FROM users", [], $conn);

	// grab all the results
	$row = $result->fetch();

	//check if the specified record is found
	if ($row['max_user_key']) {

		//increment this value by one and store it in a new variable
		$new_user_key = $row['max_user_key'] + 1;

		//insert the new value into the users table
		$result = query("INSERT INTO users (user_key) VALUES (:user_key)",
			            ['user_key' => $new_user_key],
			            $conn);

		//verify if the new record has been inserted
		if ($result) {

			//create a new cookie
			setcookie(config('cookie/cookie_name'),
				      $new_user_key,
				      config('cookie/cookie_expiry'),
				      '/',
				      null,
				      null,
				      true
				      );
		}

	} else {

		//insert the first record in users table
		$result = query("INSERT INTO users (user_key) VALUES (:user_key)",
			            ['user_key' => 1],
			            $conn);

		//verify if the new record has been inserted
		if ($result) {

			//create a new cookie
			setcookie(config('cookie/cookie_name'),
				      1,
				      config('cookie/cookie_expiry'),
				      '/',
				      null,
				      null,
				      true
				      );
	    }
	}
}

function getProfilePicture($user_key) {
	$sql = "SELECT profile_path FROM `users` WHERE `user_key`=:user_key";
	$input = array(
		"user_key" => $user_key,
	);

	$result = read_query($sql, $input);
	if (!empty($result)) {
		return('resources/upload/' . $result[0]['profile_path']);
	}
	return('');
}

function getProfileName($user_key) {
	$sql = "SELECT name FROM `users` WHERE `user_key`=:user_key";
	$input = array(
		"user_key" => $user_key,
	);

	$result = read_query($sql, $input);
	if (!empty($result)) {
		return($result[0]['name']);
	}
	return('');
}

function addProfile($file, $profileName, $user_key) {
	$fileName = $file['name'];
	$fileTempLocation = $file['tmp_name'];
	$fileSize = $file['size'];

	if ($fileSize > 1) {
		// We have a real file
		$result = move_uploaded_file($fileTempLocation, 'resources/upload/' . $fileName);
		$sql = "UPDATE `users` SET `profile_path`=:picture, name=:name WHERE user_key=:user_key";
		$input = array(
			"user_key" => $user_key,
			"name" => $profileName,
			"picture" => $fileName,
		);
		query($sql, $input, connect());
	}
}
