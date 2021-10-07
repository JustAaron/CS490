<?php
	session_start();
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 1);
	require('account.php');
	$conn = mysqli_connect($hostname, $username, $password, $project);
	if(mysqli_connect_errno()){
		//echo "<p>Failed to connect to MySQL: " . mysqli_connect_error(). "</p>";
		exit('Error while connecting to database');
	}
	
	function verify_login(){
		global $conn;
		$query = 'SELECT * FROM alpha_users WHERE username=\'' . $_POST['username'] . '\';';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) > 0){
			mysqli_free_result($result);
			return true;
			
		}
		else{
			mysqli_free_result($result);
			return false;
		}
	}
	
	function verify_password(){
		global $conn;
		$query = 'SELECT * FROM alpha_users WHERE username=\'' . $_POST['username'] . '\'';
		$result = mysqli_query($conn, $query);
		if(!$result || mysqli_num_rows($result) != 1){
			mysqli_free_result($result);
			return false;
		}
		$row = mysqli_fetch_assoc($result);
		mysqli_free_result($result);
		$hashed_password = $row['password'];
		if(password_verify($_POST['password'], $hashed_password)){
			return true;
		}
		return false;
	}
	
	function main(){
		if(!verify_login() || !verify_password()){
			echo('Wrong credentials');
			exit();
		}
		echo('connected!');
	}
	
	main();
	
		
	mysqli_close($conn);
?>
