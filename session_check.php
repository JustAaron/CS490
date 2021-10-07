<?php
	session_start();
	require('account.php');
?>

<?php
	//echo("enter session_check");
	$conn = mysqli_connect($hostname, $username, $password, $project);
	if(mysqli_connect_errno()){
		//echo "Failed to connect to MySQL: " . mysqli_connect_error();
		exit();
	}
	/*
	Use $is_valid_session to determine whether the user-requested page or the disconnect page should be rendered.
	*/
	$is_valid_session = true;
	
	/*
	If the session is not valid, go to disconnected page.
	*/
	function disconnectUser(){
		global $is_valid_session;
		//echo("enter disconnectuser");
		session_unset();
		session_destroy();
		$is_valid_session = false;
		header("disconnect_page.php");
		exit();
	}
	
	if(!isset($_SESSION['username']) || !isset($_SESSION['password'])){
		//echo("enter first if");
		//echo($_SESSION["username"] . $_SESSION["password"]);
		disconnectUser();
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
			//echo("invalid user");
			return false;
		}
	}
	
	function verify_password($username, $password){
		global $conn;
		$query = 'SELECT * FROM alpha_users WHERE username=\'' . $username . '\'';
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
		//echo("invalid pass");
		return false;
	}
	
	$client_username = $_SESSION['username'];
	$client_password = $_SESSION['password'];
	
	if(!verify_login($client_username) || !verify_password($client_username, $client_password)){
		//echo("enter second if");
		disconnectUser();
	}
	
	//echo("everything OK");
	//exit(); // if this line is reached, everything is OK
?>
