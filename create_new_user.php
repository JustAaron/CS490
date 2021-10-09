<?php
session_start();
if(!isset($_SESSION["logged"]))
{
  header("refresh:0, url=login.html");
  exit();
}
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

function create_new_user($username, $password, $db)
{
 $password_hash = password_hash($password, PASSWORD_DEFAULT);
 $insert_user = "insert into alpha_users(username, password, status) values('$username', '$password_hash', 0)";
 $result=mysqli_query($db, $insert_user) or die(mysqli_error($db));
 echo("Successfully created new user in database");

 $new_user = fopen($username . ".php", "w");
 $text = '
 <?php
 session_start();
 if(!isset($_SESSION["logged"]))
 {
   header("refresh:0, url=login.html");
   exit();
 }

 if($_SERVER["REQUEST_METHOD"] == "POST") {
 error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
 ini_set("display_errors", 1);
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

 $add_post = "insert into posts(post_subject, post_body, uid) values (\'$post_subject\', \'$post_text\', \'$post_uid\')";
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
  ';
  fwrite($new_user, $text);
  fclose($new_user);
}
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  create_new_user($_POST["username"], $_POST["password"], $db);
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
