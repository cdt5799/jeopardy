<?php
	session_start();
	ob_start();
	
	//check if it's set first
	if(isset($_SESSION['username'])){
		//unset session variable
		unset($_SESSION['username']);
		//invalidate session cookie
		if(isset($_COOKIE[session_name()])){
			setcookie(session_name(), '', time()-86400, '/');
			}
		ob_end_flush();
		//end session
		session_destroy();

	}
	
	header("Location: client.php");
?>