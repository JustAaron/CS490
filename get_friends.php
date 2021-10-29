<?php
	/*
	Functionality: Echoes a list of friends for the logged-in user. 
	Input: 
	In $_SESSION[], "uid" should be the uid of the logged-in user
	Output: 
	If there were no database errors: echo a json-encoded string of friends
	Example: 
	["user2","user3"]
	Note: The array will be blank if the user has no friends
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
	
	function get_username($uid){
		global $conn;
		$query = 'SELECT username FROM alpha_users WHERE uid=' . $uid . ';';
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo('database error');
			return '';
		}
		$row = mysqli_fetch_assoc($result);
		$username = $row['username'];
		return $username;
	}
	
	function selectDatabase(){
		global $conn;
		$client = $_SESSION['uid'];
		$friends_list = array();
		$query = 'SELECT User2ID FROM Friends WHERE User1ID=' . $client . ';';
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo('database error');
			return false;
		}
		elseif(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)){
				$friend_uid = $row['User2ID'];
				$friend_username = get_username($friend_uid);
				array_push($friends_list, $friend_username);
			}
		}
		$query = 'SELECT User1ID FROM Friends WHERE User2ID=' . $client . ';';
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo('database error');
			return false;
		}
		elseif(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)){
				$friend_uid = $row['User1ID'];
				$friend_username = get_username($friend_uid);
				array_push($friends_list, $friend_username);
			}
		}
		return $friends_list;
	}
	
	function main(){
		$friends_list = selectDatabase();
		if(!$friends_list){
			return;
		}
		$str = json_encode($friends_list);
		echo($str);
	}
	main();
	mysqli_close($conn);
?>
