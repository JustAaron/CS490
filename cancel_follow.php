<?php
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
		deleteDatabase($client_uid, $other_uid);
	}
	main();
	mysqli_close($conn);
?>