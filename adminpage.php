<?php
session_start();

function returnToLogin(){
	session_unset();
	session_destroy();
	header("refresh:0, url=login.html");
	exit();
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
elseif(mysqli_num_rows($get_posts_result) > 0)
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
	
	$post_id = $posts_info['post_id'];  // added
	
    $display .="
    <tr>
	<td><strong><p>$post_subject</p></strong></td>
    <td><strong><p>$post_body</p></strong></td>
    <form method='post'>
		<input type='hidden' name='post_id' value='$post_id'>
		<td><input type='submit' value='BLOCK'></td>
    </form>
    </tr>";
  }
  $display .= "</table>";

  function block_post_page(){  // added by Aaron
    global $db;
	$post_id = $_POST['post_id'];
	$uid_query = 'SELECT a.username, p.post_id FROM alpha_users a, posts p WHERE p.post_id=' . $post_id . ' AND a.uid=p.uid;';
	$result = mysqli_query($db, $uid_query);
	if($result){
		$row = mysqli_fetch_assoc($result);
		$username = $row['username'];
		$post_id = $row['post_id'];
		if(unlink('./' . $username . '/' . $post_id . '.php')){
			echo('<br>post page deleted');
		}
		else{
			die('<br>error while deleting post page');
		}
	}
	else{
		die(mysqli_error($db));
	}
  }
  
  function isPostExist(){  // added by Aaron
	  global $db;
	  $post_id = $_POST['post_id'];
	  $query = 'SELECT * FROM posts WHERE post_id=' . $post_id . ';';
	  $result = mysqli_query($db, $query);
	  return ($result && mysqli_num_rows($result) > 0);
  }
  
  /*function block_posts($db)
  {
    echo("<br>The post subject is:" . $_POST["post_subject"]);
    echo("<br>Executing Function...");
    $PostSubject = $_POST["post_subject"];
    echo("<br>" . $PostSubject);
    $query = "delete from posts where post_subject='$PostSubject'";
    $result = mysqli_query($db, $query) or die(mysqli_error($db));
    echo("Post successfully deleted.");
  }*/
  
  function block_posts($db)
  {
	  global $db;
	  $post_id = $_POST['post_id'];
	  $query = 'DELETE FROM posts WHERE post_id=' . $post_id . ';';
	  $result = mysqli_query($db, $query);
	  if(!$result){
		  die('<br>error while deleting from database:' . mysqli_error($db));
	  }
	  echo('<br>Post successfully deleted.');
  }
  
  if(isset($_POST["post_id"]))
  {
    echo("<br>Post request received.");
	if(isPostExist()){
		block_post_page();  // added
		block_posts($db);
		$_POST = array();  // added
	}
	else{
		echo('<br>Post does not exist');
	}
  }
}
else
{
	echo('<br>Database error');
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
