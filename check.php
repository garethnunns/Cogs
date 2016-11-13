<?php
	session_start();

	if(!isset($_SESSION['user'])) {
		header("refresh:0; url=login");
		exit();
	}

	require_once dirname(__FILE__).'/functions.php';
	require_once dirname(__FILE__).'/database.php';

	// internationalisation - yay...
	if(isset($_SESSION['timezone']))
		date_default_timezone_set($_SESSION['timezone']);
	else
		date_default_timezone_set("Europe/London");

	if(!isset($_SESSION['lang']))
		$_SESSION['lang'] = 'en';

	if(isset($_SESSION['format'])) $format = $tFormats[$_SESSION['format']];
	else $format = 'j/n/y \a\t H:i';
?>