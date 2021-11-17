<?php
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="styles.css">
	<title>CS490 Project Login</title>
</head>

<body>
	<h1>Login Page</h1>

	<?php
	/*****************************************************************************************************************************************************/
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

	//Just reads text from the input fields.
	if(!isset($_POST["username"]) || !isset($_POST["password"])){
		exit("post variables not set");
	}
	$client_username = $_POST["username"];
	$client_password = $_POST["password"];
	/*Checks to see if any entries in the database correspond to the input username and password. If not, it simply returns false, signifying an invalid login.*/
	function valid_login($username, $db)
	{
	  $statement = "select * from alpha_users where username='$username'";
	  ($t=mysqli_query($db, $statement)) or die(mysqli_error($db));
	  $valid = mysqli_num_rows($t);
	  if($valid==0) { return false; }
	  else { return true; }
	}
	/* Veriifies the user password by comparing the hash in the database to the input password from the user. Uses built-in PHP hash functions.*/
	function verify_password($username, $password, $db)
	{
		$statement = "select password from alpha_users where username='$username'";
		$query = mysqli_query($db, $statement);
		$stored_hash = mysqli_fetch_assoc($query);
		$stored_password = $stored_hash['password'];

		if(password_verify($password, $stored_password)) { return true; }
		else { return false; }
	}
	/* A series of checks to make sure that the users Username and Password are valid. */
	if(!valid_login($client_username, $db)) {
		echo("<p>Invalid Login.</p>");
		return_to_login("Return to login");
	}

	else if(!verify_password($client_username, $client_password, $db)) {
		echo("<p>Invalid Login.</p>");
		return_to_login("Return to login");
	}

  /* Checks to see if the user is marked as an administrator or a normal user in the database. It then gives a different output depending. */
	else
	{
		$statement = "select status from alpha_users where username='$client_username'";
		($query = mysqli_query($db, $statement)) or die(mysqli_error($db));
		$status = mysqli_fetch_assoc($query);
		$admin = $status['status'];

		$statement2 = "select uid from alpha_users where username='$client_username'";
		($query2 = mysqli_query($db, $statement2)) or die(mysqli_error($db));
		$uid = mysqli_fetch_assoc($query2);
		$_SESSION["uid"] = $uid["uid"];
		if($admin == 1)
		{
			echo("<p>Welcome Administrator. You have successfully logged in!</p>");
			$_SESSION["username"] = $client_username;
			$_SESSION["password"] = $client_password;
			$_SESSION["logged"] = true;
			$_SESSION["is_admin"] = true;
			header("refresh:0, url=adminpage.php");
		}
		else if($admin == 0) {
			echo('<p>Welcome, ' . $client_username . '. You have successfully logged in.</p>');
			$_SESSION["username"] = $client_username;
			$_SESSION["password"] = $client_password;
			$_SESSION["logged"] = true;
			$_SESSION["is_admin"] = false;
			//echo("session variables set");
			//echo($client_username . $client_password);
			//echo($_SESSION["username"] . $_SESSION["password"] . "<br>" . $_SESSION["logged"]);
			header("refresh:0, url=" . $client_username . '/' . $client_username . ".php");
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
		//session_unset();
		//session_destroy();
		echo("
		<form action=\"login.html\" method=\"post\">
			<input type=\"submit\" value=\"$val\" />
		</form>");
	}
	?>

</body>
</html>
