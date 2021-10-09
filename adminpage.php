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
  </tr>";
  while($posts_info = mysqli_fetch_array($get_posts_result))
  {
    $post_subject = $posts_info["post_subject"];
    $post_body = $posts_info["post_body"];

    $display .="
    <tr>
    <td><strong> $post_subject</strong></td>
    <td align=center>$post_body</td>
    </tr>";
  }
  $display .= "</table>";
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
  </div>
  <h1>List of Posts</h1>
  <?php print $display ?>
</body>
</html>
