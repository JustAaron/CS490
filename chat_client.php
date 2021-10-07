<?php
	/*
	Functionality: Inserts messages into ChatMessages table using given information. Should be called whenever a user sends a message to another user. 
	The client is the user who is currently logged in. The other is the user who the client wants to chat with.
	Input: 
	In $_POST[]: "other" should be the username of the other; "message" should be the message to be sent from client to other. Note: "message" should be <= 192 characters
	In $_SESSIONS[], "username" should be the username of the client. Note: this file does not check if the login is valid
	Output:
	If message is successfully sent: echo "values inserted"
	If message was not sent because of a non-existent other: echo "user not found"
	If message was not sent because of database error: echo "error while inserting values"
	*/
	
	session_start();
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 1);
	require('account.php');
	$conn = mysqli_connect($hostname, $username, $password, $project);
	if(mysqli_connect_errno()){
		//echo "<p>Failed to connect to MySQL: " . mysqli_connect_error(). "</p>";
		exit();
	}
	
	function main(){
		global $conn;
		$client_username = $_SESSIONS['username'];
		$other_username = $_POST['other'];
		$message = $_POST['message'];
		$query = 'SELECT * FROM alpha_users WHERE username="' . $client_username . '";';
		$result = mysqli_query($conn, $query);
		if($result){
			$client_id = mysqli_fetch_assoc($result)['uid'];
		}
		$query = 'SELECT * FROM alpha_users WHERE username="' . $other_username . '";';
		$result = mysqli_query($conn, $query);
		if($result){
			$other_id = mysqli_fetch_assoc($result)['uid'];
		}
		else{
			echo('user not found');
			exit();
		}
		$query = 'INSERT INTO ChatMessages VALUES (MAX(MessageId) + 1, ' . $client_id . ', ' , $other_id . ', "' . $message . '");';
		$result = mysqli_query($conn, $query);
		if($result){
			echo('values inserted');
		}
		else{
			echo('error while inserting values');
		}
	}
	main();
		
	mysqli_close($conn);
?>
