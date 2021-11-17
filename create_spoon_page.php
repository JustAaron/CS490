<?php
/*
Creates a new file for each recipe in the proper format and then returns an array of links to be displayed in the search results.
It does this by making an API call for each ID in the recipe array. Each time it gets this information, but calls write_recipe so that the information can be displayed
on the newly created recipe page. The recipe page is then moved into the user's directory.
*/
session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors', 1);
require('account.php');

$invalid_characters = array("[", "]", "(", ")", "/", " ");
$recipe_bad_format = $_POST["?ids"]; //Array of IDs received from search.js in bad format for some reason I can't discern
$recipe_ids_string = implode(",", $recipe_bad_format);
$recipe_ids_string = str_replace($invalid_characters, "", $recipe_ids_string);
$recipe_ids = explode(",", $recipe_ids_string);
$recipe_links = array(); //Empty array to be populated with links
$username = $_SESSION["username"];
function get_information() //Loops through each recipe ID in the array, making an API call and sending the information to write_recipe for each one
{
  global $recipe_ids;
  global $api_key;
  $count = 0;
  foreach($recipe_ids as $id)
  {
    $information = file_get_contents("https://api.spoonacular.com/recipes/" . $id . "/information?apiKey=" . $api_key);
    $recipe_information = json_decode($information, true); //Creates array with API result

    if(empty($recipe_information)) //If no information was received, throw an error
    {
      return false;
    }
    else
    {
      write_recipe($recipe_information);
    }
  }
  return true;
}

function write_recipe($recipe_information)
{
  global $recipe_links;
  global $username;
  global $invalid_characters;
  $file_title = $recipe_information["title"] . ".php";
  $file_title = str_replace($invalid_characters, "-", $file_title);
  $recipe_file = fopen($file_title, "w");
  $file_contents = "
  <?php
      session_start();
  ?>
  <!DOCTYPE html>
  <html lang='en'>
  <head>
      <link rel='stylesheet' href='../styles.css'>
      <meta charset='UTF-8'>
      <meta http-equiv='X-UA-Compatible' content='IE=edge'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <title>Recipe</title>
  </head>
  <body>
  ";
  $file_contents .= "<a href=" . "'" . $recipe_information["spoonacularSourceUrl"] . "'" . ">Full Recipe </a><br><br>";
  $file_contents .= "<img src=" . "'" . $recipe_information["image"] . "'" . "><br><br>";
  for($count = 0; $count < count($recipe_information["extendedIngredients"]); ++$count)
  {
    $file_contents .= $recipe_information["extendedIngredients"][$count]["original"] . "<br>";
  }
  if(!empty($recipe_information["instructions"]))
  {
    $file_contents .= $recipe_information["instructions"] . "<br>";
  }
  $file_contents .= "
  </body>
  </html>
  ";
  fwrite($recipe_file, $file_contents);
  fclose($recipe_file);
  rename($file_title, $username . "/" . $file_title);
  array_push($recipe_links, $username . "/" . $file_title);
}

function main()
{
  global $recipe_links;
  $result = get_information();
  if($result == false)
  {
    echo("error");
  }
  elseif(empty($recipe_links))
  {
    echo("error");
  }
  else
  {
    echo(json_encode($recipe_links));
  }
}
main();
?>
