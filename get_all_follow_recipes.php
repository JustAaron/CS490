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
        $uid = $_SESSION['uid'];
		$query = 'SELECT r.Title, r.ImagePath, r.RecipeID, a.username FROM Recipes r, alpha_users a WHERE r.UID IN (SELECT a.uid from alpha_users a, Follows f WHERE f.Follower=' . $uid . ' AND f.Followed=a.uid) AND r.UID = a.uid ORDER BY RecipeID DESC;';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) > 0){
			$post_list = array();
			while($row = mysqli_fetch_assoc($result)){
				array_push($post_list, array('recipe_title'=>$row['Title'], 'username'=>$row['username'], 'image_path'=>$row['ImagePath'], 'recipe_id'=>$row['RecipeID']));
			}
			$str = json_encode($post_list);
			echo($str);
		}
		elseif(mysqli_num_rows($result) == 0){
			echo('no recipes');
		}
		else{
			echo('database error');
		}
	}
	main();
	mysqli_close($conn);
?>