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
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript">var $j = jQuery.noConflict();</script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
    <script type="text/javascript">var $c = jQuery.noConflict();</script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">var $k = jQuery.noConflict();</script>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create New Recipe</title>
</head>
<body>
    <div id="banner">
        <form method="post">
            <label for="LogoutButton">Welcome, <?php echo $_SESSION['username']?></label>
            <input type="submit" name="LogoutButton" id="LogoutButton" value="Logout" /><br/>
        </form>
        <ul id="tabs">
        <li><a href=" <?php echo('./' . $_SESSION['username'] . '/' .$_SESSION['username'] . '.php'); ?>">Home</a></li>
        <li id="messagesPage"><a href=" <?php echo('./' . $_SESSION['username'] . '/chatpage.php'); ?>">Messages</a></li>
        <li><a href="<?php echo('./' . $_SESSION['username'] . '/favoritespage.php'); ?>">My Favorites</a></li>
        <li><a href="<?php echo('./' . $_SESSION['username'] . '/myrecipepage.php'); ?>">My Recipes</a></li>
        <li><a href="<?php echo('./' . $_SESSION['username'] . '/followingpage.php'); ?>">Following</a></li>
        <li id="friendsPage"><a href="<?php echo('./' . $_SESSION['username'] . '/friendspage.php'); ?>">Friends</a></li>
        <li><a href="<?php echo('./' . $_SESSION['username'] . '/searchpage.php'); ?>">Search</a></li>
        </ul>
    </div><br>
    <h1>Create a New Recipe</h1>
  <form id="recipeForm" enctype="multipart/form-data" name="recipeForm">
    <p class="newRecipeLabels">Recipe Title:<br>
      <input type="text" id="Title" name="Title" size=40 maxlength=50></p>
    <p class="newRecipeLabels">Input your  ingredients:<br>
      <textarea id="Ingredients" name="Ingredients" rows=3 cols=75 wrap=virtual></textarea></p>
    <p class="newRecipeLabels">Input the steps to make your recipe:<br>
      <textarea id="Steps" name="Steps" rows=15 cols=75 wrap=virtual></textarea></p>
    <p class="newRecipeLabels">Select intolerances for your recipe:<br>
      <select id="selectTag" name="selectTag[]" class="selectTag" multiple>
        <option value="Vegetarian">Vegetarian</option>
        <option value="Vegan">Vegan</option>
        <option value="GlutenFree">Gluten-free</option>
        <option value="Dairy">Dairy</option>
        <option value="Peanut">Peanut</option>
        <option value="Soy">Soy</option>
        <option value="Egg">Egg</option>
        <option value="Seafood">Seafood</option>
        <option value="Sesame">Sesame</option>
        <option value="TreeNut">Tree Nut</option>
        <option value="Grain">Grain</option>
        <option value="Shellfish">Shellfish</option>
        <option value="Wheat">Wheat</option>
      </select></p>
    <p class="newRecipeLabels"><input type="file" id="image-file" name="image-file" accept="image/*"></p>
    <p><input type="submit" value="Submit" class="recipeSubmit"></p>
  </form>
  <script type="text/javascript" src="recipe.js"></script>
</body>
</html>
