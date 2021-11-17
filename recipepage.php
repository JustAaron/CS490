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
    <title>Recipe</title>
</head>
<body onload="displayRecipe()">
    <div id="banner">
        <form method="post">
            <label for="LogoutButton">Welcome, <?php echo $_SESSION['username']?></label>
            <input type="submit" name="LogoutButton" id="LogoutButton" value="Logout" /><br/>
        </form>
        <ul id="tabs">
        <li><a href=" <?php echo('../' . $_SESSION['username'] . '/' .$_SESSION['username'] . '.php'); ?>">Home</a></li>
        <li id="messagesPage"><a href=" <?php echo('../' . $_SESSION['username'] . '/chatpage.php'); ?>">Messages</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/favoritespage.php'); ?>">My Favorites</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/myrecipepage.php'); ?>">My Recipes</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/followingpage.php'); ?>">Following</a></li>
        <li id="friendsPage"><a href="<?php echo('../' . $_SESSION['username'] . '/friendspage.php'); ?>">Friends</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/searchpage.php'); ?>">Search</a></li>
        </ul>
    </div>
    <div id="recipeTitle">
    </div>
    <div id="recipePicture">
    </div>
    <div id="recipeContent">
        <div id="userRecipeIngredients">
            <p class="recipeContentTitle">Ingredients</p>
        </div>
        <div id="userRecipeSteps">
            <p class="recipeContentTitle">Steps</p>
        </div>
    </div>
    <div id="recipeButtonTab"></div>
    <div id="recipeComments">
      <p class="recipeContentTitle">Comments</p>
      <textarea class="writeComment" id="writeComment"></textarea><br>
      <button class="postComment" id="postComment" onclick="sendComment()">Send</button>
    </div>
    <script type="text/javascript" src="../recipepage.js"></script>
</body>
</html>
