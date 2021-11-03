<?php
	/*
	Functionality: Enables users to follow others (by inserting into Follows table)
	Input: 
	In $_SESSION[], "uid" should be the uid of the logged-in user
	In $_POST[], "other" should be the username that the logged-in user wants to follow
	Output: 
	If the database insert was successful: echo "successfully followed"
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
	
	function usernameToUid($username){
		global $conn;
		$query = 'SELECT * FROM alpha_users WHERE username="' . $username . '";';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			$uid = $row['uid'];
			return $uid;
		}
		return -1;  // error value
	}
	
	function getMaxFollowID(){
		global $conn;
		$query = 'SELECT MAX(FollowID) as max_fid FROM Follows;';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			$max = $row['max_fid'];
			if($max == null){
				return 0;
			}
			return $max;
		}
		return -1;  // error value
	}
	
	function insertDatabase($followid, $client_uid, $other_uid){
		global $conn;
		$query = 'INSERT INTO Follows (FollowID, Follower, Followed) VALUES (' . $followid . ',' . $client_uid . ',' . $other_uid . ');';
		$result = mysqli_query($conn, $query);
		if($result){
			return 0;
		}
		return -1;  // error value
	}
	
	function main(){
		$client_uid = $_SESSION['uid'];
		$other_username = $_POST['other'];
		$other_uid = usernameToUid($other_username);
		$max_followid = getMaxFollowID();
		if($max_followid == -1){
			echo('database error');
			return;
		}
		if(insertDatabase($max_followid+1, $client_uid, $other_uid) == -1){
			echo('database error');
			return;
		}
		echo('successfully followed');
	}
	main();
	mysqli_close($conn);
?>