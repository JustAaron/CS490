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

$get_posts = "select * from Recipes";
$get_posts_result = mysqli_query($db, $get_posts) or die(mysqli_error($db));
if(mysqli_num_rows($get_posts_result) == 0)
{
  $display = "<p><strong>There are no recipes</strong></p>";
}
else
{
  $display = "
  <table cellpadding=3 cellspacing=1 border=1>
  <tr>
	<th>Username</th>
  <th>Recipe Title</th>
  <th>Block Recipes</th>
  </tr>";
  while($posts_info = mysqli_fetch_array($get_posts_result))
  {
    $post_subject = $posts_info["Title"];
		$uid = $posts_info["UID"];
		$query = "SELECT a.username FROM alpha_users a WHERE a.uid='$uid'";
		$result = mysqli_query($db, $query);
		$r = mysqli_fetch_assoc($result);
		$username = $r["username"];

    $display .="
    <tr>
    <form method='post'>
		<td align=center><strong>$username</strong></td>
    <td><strong><input type='hidden' name='post_subject' value='$post_subject' >$post_subject</strong></td>
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
    $query = "delete from Recipes where Title='$PostSubject'";
    $result = mysqli_query($db, $query) or die(mysqli_error($db));
    echo("Post successfully deleted.");
  }
  if(isset($_POST["post_subject"]))
  {
    echo("<br>Post request received.");
    block_posts($db);
		header("refresh:0, url=adminpage.php");
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
    <form method="post">
      <input type="submit" name="LogoutButton" id="LogoutButton" value="Logout" /><br/>
    </form>
    <ul id="tabs">
      <li><a href="create_new_user.php">Create New User</a></li>
    </ul>
  </div>
  <h1>List of Recipes</h1>
  <?php print $display ?>
</body>
</html>
