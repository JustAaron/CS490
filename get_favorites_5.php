<?php
	/*
	Functionality: Echoes information about the 5 most recently-favorited recipes of the logged-in user. Included information is the RecipeID, Title, Username (of the author), and ImagePath.
	Input: 
	In $_SESSION[], "uid" should be the uid of the logged-in user
	Output: 
	If the query was successful: echo a json-encoded string of the favorite information
	Example: 
	[{"RecipeID":"2","Title":"title2 by u2","Username":"user2","ImagePath":null},{"RecipeID":"3","Title":"title3 by u3","Username":"user3","ImagePath":null}]
	Example (with no favorited recipes):
	[]
	
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
	
	function selectDatabase($client_id){
		global $conn;
		$query = 'SELECT f.FavoriteRecipesID as frid, r.RecipeID as rid, r.Title as t, u.username as username, r.ImagePath as i FROM FavoriteRecipes f, Recipes r, alpha_users u WHERE f.uid=' . $client_id . ' AND f.RecipeID=r.RecipeID AND r.uid=u.uid ORDER BY frid DESC LIMIT 5;';
		$result = mysqli_query($conn, $query);
		if(!$result){
			return null;
		}
		$favs = array();
		while($row = mysqli_fetch_assoc($result)){
			array_push($favs, array('RecipeID'=>$row['rid'], 'Title'=>$row['t'], 'Username'=>$row['username'], 'ImagePath'=>$row['i']));
		}
		$str = json_encode($favs);  // [] when no favorites
		echo($str);
		return 0;
	}
	
	function main(){
		$client_id = $_SESSION['uid'];
		$data = selectDatabase($client_id);
		if(is_null($data)){
			echo('database error');
		}
	}
	main();
	mysqli_close($conn);
?>
