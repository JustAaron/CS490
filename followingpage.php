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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../styles.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Following</title>
</head>
<body onload="loadFollowingRecipes()">
    <div id="banner">
        <form method="post">
            <label for="LogoutButton">Welcome, <?php echo $_SESSION['username']?></label>
            <input type="submit" name="LogoutButton" id="LogoutButton" value="Logout" /><br/>
        </form>
        <ul id="tabs">
        <li><a href=" <?php echo('../' . $_SESSION['username'] . '/' .$_SESSION['username'] . '.php'); ?>">Home</a></li>
        <li><a href=" <?php echo('../' . $_SESSION['username'] . '/chatpage.php'); ?>">Messages</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/favoritespage.php'); ?>">My Favorites</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/myrecipepage.php'); ?>">My Recipes</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/friendspage.php'); ?>">Friends</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/searchpage.php'); ?>">Search</a></li>
        </ul>
    </div><br>
    <h1>Following</h1>
    <div id="followingPageFeed">
			<div id="followingRecipePageFeed" class="feedPagedisplayRecipe">
			</div>
	</div>
    <script type="text/javascript" src="../followrecipes.js"></script>
</body>
</html>