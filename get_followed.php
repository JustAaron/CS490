<?php
	/*
	Functionality: Echoes a json-encoded string of the usernames of the users that the logged-in user follows
	Input:
	In $_SESSION[], "uid" should be the uid of the logged-in user
	Output:
	If the array of users was successfully fetched: echo a json-encoded string of usernames that correspond to the followed users
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
		$query = 'SELECT Followed FROM Follows WHERE Follower=' . $client_uid . ';';
		$result = mysqli_query($conn, $query);
		$followeds = array();
		while($row = mysqli_fetch_assoc($result)){
			$followed_uid = $row['Followed'];
			$followed_username = getUsername($followed_uid);
			if($followed_username == null){
				return false;
			}
			array_push($followeds, $followed_username);
		}
		$str = json_encode($followeds);
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
