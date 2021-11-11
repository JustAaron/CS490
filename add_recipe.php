<?php
session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
require('account.php');
$db = mysqli_connect($hostname, $username, $password, $project);
if(mysqli_connect_errno()){
  exit();
}
echo ("<br>Did I survive? 1");

function write_file($max_recipe_id)
{
  echo("<br>Write File is where I died.");
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
echo ("<br>Did I survive? 2");
function insert_recipe_database($max_recipe_id)
{
  echo ("<br>Recipe Database is where I died.");
  global $db;
  $recipe_image = "images/" . basename($_FILES["file"]["name"]);
  $recipe_title = $_POST["Title"];
  $recipe_ingredients = $_POST["Ingredients"];
  $recipe_steps = $_POST["Steps"];
  echo $recipe_title . "<br>" . $recipe_ingredients . "<br>" . $recipe_steps;
  $add_recipe = "insert into Recipes(RecipeID, Title, Ingredients, Steps, ImagePath) values('$max_recipe_id', '$recipe_title', '$recipe_ingredients', '$recipe_steps', '$recipe_image')";
  $result = mysqli_query($db, $add_recipe);
  if(!$result)
  {
    return false;
  }
  return true;
}
echo("<br>Did I survive? 3");
function insert_tags_database($max_recipe_id)
{
  global $db;
  echo ("<br> Tags Database is where I died.");
  foreach($_POST["selectTags"] as $selectedTag)
  {
    $tag_id = get_tag_id($selectedTag);
    $add_tags = "insert into RecipeTags(RecipeID, TagID) values ('$max_recipe_id', '$tag_id')";
    $result = mysqli_query($db, $add_tags);
    if(!$result){
      echo("database error");
      return false;
    }
  }
  return true;
}
echo("<br>Did I survive? 4");

function get_tag_id($selectedTag)
{
  echo ("<br> Getting the Tag ID is where I died.");
  global $db;
  $get_tag = "select TagID as tag_id from Tags where Tag='$selectedTag'";
  $result = mysqli_query($db, $get_tag) or die(mysqli_error($db));
  $row = mysqli_fetch_assoc($result);
  $tagID = $row["tag_id"];
  return $tagID;
}
echo("<br>Did I survive? 5");
function get_max_recipe_id()
{
  echo ("<br> Getting the Recipe ID is where I died.");
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
    var_dump($row);
    $max = $row["max_rid"];
    return $max;
  }
  return -1;
}
echo("<br>Did I survive? 6");
function main()
{
  echo ("<br>Main is where I died.");
  $max_id = get_max_recipe_id();
  if($max_id == -1)
  {
    echo("database error");
    return;
  }
  if(insert_recipe_database($max_id+1))
  {
    if(insert_tags_database($max_id+1))
    {
      write_file($max_id+1);
    }
  }
}
echo("<br>Did I survive? 7");
main();
mysqli_close($db);
echo ("<br>I survived.");
?>
