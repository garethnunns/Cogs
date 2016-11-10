<?php
	session_start();

	if(!isset($_SESSION['user'])) {
		header("refresh:0; url=login");
		exit();
	}

	// internationalisation - yay...
	date_default_timezone_set("Europe/London");
	$format = 'j/n/y \a\t H:i';

	require_once dirname(__FILE__).'/functions.php';
	require_once dirname(__FILE__).'/database.php';
?>