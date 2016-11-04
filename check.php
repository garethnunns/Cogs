<?php
	session_start();

	if(!isset($_SESSION['user'])) {
		header("refresh:0; url=login");
		exit();
	}
?>