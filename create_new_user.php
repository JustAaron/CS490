<?php
	session_start();
	if(!isset($_SESSION["logged"]))
	{
		header("refresh:0, url=login.html");
		exit();
	}
	function returnToLogin(){
		session_unset();
		session_destroy();
		header("refresh:0, url=login.html");
		exit();
	}
	if(array_key_exists('LogoutButton',$_POST)){
	  returntoLogin();
	}
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 1);
	require("account.php");

	$conn = mysqli_connect($hostname, $username, $password, $project);
	if (mysqli_connect_errno())
	{
		echo("Failed to connect to MySQL: " . mysqli_connect_error());
		exit();
	}
	//mysqli_select_db($conn, $project);
	
	function getMaxUID(){
		global $conn;
		$query = 'SELECT MAX(uid) as max_uid FROM alpha_users;';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			$max = $row['max_uid'];
			return $max;
		}
		return -1;
	}
	
	function create_new_user($username, $password, $max_uid)
	{
		global $conn;
		$password_hash = password_hash($password, PASSWORD_DEFAULT);
		$uid = $max_uid+1;
		$insert_user = "insert into alpha_users(uid, username, password, status) values('$uid', '$username', '$password_hash', 0)";
		$result=mysqli_query($conn, $insert_user);
		if(!$result){
			echo('<br><p>' . mysqli_error($conn) . '</p>');
		}
		else{
			echo("<p>Successfully created new user in database</p>");
		}
		
		if(!is_dir($username)){
			mkdir($username);
			echo('<br><p>user dir created</p>');
		}
		else{
			echo('<br><p>user dir already exists</p>');
		}
		//echo("<br>Successfully created a new user directory");
		
		copy('userpage.php', $username . '/' . $username . '.php');
		
		echo("<br><p>Successfully created a new user profile page.</p>");
		/*rename("/afs/cad.njit.edu/u/d/l/dl388/public_html/490TEST/" . $username . ".php", "/afs/cad.njit.edu/u/d/l/dl388/public_html/490TEST/" . $username . "/" . $username . ".php");
		echo("<br>Successfully moved new user profile page into user directory");*/
		
		copy('chatpage.php', $username . '/chatpage.php');
		echo("<br><p>Successfully created chat page.</p>");
		/*rename("/afs/cad.njit.edu/u/d/l/dl388/public_html/490TEST/messages.php", "/afs/cad.njit.edu/u/d/l/dl388/public_html/490TEST/" . $username . "/" . "messages.php");
		echo("<br> Successfully moved user chat page into user directory.");*/
		
		/*copy("/afs/cad.njit.edu/u/d/l/dl388/public_html/490TEST/styles.css", "/afs/cad.njit.edu/u/d/l/dl388/public_html/490TEST/" . $username . "/styles.css");
		echo("<br>Copied styling to user directory");*/
		
		copy('searchpage.php', $username . '/searchpage.php');
		echo("<br><p>Successfully created search page.</p>");
		copy('friendspage.php', $username . '/friendspage.php');
		echo("<br><p>Successfully created friends page.</p>");
		
		copy('friendspage.php', $username . '/favoritespage.php');
		echo("<br><p>Successfully created favorites page.</p>");
		
		copy('friendspage.php', $username . '/myrecipepage.php');
		echo("<br><p>Successfully created myrecipe page.</p>");
		
		copy('friendspage.php', $username . '/followingpage.php');
		echo("<br><p>Successfully created following page.</p>");
	}
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$max_uid = getMaxUID();
		create_new_user($_POST["username"], $_POST["password"], $max_uid);
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="stylesheet" href="styles.css">
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Create User Page</title>
	</head>
	<div id="banner">
		<form method="post">
            <input type="submit" name="LogoutButton" id="LogoutButton" value="Logout" /><br/>
        </form>
		<ul id="tabs">
          <li><a href="adminpage.php">Home</a></li>
		</ul>
  </div>
	<div id="pageHeader">
		<h1>Create a New User</h1>
	</div>
	<body>
		<form method="post">
			<p><strong>Enter Username</strong><br>
			<input type="text" id="username" name="username" size=40 maxlength=50>
			<p><strong>Enter Password</strong><br>
			<input type="text" id="password" name="password" size=40 maxlenth=50>
			<p><input type="submit" value="Submit"></p>
		</form>
	</body>
</html>
