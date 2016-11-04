<?php
	session_start();

	if(!isset($_SESSION['user'])) {
		header("refresh:1; url=login");
		exit('Not logged in');
	}
?>