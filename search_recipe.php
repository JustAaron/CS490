<?php
session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
require('account.php');
$db = mysqli_connect($hostname, $username, $password, $project);
if(mysqli_connect_errno()){
  exit();
}

function main()
{
  global $db;
  $tags = $_POST["tags"];
  $title = $_POST["title"];
  $tids = get_tag_ids($tags);
  $rids = get_recipe_ids($title);
  $results = get_results($tids, $rids);
  $results = array_unique($results);
  $information = get_information($results);
  echo $information;
}

function get_tag_ids($tags)
{
  global $db;
  $tag_ids = array();
  foreach($tags as $tag)
  {
    $query = "SELECT t.TagID FROM Tags t where t.Tag='$tag'";
    $result = mysqli_query($db, $query);
    $tag_id = mysqli_fetch_assoc($result);
    array_push($tag_ids, $tag_id);
  }
  return $tag_ids;
}

function get_recipe_ids($title)
{
  global $db;
  $recipe_ids = array();
  $query = "SELECT r.RecipeID FROM Recipes r WHERE r.Title LIKE '%" . $title . "%'";
  $result = mysqli_query($db, $query);
  if($result && mysqli_num_rows($result) > 0)
  {
    while($recipe_id = mysqli_fetch_assoc($result))
    {
      array_push($recipe_ids, $recipe_id);
    }
  }
  return $recipe_ids;
}

function get_information($results)
{
  global $db;
  $recipe_information = array();
  foreach($results as $result)
  {
    $query = "SELECT r.*, a.username FROM Recipes r, alpha_users a WHERE r.RecipeID='$result' AND r.uid=a.uid";
    $r = mysqli_query($db, $query);
    $row = mysqli_fetch_assoc($r);
    array_push($recipe_information, array("RecipeID"=>$row["RecipeID"], "Title"=>$row["Title"], "Username"=>$row["username"], "ImagePath"=>$row["ImagePath"]));
  }
  $string = json_encode($recipe_information);
  return $string;
}

function get_results($tags_array, $recipes_array)
{
  global $db;
  $results = array();
  $recipe_ids = array();
  $tags = $tags_array;
  $recipes = $recipes_array;
  foreach($recipes as $recipe)
  {
    $temp_array = array();
    foreach($tags as $tag)
    {
      $tag_id = $tag["TagID"];
      $recipe_id = $recipe["RecipeID"];
      $query = "SELECT rt.RecipeTagsID FROM RecipeTags rt WHERE rt.TagID='$tag_id' AND rt.RecipeID='$recipe_id'";
      $result = mysqli_query($db, $query);
      if($result && mysqli_num_rows($result) > 0)
      {
        $row = mysqli_fetch_assoc($result);
        array_push($temp_array, $row["RecipeTagsID"]);
      }
      else
      {
        $temp_array = array();
        break;
      }
    }
    $results = array_merge($results, $temp_array);
  }
  foreach($results as $result)
  {
    $query = "SELECT rt.RecipeID FROM RecipeTags rt WHERE rt.RecipeTagsID='$result'";
    $r = mysqli_query($db, $query);
    $row = mysqli_fetch_assoc($r);
    array_push($recipe_ids, $row["RecipeID"]);
  }
  return $recipe_ids;
}
main();
mysqli_close($db);
?>
