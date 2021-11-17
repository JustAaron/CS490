<?php
session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
require('account.php');

$db = mysqli_connect($hostname, $username, $password, $project);
if(mysqli_connect_errno())
{
  exit();
}

function delete()
{
  global $db;
  global $recipe_id;
  $uid = $_SESSION["uid"];
  $recipe_id = $_POST["RecipeID"];
  $query = "DELETE fr.* FROM FavoriteRecipes fr WHERE fr.uid='$uid' AND fr.RecipeID='$recipe_id'";
  $result = mysqli_query($db, $query);
  if(!$result)
  {
    echo "error";
    return;
  }
  echo "success";
}
delete();
?>
