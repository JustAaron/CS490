<?php
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
echo("<br>Successfully connect to MySQL");
mysqli_select_db($db, $project);

$post_subject = $_POST["post_subject"];
$post_text = $_POST["post_text"];
$post_uid = $_SESSION["uid"];
echo("<br>Successfully got values for SQL statement<br>");

$add_post = "insert into posts(post_subject, post_body, uid) values ('$post_subject', '$post_text', '$post_uid')";
($result = mysqli_query($db, $add_post)) or die(mysqli_error($db));
echo("<br> Successfully added post.");
?>
