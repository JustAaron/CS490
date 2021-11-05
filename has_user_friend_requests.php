<?php
	/*
	Similar to has_friend_requests.php but only for specified "other" user
	Input:
	$_SESSION[], "uid" is logged-in user uid
	$_POST[], "other" is username to check
	Output:
	"true"/"false"
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
	
	function selectDatabase($client_uid, $other_uid){
		global $conn;
		$query = 'SELECT 1 FROM FriendRequests WHERE SenderID=' . $client_uid . ' AND ReceiverID=' . $other_uid . ';';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_fetch_row($result)){
			echo('true');
			return;
		}
		echo('false');
		return;
		
	}

	function main(){
		$client_uid = $_SESSION['uid'];
		$other_username = $_POST['other'];
		$other_uid = getUserID($other_username);
		selectDatabase($client_uid, $other_uid);
	}
	main();
	mysqli_close($conn);
?>
