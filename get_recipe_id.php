<?php
/*
Fetches the first ( arbitrary number ) of results from the spoonacular search using cURL.
These results are stored in a JSON array, and a loop is initiated to grab the ID's of the recipes.
The array of ID's is then returned to search.js
*/
session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
require("account.php");

$recipe_id_array = array();
$invalid_characters = array("[", "]", "(", ")", "/", " ");

function get_results() //Gets the resutls for the spooncular search
{
  global $api_key;
  global $invalid_characters;
  $query = $_POST["query"]; // Query recieved from user search
  $query = str_replace($invalid_characters, "-", $query);
  $query_result = file_get_contents("https://api.spoonacular.com/recipes/complexSearch?apiKey=" . $api_key . "&number=5&query=" . $query); // Specifies URL to send request to
  $result = json_decode($query_result, true);
  return $result;
}

function populate($result) //Populates the recipe id array with values from the spoonacular search
{
  global $recipe_id_array;
  for($count = 0; $count < count($result["results"]); ++$count)
  {
    $recipe_id = $result["results"][$count]["id"];
    array_push($recipe_id_array, $recipe_id);
  }
}
function main()
{
  global $recipe_id_array;
  $array = get_results();
  populate($array);
  if(empty($recipe_id_array))
  {
    echo ("error");
    return;
  }
  else
  {
    echo (json_encode($recipe_id_array));
  }
}
main();
?>
