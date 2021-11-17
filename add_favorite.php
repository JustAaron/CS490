<?php
	/*
	Functionality: Allows users to favorite a recipe by inserting a row into the FavoriteRecipes table. 
	Input: 
	In $_SESSION[], "uid" should be the uid of the logged-in user
	In $_POST[], "RecipeID" should be the RecipeID of the recipe to be favorited
	Output: 
	If the recipe was successfully inserted into the database: echo 'successfully favorited'
	If there was a database error: echo 'database error'
	*/
	session_start();
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 1);
	require('account.php');
	$conn = mysqli_connect($hostname, $username, $password, $project);
	if(mysqli_connect_errno()){
		//echo "<p>Failed to connect to MySQL: " . mysqli_connect_error(). "</p>";
		exit();
	}
	
	function getMaxFavRecipeID(){
		global $conn;
		$query = 'SELECT MAX(FavoriteRecipesID) as max_frid FROM FavoriteRecipes;';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			$max = $row['max_frid'];
			return $max;
		}
		return -1;  // error value
	}
	
	function insertDatabase($fav_recipe_id, $client_id, $recipe_id){
		global $conn;
		$query = 'INSERT INTO FavoriteRecipes (FavoriteRecipesID, uid, RecipeID) VALUES ('. $fav_recipe_id . ','. $client_id . ',' . $recipe_id . ');';
		$result = mysqli_query($conn, $query);
		if(mysqli_affected_rows($conn) != 1){
			return null;  // error value
		}
		return 0;
	}
	
	function main(){
		$client_id = $_SESSION['uid'];
		$recipe_id = $_POST['RecipeID'];
		$max_fav_recipe_id = getMaxFavRecipeID();
		if($max_fav_recipe_id == -1){
			echo('database error');
			return;
		}
		$data = insertDatabase($max_fav_recipe_id+1, $client_id, $recipe_id);
		if(is_null($data)){
			echo('database error');
			return;
		}
		echo('successfully favorited');
	}
	main();
	mysqli_close($conn);
?>
