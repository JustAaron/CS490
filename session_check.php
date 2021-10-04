<?php
	session_start();
	require('account.php');
	
	$conn = mysqli_connect($hostname, $username, $password, $project);
	if(mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		exit();
	}
	
	function returnToLogin(){
		// TODO: disconnected user url goes here
		// header();
		exit();
	}
	
	if(!isset($_SESSIONS['username']) || !isset($_SESSIONS['password'])){
		returnToLogin();
	}
	
	function verify_login($username){
		global $conn;
		$query = 'SELECT * FROM alpha_users WHERE username=\'' . $username . '\';';
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
	
	function verify_password($username, $password){
		global $conn;
		$query = 'SELECT * FROM alpha_users WHERE username=\'' . $uesrname . '\'';
		$result = mysqli_query($conn, $query);
		if(!$result || mysqli_num_rows($result) != 1){
			mysqli_free_result($result);
			return false;
		}
		$row = mysqli_fetch_assoc($result);
		mysqli_free_result($result);
		$hashed_password = $row['password'];
		if(password_verify($password, $hashed_password)){
			return true;
		}
		return false;
	}
	
	$client_username = $_SESSIONS['username'];
	$client_password = $_SESSIONS['password'];
	
	if(!verify_login($client_username) || !verify_password($client_username, $client_password)){
		returnToLogin();
	}
	
	exit(); // if this line is reached, everything is OK
?>
