<?php

require 'database.php';
require 'loginPHPcheck.php'; // does session_start() and gives the variable (bool) $loggedin
// no need csrf check cos not priviledged access
header("Content-Type: application/json");

if (!$loggedin){
	echo json_encode(array(
			"success" => false,
			"message" => "Please Log in "
			));
	exit();
}
else if (isset($_POST['event_id'])&& isset($_SESSION['user_id'])){
		require 'csrfcheck.php';
		$event_id = $_POST['event_id'];	
		$user_id = $_SESSION['user_id'];
		$stmt = $mysqli->prepare("DELETE FROM events where event_id = ? and user_id = ?");
		if(!$stmt){
			printf("Delete Event Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->bind_param('ii', $event_id, $user_id);
		$stmt->execute();
		$stmt->close();
		echo json_encode(array(
				"success" => true,
				"message" => "Success!"
				));
		
		exit();
	}
else{
	echo json_encode(array(
				"success" => false,
				"message" => "No!"
				));
}
exit();
?>