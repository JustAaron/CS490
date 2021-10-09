<?php
	/*
	Functionality: echoes a json-encoded string using given information. Should be used whenever a client wants to view their messages with another user.
	The client is the user who is currently logged in. The other is the user who the client wants to chat with.
	Input:
	In $_POST[], "other" should be the username of other.
	In $_SESSION[], "username" should be the username of the client. Note: the file does not check if the user is valid
	Output:
	If the other is valid and there are >0 messages between client and other: echo json-encoded strings where the keys are the names of the database fields ("MessageID", "SenderID", "ReceiverID", "Message") and the values are the values stored in each row of the table.
	If the other is valid but there are 0 messages between client and other: echo "no messages found"
	If the other is not valid: echo "user not found"
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
	
	function main(){
		global $conn;
		$send_query = 'SELECT * FROM alpha_users WHERE username=\'' . $_SESSION['username'] . '\';';
		$rec_query = 'SELECT * FROM alpha_users WHERE username=\'' . $_POST['other'] . '\';';
		$send_result = mysqli_query($conn, $send_query);
		$rec_result = mysqli_query($conn, $rec_query);
		if($send_result && $rec_result && mysqli_num_rows($send_result) > 0 && mysqli_num_rows($rec_result) > 0){
			$send_row = mysqli_fetch_assoc($send_result);
			$rec_row = mysqli_fetch_assoc($rec_result);
			$send_uid =  $send_row['uid'];
			$rec_uid = $rec_row['uid'];
			$message_query = 'SELECT * FROM ChatMessages WHERE (SenderID="' . $send_uid . '" AND ReceiverID="' . $rec_uid . '") OR (SenderID="' . $rec_uid . '" AND ReceiverID="' . $send_uid . '");';
			$message_result = mysqli_query($conn, $message_query);
			if($message_result && mysqli_num_rows($message_result) > 0){
				while($message_row = mysqli_fetch_row($result)){
				$message = array('MessageID'=>$message_row[0], 'SenderID'=>$message_row[1], 'ReceiverID'=$message_row[2], 'Message'=>$message_row[3]);
				$message = json_encode($message);
				echo($message);
			}
			else{
				echo('no messsages found');
			}
			mysqli_free_result($send_result);
			mysqli_free_result($rec_result);
		}
		else{
			echo('user not found');
		}
	}
	main();
	mysqli_close($conn);
?>
