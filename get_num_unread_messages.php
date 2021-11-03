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
	
	function selectCountDatabase($client_uid, $other_uid){
		global $conn;
		$query = 'SELECT Count(MessageID) as count_mid FROM ChatMessages WHERE SenderID=' . $other_uid . ' AND ReceiverID=' . $client_uid . ' AND isRead=1;';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			$count = $row['count_mid'];
			if($count == null){
				echo('0');
			}
			echo($count);
			return 0;
		}
		return -1;  // error value
	}
	
	function main(){
		$client_uid = $_SESSION['uid'];
		$other_username = $_POST['other'];
		$other_uid = usernameToUid($other_username);
		selectCountDatabase($client_uid, $other_uid);
	}
	main();
	mysqli_close($conn);
?>