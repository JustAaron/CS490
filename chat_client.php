<?php
	/*
	Functionality: Inserts messages into ChatMessages table using given information. Should be called whenever a user sends a message to another user. 
	The client is the user who is currently logged in. The other is the user who the client wants to chat with.
	Input: 
	In $_POST[]: "other" should be the username of the other; "message" should be the message to be sent from client to other. Note: "message" should be <= 192 characters
	In $_SESSIONS[], "username" should be the username of the client. Note: this file does not check the $_SESSION[] "password".
	Output:
	If message is successfully sent: echo "values inserted"
	if message is empty: echo "empty message" and do not insert. 
	If message was not sent because of a non-existent client or non-existent other: echo "user not found"
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
	
	function getMaxMessageID(){
		echo('enter maxmessageid');
		global $conn;
		$query = 'SELECT MAX(MessageID) as max_message_id FROM ChatMessages;';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			$max = $row['max_message_id'];
			if($max == null){
				return 0;
			}
			return $max;
		}
		return -1;
	}
	
	function insertDatabase($max_message_id){
		global $conn;
		$client_username = $_SESSION['username'];
		$other_username = $_POST['other'];
		$message = $_POST['message'];
		if($message == ''){
			echo('empty message');
			return;
		}
		if(strlen($message) > 192){
			echo('long message');
			return;
		}
		$query = 'SELECT * FROM alpha_users WHERE username="' . $client_username . '";';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) > 0){
			$row = mysqli_fetch_assoc($result);
			$client_id = $row['uid'];
			//echo($client_id);
		}
		else{
			echo('user not found');
			return;
		}
		$query = 'SELECT * FROM alpha_users WHERE username="' . $other_username . '";';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) > 0){
			$row = mysqli_fetch_assoc($result);
			$other_id = $row['uid'];
			//echo($other_id);
		}
		else{
			echo('user not found');
			return;
		}
		$query = 'INSERT INTO ChatMessages (MessageID, SenderID, ReceiverID, Message) VALUES (' . $max_message_id . ',' . $client_id . ', ' . $other_id . ', "' . $message . '");';
		//echo($query);
		$result = mysqli_query($conn, $query);
		if($result){
			echo('values inserted');
		}
		else{
			echo('error while inserting values');
		}
	}
	
	function main(){
		echo('enter main()');
		$max_message_id = getMaxMessageID();
		if($max_message_id == -1){
			echo('error while getting max_message_id');
			return;
		}
		//echo($max_message_id);
		insertDatabase($max_message_id+1);
	}
	main();
	mysqli_close($conn);
?>
