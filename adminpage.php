<?php
session_start();

function returnToLogin(){
	session_unset();
	session_destroy();
	header("refresh:0, url=login.html");
	exit();
}
if(array_key_exists('LogoutButton',$_POST)){
  returntoLogin();
}
if(!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']){
	returnToLogin();
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

$get_posts = "select * from posts";
$get_posts_result = mysqli_query($db, $get_posts) or die(mysqli_error($db));
if(mysqli_num_rows($get_posts_result) == 0)
{
  $display = "<p><strong>There are no posts</strong></p>";
}
else
{
  $display = "
  <table cellpadding=3 cellspacing=1 border=1>
  <tr>
  <th>Post Title</th>
  <th>Post Text</th>
  <th>Block Posts</th>
  </tr>";
  while($posts_info = mysqli_fetch_array($get_posts_result))
  {
    $post_subject = $posts_info["post_subject"];
    $post_body = $posts_info["post_body"];

    $display .="
    <tr>
    <form method='post'>
    <td><strong><input type='hidden' name='post_subject' value='$post_subject' >$post_subject</strong></td>
    <td align=center><strong>$post_body</strong></td>
    <td><input type='submit' value='BLOCK'></td>
    </form>
    </tr>";
  }
  $display .= "</table>";

  function block_posts($db)
  {
    echo("<br>The post subject is:" . $_POST["post_subject"]);
    echo("<br>Executing Function...");
    $PostSubject = $_POST["post_subject"];
    echo("<br>" . $PostSubject);
    $query = "delete from posts where post_subject='$PostSubject'";
    $result = mysqli_query($db, $query) or die(mysqli_error($db));
    echo("Post successfully deleted.");
  }
  if(isset($_POST["post_subject"]))
  {
    echo("<br>Post request received.");
    block_posts($db);
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adminpage</title>
</head>
<body>
  <div id="banner">
          <a href="create_new_user.php"><button id="NewUserButton">Create New User</button></a>
          <form method="post">
            <input type="submit" name="LogoutButton" id="LogoutButton" value="Logout" /><br/>
          </form>
  </div>
  <h1>List of Posts</h1>
  <?php print $display ?>
</body>
</html>
