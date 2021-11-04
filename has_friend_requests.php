<?php
	/*
	Functionality: Checks if there are pending friend requests for the logged-in user
	Input: 
	In $_SESSION[], "uid" should be the uid of the logged-in user
	Output:
	If there are pending friend requests: echo "true"
	If there are no pending friend requests: echo "false"
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
	
	function selectDatabase($client_uid){
		global $conn;
		$query = 'SELECT 1 FROM FriendRequests WHERE ReceiverID=' . $client_uid . ';';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_fetch_row($result)){
			echo('true');
			return;
		}
		echo('false');
	}

	function main(){
		$client_uid = $_SESSION['uid'];
		selectDatabase($client_uid);
	}
	main();
	mysqli_close($conn);
?>
