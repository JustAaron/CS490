<?php
	/*
	Functionality: Check if the logged-in user is following another specified user
	Input:
	In $_SESSION[], "uid" is the uid of the logged-in user
	In $_POST[], "other" is the username of the other user to check
	Output:
	If logged-in user is following "other": echo "true"
	else: echo "false"
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
		$query = 'SELECT 1 FROM Follows WHERE Follower=' . $client_uid . ' AND Followed=' . $other_uid . ';';
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
