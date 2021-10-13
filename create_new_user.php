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

 mkdir($username);
 echo("<br>Successfully created a new user directory");

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
  echo("<br>Successfully created a new user profile page.");
  rename("/afs/cad.njit.edu/u/d/l/dl388/public_html/490TEST/" . $username . ".php", "/afs/cad.njit.edu/u/d/l/dl388/public_html/490TEST/" . $username . "/" . $username . ".php");
  echo("<br>Successfully moved new user profile page into user directory");

  $new_chat_page = fopen("messages.php", "w");
  $text = '
  <?php
      session_start();
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
      <link rel="stylesheet" href="styles.css">
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Chat</title>
  </head>
  <body onload="updateListen()">
      <h4>Search User</h4>
      <div class="searchUsers" id="searchUsers">
          <textarea class="usernameSearch" id="usernameSearch"></textarea>
      </div>
      <h1 id="chatHeader">Chat</h1>
      <div class="chatWindow" id="chatWindow">
      </div>
      <div class="sendMessage" id="sendMessage">
          <textarea class="message" id="message"></textarea>
          <button class="sendButton" id="sendButton">Send</button>
      </div>
      <script type="text/javascript" src="chat.js"></script>
  </body>
  </html>';
  fwrite($new_chat_page, $text);
  fclose($new_chat_page);
  echo("<br>Successfully created chat page.");
  rename("/afs/cad.njit.edu/u/d/l/dl388/public_html/490TEST/messages.php", "/afs/cad.njit.edu/u/d/l/dl388/public_html/490TEST/" . $username . "/" . "messages.php");
  echo("<br> Successfully moved user chat page into user directory.");

  copy("/afs/cad.njit.edu/u/d/l/dl388/public_html/490TEST/styles.css", "/afs/cad.njit.edu/u/d/l/dl388/public_html/490TEST/" . $username . "/styles.css");
  echo("<br>Copied styling to user directory");
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
