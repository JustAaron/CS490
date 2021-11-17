<?php
	session_start();
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 1);
	require('account.php');
	$conn = mysqli_connect($hostname, $username, $password, $project);
	if(mysqli_connect_errno()){
		//echo "<p>Failed to connect to MySQL: " . mysqli_connect_error(). "</p>";
		exit();
	}
	function main(){
		global $conn;
		$post_id = $_POST['post_id'];
		$query = 'SELECT r.Title, r.Ingredients, r.Steps, r.ImagePath, a.username FROM Recipes r, alpha_users a WHERE r.RecipeID=' . $post_id . ' AND r.UID=a.uid;';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) > 0){
			$post_list = array();
			while($row = mysqli_fetch_assoc($result)){
				array_push($post_list, array('recipe_title'=>$row['Title'], 'recipe_ingredients'=>$row['Ingredients'], 'recipe_steps'=>$row['Steps'], 'username'=>$row['username'], 'image_path'=>$row['ImagePath']));
			}
			$str = json_encode($post_list);
			echo($str);
		}
		elseif(mysqli_num_rows($result) == 0){
			echo('no posts found');
		}
		else{
			echo('database error');
		}
	}
	main();
	mysqli_close($conn);
?>