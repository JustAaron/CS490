<?php
	/*
	Functionality: Reject friend request by setting the isActive attribute to 0
	Input:
	In $_SESSION[], "uid" should be the uid of the logged-in user
	In $_POST[], "other" should be the username of the rejected user
	Output:
	If the query was successful: echo "successfully rejected"
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
		$rejecter_uid = $_SESSION['uid'];
		$rejected_username = $_POST['other'];
		$rejected_uid = getUserID($rejected_username);
		if($rejected_uid == -1){
			echo('user not found');
		}
		$query = 'DELETE FROM FriendRequests WHERE ReceiverID=' . $rejecter_uid . ' AND SenderID=' . $rejected_uid . ';';
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo('database error');
			return;
		}
		echo('successfully rejected');
	}*/
	
	function updateDatabase(){
		global $conn;
		$rejecter_uid = $_SESSION['uid'];
		$rejected_username = $_POST['other'];
		$rejected_uid = getUserID($rejected_username);
		if($rejected_uid == -1){
			echo('user not found');
		}
		$query = 'UPDATE FriendRequests SET isActive=0 WHERE ReceiverID=' . $rejecter_uid . ' AND SenderID=' . $rejected_uid . ';';
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo('database error');
			return;
		}
		echo('successfully rejected');
	}
	
	function main(){
		//deleteDatabase();
		updateDatabase();
	}
	main();
	mysqli_close($conn);
?>
