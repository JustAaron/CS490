<?php
session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
require('account.php');
$db = mysqli_connect($hostname, $username, $password, $project);
if(mysqli_connect_errno()){
  exit();
}
function write_file($max_recipe_id)
{
  global $db;
  $recipe_username = $_SESSION["username"];
  $recipe_image = "images/" . basename($_FILES["file"]["name"]);
  move_uploaded_file($_FILES["file"]["tmp_name"], $recipe_image);
  if(!copy("recipepage.php", $recipe_username . "/" . $max_recipe_id . ".php"))
  {
    echo("copy file error");
    return;
  }
}
function insert_recipe_database($max_recipe_id)
{
  global $db;
  $recipe_image = "images/" . basename($_FILES["file"]["name"]);
  $recipe_title = $_POST["Title"];
  $recipe_ingredients = $_POST["Ingredients"];
  $recipe_steps = $_POST["Steps"];
  $recipe_uid = $_SESSION["uid"];
  $add_recipe = "insert into Recipes(RecipeID, Title, Ingredients, Steps, ImagePath, UID) values('$max_recipe_id', '$recipe_title', '$recipe_ingredients', '$recipe_steps', '$recipe_image', '$recipe_uid')";
  $result = mysqli_query($db, $add_recipe);
  if(!$result)
  {
    echo("error");
    return false;
  }
  return true;
}
function insert_tags_database($max_recipe_id, $max_recipetags_id)
{
  global $db;
  $tags = array_unique($_POST["selectTags"]);
  foreach($tags as $selectedTag)
  {
    echo($selectedTag);
    $max_recipetags_id = $max_recipetags_id + 1;
    $tag_id = get_tag_id($selectedTag);
    $add_tags = "insert into RecipeTags(RecipeTagsID, RecipeID, TagID) values ('$max_recipetags_id', '$max_recipe_id', '$tag_id')";
    $result = mysqli_query($db, $add_tags);
    if(!$result){
      echo("error");
      return false;
    }
  }
  return true;
}

function get_tag_id($selectedTag)
{
  global $db;
  $get_tag = "select TagID as tag_id from Tags where Tag='$selectedTag'";
  $result = mysqli_query($db, $get_tag) or die(mysqli_error($db));
  $row = mysqli_fetch_assoc($result);
  $tagID = $row["tag_id"];
  return $tagID;
}
function get_max_recipe_id()
{
  global $db;
  $query = "SELECT MAX(RecipeID) as max_rid FROM Recipes";
  $result = mysqli_query($db, $query);
  if($result && mysqli_num_rows($result) == 1)
  {
    $row = mysqli_fetch_assoc($result);
    if($row["max_rid"] == null)
    {
      return 0;
    }
    $max = $row["max_rid"];
    return $max;
  }
  return -1;
}

function get_max_recipetags_id()
{
  global $db;
  $query = "SELECT MAX(RecipeTagsID) as max_rtid FROM RecipeTags";
  $result = mysqli_query($db, $query);
  if($result && mysqli_num_rows($result) == 1)
  {
    $row = mysqli_fetch_assoc($result);
    if($row["max_rtid"] == null)
    {
      return 0;
    }
    $max = $row["max_rtid"];
    return $max+1;
  }
  return -1;
}
function main()
{
  $max_id = get_max_recipe_id();
  $max_recipetags_id = get_max_recipetags_id();
  if($max_id == -1)
  {
    echo("error");
    return;
  }
  if(insert_recipe_database($max_id+1))
  {
    if(insert_tags_database($max_id+1, $max_recipetags_id))
    {
      write_file($max_id+1);
    }
  }
  $_POST = array();
}
main();
mysqli_close($db);
?>
