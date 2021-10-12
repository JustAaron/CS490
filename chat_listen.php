<?php
	/*
	Functionality: echoes a json-encoded string using given information. Should be used whenever a client wants to view their messages with another user.
	The client is the user who is currently logged in. The other is the user who the client wants to chat with.
	Input:
	In $_POST[], "other" should be the username of other.
	In $_SESSION[], "username" should be the username of the client. Note: the file does not check the $_SESSION[] "password". 
	Output:
	If the other is valid and there are >0 messages between client and other and index is within bounds: echo json-encoded string where there are n elements (n=number of messages). Each element has keys ("isIncoming", "Message") and values ([True/False], <message stored in database).
	Example: [{"isIncoming":true,"Message":"test message from 1 to 0"},{"isIncoming":false,"Message":"test message from 0 to 1"}] 
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
				$a = array();
				while($row = mysqli_fetch_assoc($message_result)){
					if($send_uid == $row['SenderID']){
						array_push($a, array('isIncoming'=>False, 'Message'=>$row['Message']));
					}
					else{
						array_push($a, array('isIncoming'=>True, 'Message'=>$row['Message']));
					}
				}
				$str = json_encode($a);
				echo($str);
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
