<?php
	/*
	Functionality: Enables users to cancel a sent follow (by deleting the entry from the Follows table)
	Input:
	In $_SESSION[], "uid" should be the uid of the logged-in user
	In $_POST[], "other" should be the username of the user to no longer be followed
	Output:
	If the row was successfully deleted: echo "successfully unfollowed"
	If the "other" user was not found: echo "user not found"
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
	
	function deleteDatabase($client_uid, $other_uid){
		global $conn;
		$query = 'DELETE FROM Follows WHERE Follower=' . $client_uid . ' AND Followed=' . $other_uid . ';';
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
		if($other_uid == -1){
			echo('user not found');
			return;
		}
		if(deleteDatabase($client_uid, $other_uid) == 0){
			echo('successfully unfollowed');
		}
		else{
			echo('database error');
		}
	}
	main();
	mysqli_close($conn);
?>