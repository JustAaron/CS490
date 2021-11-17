<?php
session_start();

function returnToLogin(){
	session_unset();
	session_destroy();
	header("refresh:0, url=../login.html");
	exit();
}
if(array_key_exists('LogoutButton',$_POST)){
	returntoLogin();
}
if(!isset($_SESSION["logged"]))
{
	returnToLogin();
}

if(!isset($_SESSION['username']) || $_SESSION['username'] != basename(__FILE__, '.php')){
	$isOther = true;
}
else{
	$isOther = false;
}

/*if($_SERVER['REQUEST_METHOD'] == 'POST') {
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 1);
	require("../account.php");

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
}*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../styles.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo(basename(__FILE__, '.php')); ?></title>
</head>
<body onload="loadPage()">
	<div id="banner">
        <form method="post">
            <label for="LogoutButton">Welcome, <?php echo $_SESSION['username']?></label>
            <input type="submit" name="LogoutButton" id="LogoutButton" value="Logout" /><br/>
        </form>
       <ul id="tabs">
        <li id="messagesPage"><a href=" <?php echo('../' . $_SESSION['username'] . '/chatpage.php'); ?>">Messages</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/favoritespage.php'); ?>">My Favorites</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/myrecipepage.php'); ?>">My Recipes</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/followingpage.php'); ?>">Following</a></li>
		    <li id="friendsPage"><a href="<?php echo('../' . $_SESSION['username'] . '/friendspage.php'); ?>">Friends</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/searchpage.php'); ?>">Search</a></li>
        </ul>
    </div><br>
    <!--
    <div class="otherUserOptions">
        <button class="otherUserButton" id="addFriend">Add Friend</button>
        <button class="otherUserButton" id="follow">+Follow</button>
    </div>-->
	<!--
	<div id="recipeFeeds">
		<div id="ownRecipeFeed">
		</div>
		<div id="followingRecipeFeed">
		</div>
		<div id="favoritesFeed">
		</div>
	</div>
	-->
	<?php
		$post_form_html ='
		<div id="createNewRecipe"><a href="../create_new_recipe.php"><button id="newRecipeButton">+Create New Recipe</button></a></div>
		<div id="recipeFeeds">
		<div id="ownRecipeFeed">
		<p class="feedTitle">My Recipes</p>
			<div id="myRecipeCol" class="displayRecipe">
			</div>
      <a class = "seeAll" href="' . '../' . $_SESSION['username'] . '/myrecipepage.php' . '">View All</a>
		</div>
		<div id="followingRecipeFeed">
			<p class="feedTitle">Following</p>
			<div id="followRecipeCol" class="displayRecipe">
			</div>
      <a class = "seeAllFollowing" href="' . '../' . $_SESSION['username'] . '/followingpage.php' . '">View All</a>
		</div>
		<div id="favoritesFeed">
			<p class="feedTitle">My Favorites</p>
			<div id="favoriteRecipeCol" class="displayRecipe">
			</div>
      <a class = "seeAll" href="' . '../' . $_SESSION['username'] . '/favoritespage.php' . '">View All</a>
		</div>
	</div>';
		if(!$isOther){
			echo($post_form_html);
		}
		else{
			echo('<div id="pageHeader">
        <h1>');
        echo(basename(__FILE__, '.php'));
        echo('</h1>
    </div><div class="otherUserOptions" id="otherUserOptions">
        <button class="otherUserButton" id="addFriend" onclick="sendFriendRequest();">Add Friend</button>
        <button class="otherUserButton" id="follow" onclick="followUser();">+Follow</button>
    </div>    
	<div id="followingPageFeed">
		<div id="otherUserFeed" class="feedPagedisplayRecipe"></div>
	</div>');
		}
	?>
	<div class="userPosts" id="userPosts"></div>
	<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
	<script type="text/javascript" src="../user.js"></script>
</body>
</html>