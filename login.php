<?php
//Starts the session and connects to the database. Code can essentially be ignored for right now.
/*****************************************************************************************************************************************************/
session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
include("account.php");

$db = mysqli_connect($hostname, $username, $password, $project);
if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}
print "<br>Successfully connected.<br>";
mysqli_select_db($db, $project);
/***************************************************************************************************************************************************/

//Just reads text from the input fields. Can/Should be changed depending on what field is created.
$username = $_GET["user"];
$password = $_GET["password"];

function valid_login($username, $db)
{
  $statement = "select * from alpha_users where username='$username'";
  ($t=mysqli_query($db, $statement)) or die(mysqli_error($db));
  $valid = mysqli_num_rows($t);
  if($valid==0) { return false; }
  else { return true; }
}

function verify_password($username, $password, $db)
{
	$statement = "select password from alpha_users where username='$username'";
	$query = mysqli_query($db, $statement);
	$stored_hash = mysqli_fetch_assoc($query);
	$stored_password = $stored_hash['password'];

	if(password_verify($password, $stored_password)) { return true; }
	else { return false; }
}
if(!valid_login($username, $db)) { exit("Invalid Login."); }

else if(!verify_password($username, $password, $db)) { exit("Invalid Password."); }

else
{
	$statement = "select status from alpha_users where username='$username'";
	$query = mysqli_query($db, $statement);
	$status = mysqli_fetch_assoc($query);
	$admin = $status['status'];
	if($admin == 1) { exit("Welcome Administrator. You have successfully logged in!"); }
	else { exit("Welcome, painfully ordinary user. You have successfully logged in."); }
}
?>
