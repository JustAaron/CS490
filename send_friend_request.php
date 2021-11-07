<?php
	/*
	Functionality: Allows a user to send a friend request by inserting a row into the friend request table. 
	Input:
	In $_SESSION[], "uid" should be the uid of the logged-in user
	In $_POST[], "other" should be the username of the user to send a friend request to
	Output:
	If the friend request is successfully sent: echo "successfully sent"
	If the friend request failed because the "other" user was not found: echo "user not found"
	If the friend request failed because of a database error: echo "database error"
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

	function getMaxRequestID(){
		global $conn;
		$query = 'SELECT MAX(FriendRequestID) as max_rid FROM FriendRequests;';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			if($row['max_rid'] == null){
				return 0;
			}
			$max = $row['max_rid'];
			return $max;
		}
		return -1;  // error value
	}
	
	function getUserID($username){
		global $conn;
		$query = 'SELECT uid FROM alpha_users WHERE username="' . $username . '";';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			return $row['uid'];
		}
		return -1;  // error value
	}
	
	function insertDatabase($max_request_id){
		global $conn;
		$sender_id = $_SESSION['uid'];
		$receiver_username = $_POST['other'];
		$receiver_id = getUserID($receiver_username);
		if($receiver_id == -1){
			echo('user not found');
		}
		$query = 'INSERT INTO FriendRequests (FriendRequestID, SenderID, ReceiverID) VALUES (' . $max_request_id . ', ' . $sender_id . ', ' . $receiver_id . ');';
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo('database error');
			return;
		}
		echo('successfully sent');
	}
	
	function main(){
		$max_request_id = getMaxRequestID();
		if($max_request_id == -1){
			echo('database error');
			return;
		}
		insertDatabase($max_request_id+1);
	}
	main();
	mysqli_close($conn);
?>
