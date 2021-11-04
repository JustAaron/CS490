<?php
	/*
	Functionality: Echoes a json-encoded array of usernames, corresponding to users that have sent a friend request to the logged-in user
	Input:
	In $_SESSION[], "uid" should be the uid of the logged-in user
	Output:
	If the list of users was fetched successfully: echo a json-encoded string of usernames that sent a friend request
	Example: 
	["user2","user3"]
	Note: If there are no pending friend requests, the output will be [] (empty array)
	If there was a database error: echo "database error"
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
	
	function getUsername($uid){
		global $conn;
		$query = 'SELECT Username FROM alpha_users WHERE uid=' . $uid . ';';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			$username = $row['Username'];
			if($username == null){
				echo('user not found');
				return null;
			}
			return $username;
		}
		echo('database error');
		return null;
	}
	
	function selectDatabase($client_uid){
		global $conn;
		$query = 'SELECT SenderID FROM FriendRequests WHERE ReceiverID=' . $client_uid . ';';
		$result = mysqli_query($conn, $query);
		$senders = array();
		while($row = mysqli_fetch_assoc($result)){
			$sender_uid = $row['SenderID'];
			$sender_username = getUsername($sender_uid);
			if($sender_username == null){
				return;
			}
			array_push($senders, $sender_username);
		}
		$str = json_encode($senders);
		echo($str);
		return true;
	}

	function main(){
		$client_uid = $_SESSION['uid'];
		selectDatabase($client_uid);
	}
	main();
	mysqli_close($conn);
?>
