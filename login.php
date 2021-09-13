<?php
//Starts the session and connects to the database. Code can essentially be ignored for right now.
/*****************************************************************************************************************************************************/
session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);

$login_hostname = getenv('HOSTNAME');
$login_username = getenv('USERNAME');
$login_password = getenv('PASSWORD');
$login_project = getenv('PROJECT');

$db = mysqli_connect($login_hostname, $login_username, $login_password, $login_project);
if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}
print "<br>Successfully connected.<br>";
mysqli_select_db($db, $project);
/***************************************************************************************************************************************************/

//Just reads text from the input fields. Can/Should be changed depending on what field is created.
$username = $_GET["username"]
$password = $_GET["password"]
/*Checks to see if any entries in the database correspond to the input username and password. If not, it simply returns false, signifying an invalid login.
This function can eventually be put in a separate file to remove clutter, and/or improved with a hashing algorithm and input sanitization.*/
function valid_login($username, $password, $db)
{
  $statement = "select * from alpha_users where username='$username' and password='$password'";
  ($t=mysqli_query($db, $statement)) or die(mysqli_error($db));
  $valid = mysqli_num_rows($t);
  if($valid==0) { return false; }
  else { return true; }
}
/*url must point to whatever page the user is directed to after login. A check must also be added to see if the user is an Admin or not.
This check can be performed in a separate function if necessary*/
if(!valid_login($username, $password, $db))
{
  exit("Invalid Login.")
}
else
{
  header("refresh:5 ; url=[INSERT URL HERE]")
  exit();
}
?>
