<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="styles.css">
	<title>CS490 Project Login</title>
</head>

<body>
	<h1>Temporary header</h1>

	<?php
	//Starts the session and connects to the database. Code can essentially be ignored for right now.
	/*****************************************************************************************************************************************************/
	session_start();
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 1);
	require("account.php");

	$db = mysqli_connect($hostname, $username, $password, $project);
	if (mysqli_connect_errno())
	{
		echo("Failed to connect to MySQL: " . mysqli_connect_error());
		exit();
	}
	//echo("<br>Successfully connected.<br>");
	mysqli_select_db($db, $project);
	/***************************************************************************************************************************************************/

	//Just reads text from the input fields. Can/Should be changed depending on what field is created.
	$username = $_POST["username"];
	$password = $_POST["password"];
	/*Checks to see if any entries in the database correspond to the input username and password. If not, it simply returns false, signifying an invalid login.
	This function can eventually be put in a separate file to remove clutter, and/or improved with a hashing algorithm and input sanitization.*/
	function valid_login($username, $db)
	{
	  $statement = "select * from alpha_users where username='$username'";
	  ($t=mysqli_query($db, $statement)) or die(mysqli_error($db));
	  $valid = mysqli_num_rows($t);
	  if($valid==0) { return false; }
	  else { return true; }
	}
	/*url must point to whatever page the user is directed to after login. A check must also be added to see if the user is an Admin or not.
	This check can be performed in a separate function if necessary*/
	function verify_password($username, $password, $db)
	{
		$statement = "select password from alpha_users where username='$username'";
		$query = mysqli_query($db, $statement);
		$stored_hash = mysqli_fetch_assoc($query);
		$stored_password = $stored_hash['password'];

		if(password_verify($password, $stored_password)) { return true; }
		else { return false; }
	}
	if(!valid_login($username, $db)) {
		echo("<p>Invalid Login.</p>");
		return_to_login("Return to login");
	}

	else if(!verify_password($username, $password, $db)) {
		echo("<p>Invalid Login.</p>");
		return_to_login("Return to login");
	}

	else
	{
		$statement = "select status from alpha_users where username='$username'";
		$query = mysqli_query($db, $statement);
		$status = mysqli_fetch_assoc($query);
		$admin = $status['status'];
		if($admin == 1) {
			echo("<p>Welcome Administrator. You have successfully logged in!</p>");
		}
		else if($admin == 0) {
			echo("<p>Welcome, painfully ordinary user. You have successfully logged in.</p>");
		}
		else {
			echo("<p>Unexpected user account type</p><br><p>exiting...</p>");
			exit();
		}
		return_to_login("Logout");
	}
	
	// echoes the return to login button. $val should be the button's text.
	function return_to_login($val)
	{
		echo("
		<form action=\"login.html\" method=\"post\">
			<input type=\"submit\" value=\"$val\" />
		</form>");
	}
	
	/*<form action="login.html" method="post">
		<input type="submit" value="Logout" />
	</form>*/
	?>

</body>
</html>
