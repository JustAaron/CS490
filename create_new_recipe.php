<?php
session_start();
echo $_SESSION["username"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="styles.css">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create New Recipe</title>
</head>
<body>
  <form id="recipeForm" enctype="multipart/form-data" name="recipeForm">
    <p>Recipe Title:<br>
      <input type="text" id="Title" name="Title" size=40 maxlength=50>
    <p>Input your ingredients:<br>
      <textarea id="Ingredients" name="Ingredients" rows=3 cols=50 wrap=virtual></textarea>
    <p>Input the steps to make your recipe:<br>
      <textarea id="Steps" name="Steps" rows=20 cols=50 wrap=virtual></textarea>
    <p>Select tags for your recipe:<br>
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
      </select>
    <br><br><input type="file" id="image-file" name="image-file" accept="image/*">
    <p><input type="submit" value="Submit"></p>
  </form>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script type="text/javascript" src="recipe.js"></script>
</body>
</html>
