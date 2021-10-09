<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
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
mysqli_select_db($db, $project);

$post_subject = $_POST["post_subject"];
$post_text = $_POST["post_text"];
$post_uid = $_SESSION["uid"];

$add_post = "insert into posts(post_subject, post_body, uid) values ('$post_subject', '$post_text', '$post_uid')";
($result = mysqli_query($db, $add_post)) or die(mysqli_error($db));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Userpage</title>
</head>
<body>
    <div id="banner">
            <a href="messages.php"><button id="MessageButton">Messages</button></a>
            <a href="searchpage.html"><button id="SearchButton" type="button">Search</button></a>
    </div>
    <div id="pageHeader">
        <h1>Username</h1>
    </div>
    <form method="post">
      <p><strong>Post Subject:</strong><br>
        <input type="text" id="post_subject" name="post_subject" size=40 maxlength=50>
        <p><strong>Post Text:</strong><br>
          <textarea id="post_text" name="post_text" rows=8 cols=40 wrap=virtual></textarea>
          <p><input type="submit" value="Submit"></p>
    </form>
</body>
</html>
