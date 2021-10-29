<?php
	/*
	Functionality: Cancel a friend request by updating the FriendRequests table. 
	Input:
	In $_SESSION[], "uid" should be the uid of the logged-in user
	In $_POST[], "other" should be the username in the to-be cancelled request
	Output:
	If the friend request was successfully cancelled: echo "successfully cancelled"
	If the "other" was not found: echo "user not found"
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
	
	/*function deleteDatabase(){
		global $conn;
		$canceller_uid = $_SESSION['uid'];
		$cancelled_username = $_POST['other'];
		$cancelled_uid = getUserID($cancelled_username);
		if($cancelled_uid == -1){
			echo('user not found');
		}
		$query = 'DELETE FROM FriendRequests WHERE SenderID=' . $canceller_uid . ' AND ReceiverID=' . $cancelled_uid . ';';
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo('database error');
			return;
		}
		echo('successfully cancelled');
	}*/
	
	function updateDatabase(){
		global $conn;
		$canceller_uid = $_SESSION['uid'];
		$cancelled_username = $_POST['other'];
		$cancelled_uid = getUserID($cancelled_username);
		if($cancelled_uid == -1){
			echo('user not found');
		}
		$query = 'UPDATE FriendRequests SET isActive=0 WHERE SenderID=' . $canceller_uid . ' AND ReceiverID=' . $cancelled_uid . ';';
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo('database error');
			return;
		}
		echo('successfully cancelled');
	}
	
	function main(){
		//deleteDatabase();
		updateDatabase();
	}
	main();
	mysqli_close($conn);
?>
