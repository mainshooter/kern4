<?php

/**
 * Create a new task
 *
 * @param  string $user_key
 * @param  string $name
 * @param  object $conn
 */
function newTask($user_key, $name, $conn)
{
	query("INSERT INTO tasks (user_key, name) VALUES (:user_key, :name)",
		  [
		      ':user_key' => $user_key,
		      'name'      => $name,
		  ], $conn);

	// redirect('index');
	global $alert;
	// create a new task
	$alert = "<style>
		.alert {animation: alertMove 1.5s;}
	</style>";
}

/**
 * Get all user's tasks
 *
 * @param  string $user_key
 * @param  string $conn
 * @return array
 */
function getTasks($user_key, $conn)
{
	$result = query("SELECT * FROM tasks WHERE user_key = :user_key ORDER BY id ASC",
		            [':user_key' => $user_key], $conn);

	if ($result) {
		$row = $result->fetchAll();

		$count_records = count($row);

		if ($count_records > 0) {
			return $row;
		}
	}

}

/**
 * Mark task as done
 *
 * @param  string $user_key
 * @param  string $task_id
 * @param  object $conn
 */
function markTaskAsDone($user_key, $task_id, $conn)
{
	//task id from url
	$task_id = (int) escape($task_id);

	// check if the id of the task is valid
	$result = query("SELECT * FROM tasks WHERE user_key = :user_key AND id = :task_id",
		            [':user_key' => $user_key, ':task_id' => $task_id], $conn);
	if ($result) {
		$row = $result->fetch();

		if ($row['id'] != $task_id) {
			redirect('index');
		}

		//update done in tasks's table for the current user, only if done = 0
		$result = query("UPDATE tasks SET done = :done WHERE id = :task_id AND done = :updone",
			            [':done' => 1, ':task_id' => $task_id, ':updone' => 0], $conn);
		if ($result) {
			redirect('index');
		}
	}
}

/**
 * Undone a task
 *
 * @param  string $user_key
 * @param  string $task_id
 * @param  object $conn
 */
function undoneTask($user_key, $task_id, $conn)
{
	// taks id from the url
	$task_id = (int) escape($task_id);

	// check if the id of the task is valid
	$result = query("SELECT * FROM tasks WHERE user_key = :user_key AND id = :task_id",
		            [':user_key' => $user_key, ':task_id' => $task_id], $conn);
	if ($result) {
		$row = $result->fetch();

		if ($row['id'] != $task_id) {
			redirect('index');
		}

		// update done in tasks's table for the current user, only if done = 1
		$result = query("UPDATE tasks SET done = :done WHERE id = :task_id AND done = :updone",
			            [':done' => 0, ':task_id' => $task_id, ':updone' => 1], $conn);
		if ($result) {
			redirect('index');
		}
	}
}

/**
 * Delete all tasks for the curent user if all tasks are done
 *
 * @param  string $user_key
 * @param  object $conn
 */
function delTasks($user_key, $conn)
{
	// grab the number of the total taks from tasks table
	$result = query("SELECT COUNT($user_key) AS total_tasks FROM tasks WHERE user_key = :user_key",
		            [':user_key' => $user_key], $conn);

	// fetch the results
	$row = $result->fetch();

	// check results if are valid
	if($row['total_tasks']) {

		//store the total number of tasks
		$total_tasks = $row['total_tasks'];

		//if there are tasks, let's check how many are done
	    $result = query("SELECT COUNT($user_key) AS total_tasks_done FROM tasks WHERE user_key = :user_key AND done = :done",
		                [':user_key' => $user_key, ':done' => 1], $conn);

		// fetch the results
	    $row = $result->fetch();

	    // check results if are valid
	    if($row['total_tasks_done']) {
	    	//store the total number of already done tasks
	    	$total_tasks_done = $row['total_tasks_done'];

	    	//let's make the math
	    	$result = $total_tasks - $total_tasks_done;

	    	// check if the $result is equal with 0, it means that there are no more taks to be done
	    	if((int) $result === 0) {

		        //delete all the tasks, because all are already done
	            query("DELETE FROM tasks WHERE user_key = :user_key ",
	            	            [':user_key' => $user_key], $conn);
	    	}
	    }
	}
}
