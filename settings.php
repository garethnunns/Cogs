<?php
/*
Sets the users settings - could probably move to the AJAX folder?

Change log
==========

14/2/17 - Gareth Nunns
Added changelog

*/

	require_once 'check.php';

	$_SESSION['fonts'] = isset($_POST['fonts']);

	if($tFormats[$_POST['format']]) $_SESSION['format'] = $_POST['format'];
	else $errors['format'] = "Date format not recognised";

	if($tLanguages[$_POST['lang']]) $_SESSION['lang'] = $_POST['lang'];
	else $errors['language'] = "Date format not recognised";

	$_SESSION['autoTrans'] = isset($_POST['guessLang']);

	if(isValidTimezone($_POST['timezone']))
		$_SESSION['timezone'] = $_POST['timezone'];
	else
		$errors['timezone'] = "Invalid timezone selected - '{$_POST['timezone']}'";

	if(!$errors)
		header("refresh:0; url=settings");
	else
		foreach ($errors as $error)
			echo $error.'<br>';
?>