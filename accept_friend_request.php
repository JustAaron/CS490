<?php
	/*
	Functionality: Accepts a friend request by deleting the row of the FriendRequests database table and inserting into the Friends database table accordingly. 
	Input: 
	In $_SESSION[], "uid" should be the logged-in user
	In $_POST[], "other" should be the user whose friend request is accepted
	Output:
	If both databases were successfully updated: echo "successfully accepted"
	If the "other" user was not found: echo "user not found"
	If there was a database error: echo "database error"
	
	Last updated 11/4/21
	Changed "update" of FriendRequests to "delete"
	Changed Row to be inserted to always have User1ID be the lower UID and User2ID to be the greater UID (storage reasons)
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
	
	function getUserID($username){
		global $conn;
		$query = 'SELECT uid FROM alpha_users WHERE username="' . $username . '";';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			return $row['uid'];
		}
		return -1;  // error value
	}
	
	function deleteFriendRequestsDatabase($accepter_uid, $accepted_uid){
		global $conn;
		$query = 'DELETE FROM FriendRequests WHERE (ReceiverID=' . $accepter_uid . ' AND SenderID=' . $accepted_uid . ') OR (ReceiverID=' . $accepted_uid . ' AND SenderID=' . $accepter_uid . ');';
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo('database error');
			return false;
		}
		return true;
	}
	
	function getMaxFriendsID(){
		global $conn;
		$query = 'SELECT MAX(RelationshipID) as max_fid FROM Friends;';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			if($row['max_fid'] == null){
				return 0;
			}
			$max = $row['max_fid'];
			return $max;
		}
		return -1;  // error value
	}
	
	function insertFriendsDatabase($max_friend_id, $accepter_uid, $accepted_uid){
		global $conn;
		if($accepter_uid < $accepted_uid){
			$user1_id = $accepter_uid;
			$user2_id = $accepted_uid;
		}
		else{
			$user1_id = $accepted_uid;
			$user2_id = $accepter_uid;
		}
		$query = 'INSERT INTO Friends (RelationshipID, User1ID, User2ID) VALUES (' . $max_friend_id . ', ' . $user1_id . ', ' . $user2_id . ');';
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo('database error');
			return false;
		}
		return true;
	}
	
	function main(){
		$accepter_uid = $_SESSION['uid'];
		$accepted_username = $_POST['other'];
		$accepted_uid = getUserID($accepted_username);
		if($accepted_uid == -1){
			echo('user not found');
			return;
		}
		$max_friend_id = getMaxFriendsID();
		if($max_friend_id == -1){
			echo('database error');
			return;
		}
		if(deleteFriendRequestsDatabase($accepter_uid, $accepted_uid)){
			if(insertFriendsDatabase($max_friend_id+1, $accepter_uid, $accepted_uid)){
				echo('successfully accepted');
			}
		}
	}
	main();
	mysqli_close($conn);
?>
