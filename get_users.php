<?php
	/*
	Functionality: Echoes a json-encoded string of all users (excludes admins), except for the currently logged in user. 
	Input: 
	In $_SESSION[], "username" should be the username of the logged in user.
	Output:
	If there are other users: echo a json-encoded string of their usernames.
	Example: 
	["user1","user2"]
	If the currently logged in user is the only user in the database: echo "no other users"
	If there is a database error: echo "database error"
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
	function main(){
		global $conn;
		$session_username = $_SESSION['username'];
		$query = 'SELECT * FROM alpha_users WHERE username !="' . $session_username . '" AND status=0;';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) > 0){
			$username_list = array();
			while($row = mysqli_fetch_assoc($result)){
				$username = $row['username'];
				array_push($username_list, $username);
			}
			$str = json_encode($username_list);
			echo($str);
		}
		elseif($result && mysqli_num_rows($result) == 0){
			echo('no other users');
		}
		else{
			echo('database error');
		}
	}
	main();
	mysqli_close($conn);
?>
