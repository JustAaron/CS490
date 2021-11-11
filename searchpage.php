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
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Page</title>
</head>
<body onload="loadElements();">
    <div id="banner">
        <form method="post">
            <input type="submit" name="LogoutButton" id="LogoutButton" value="Logout" /><br/>
        </form>
        <ul id="tabs">
        <li><a href=" <?php echo('../' . $_SESSION['username'] . '/' .$_SESSION['username'] . '.php'); ?>">Home</a></li>
        <li id="messagesPage"><a href=" <?php echo('../' . $_SESSION['username'] . '/chatpage.php'); ?>">Messages</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/favoritespage.php'); ?>">My Favorites</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/myrecipepage.php'); ?>">My Recipes</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/followingpage.php'); ?>">Following</a></li>
        <li id="friendsPage"><a href="<?php echo('../' . $_SESSION['username'] . '/friendspage.php'); ?>">Friends</a></li>
        </ul>
    </div><br>
    <h1>Search Page</h1>
    <form id="searchOptions">
        <table>
            <tr id="radioButtons">
                <td><input type="radio" id="userRadio" name="searchRadio" onclick="getForm(this);"></td>
                <td><label for="userRadio">Users</label></td>
                <td><input type="radio" id="spoonRadio" name="searchRadio" onclick="getForm(this);"><br></td>
                <td><label for="postRadio">Recipes</label></td>
                <td><input type="radio" id="recipeRadio" name="searchRadio" onclick="getForm(this);"><br></td>
                <td><label for="recipeRadio">User Recipes</label></td>
            </tr>
        </table>
        <table id="searchElements">
        </table>
    </form>
    <div id=results></div>
    <a href="spoonacular_recipe.php" id="SpoonRecipe"></a>
    <script type="text/javascript" src="../search.js"></script>

</body>
</html>
