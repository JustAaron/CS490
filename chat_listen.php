<?php
	session_start();
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 1);
	require('account.php');
	$conn = mysqli_connect($hostname, $username, $password, $project);
	if(mysqli_connect_errno()){
		//echo "<p>Failed to connect to MySQL: " . mysqli_connect_error(). "</p>";
		exit('Error while connecting to database');
	}
	$send_query = 'SELECT * FROM alpha_users WHERE username=\'' . $_POST['senderid'] . '\';';
	$rec_query = 'SELECT * FROM alpha_users WHERE username=\'' . $_POST['receiverid'] . '\';';
	$send_result = mysqli_query($conn, $send_query);
	$rec_result = mysqli_query($conn, $rec_query);
	if($_POST['receiverid'] == ''){
		echo 'Please enter a user';
	}
	elseif($send_result && $rec_result && mysqli_num_rows($send_result) > 0 && mysqli_num_rows($rec_result) > 0){
		$send_row = mysqli_fetch_assoc($send_result);
		$rec_row = mysqli_fetch_assoc($rec_result);
		$send_uid =  $send_row['uid'];
		$rec_uid = $rec_row['uid'];
		$message_query = 'SELECT * FROM ChatMessages WHERE SenderID=\'' . $send_uid . '\' AND ReceiverID=\'' . $rec_uid . '\';';
		$message_result = mysqli_query($conn, $message_query);
		if($message_result && mysqli_num_rows($message_result) > 0){
			$message_row = mysqli_fetch_assoc($message_result);
			$message = $message_row['Message'];
			echo($message);
		}
		else{
			echo("No messages between users found");
		}
		mysqli_free_result($send_result);
		mysqli_free_result($rec_result);
	}
	else{
		echo 'Warning - User not found';
	}
	mysqli_close($conn);
?>
