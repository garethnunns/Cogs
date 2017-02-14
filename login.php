<?php
/*
Logs the user in and sets session variable

Change log
==========

14/2/17 - Gareth Nunns
Added changelog

*/

	session_start();

	include 'database.php';

	if(($key = array_search($_POST['username'],array_column($tlogin,'username')))!==false && ($tlogin[$key]['password'] == $_POST['password'])) {
		$_SESSION['user'] = $key;
		header("refresh:0; url=home");
	}
	else {
		echo 'Incorrect user name or password';
	}
?>