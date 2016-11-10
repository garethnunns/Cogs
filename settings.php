<?php
	require_once 'check.php';

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