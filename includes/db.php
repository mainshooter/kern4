<?php


/**
 * Connect to database
 * 
 * @return mixed
 */
function connect()
{
	try {
		$conn = new PDO('mysql:host=' . config('database/host') . 
			            ';dbname=' . config('database/dbname'),
			            config('database/username'),
			            config('database/password'));
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $conn;
	} catch (Exception $e) {
		return false;
	}
}


/**
 * Query the database using prepared statments
 * 
 * @param  string $query
 * @param  array $bindings
 * @param  object $conn 
 * @return mixed
 */
function query($query, $bindings, $conn)
{
	$stmt = $conn->prepare($query);

	$stmt->execute($bindings);

	$affected_rows = $stmt->rowCount();

	return ($affected_rows > 0) ? $stmt : false;
}


/**
 * Get all the results from a table
 * 
 * @param  string $table
 * @param  object $conn 
 * @return mixed
 */
function get($sql, $conn)
{
	try {
		$result = $conn->query($sql);

		return ($result->rowCount() > 0) ? $result : false;

	} catch(Exception $e) {
		return false;
	}
}
