<?php
	/*
	Functionality: echoes a json-encoded string that contains users that match given string.
	A match is defined by %str%, meaning if the input appears anywhere in the username, it will be returned.
	The string is not case-senstive.
	Input:
	In $_POST[], "searchuser" should be the user to be searched. 
	Output:
	If there are matches: echo a json-encoded string with n elements (n=number of matches) where each element has key="username" and value=<a matched username>.
	Example:
	If $_POST['searchuser']='o'
	[{"username":"JohnA"},{"username":"George"}]
	If there are no matches: echo "no matches"
	*/
	session_start();
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 1);
	require('account.php');
	$conn = mysqli_connect($hostname, $username, $password, $project);
	if(mysqli_connect_errno()){
		echo "<p>Failed to connect to MySQL: " . mysqli_connect_error(). "</p>";
		exit();
	}
	
	function main(){
		global $conn;
		$searchuser = $_POST['searchuser'];
		$query = 'SELECT * FROM alpha_users WHERE status=0 AND username LIKE "%' . $searchuser . '%";';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) > 0){
			$user_list = array();
			while($row = mysqli_fetch_assoc($result)){
				array_push($user_list, array('username'=>$row['username']));
			}
			$str = json_encode($user_list);
			echo($str);
		}
		else{
			echo('no matches');
		}
	}
	main();
	
	mysqli_close($conn);
?>