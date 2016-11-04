<?php
	session_start();

	include 'database.php';

	if(($key = array_search($_POST['username'],array_column($tlogin,'username')))!==false && ($tlogin[$key]['password'] == $_POST['password'])) {
		$_SESSION['user'] = $key;
		header("refresh:1; url=home");
	}
	else {
		echo 'Incorrect user name or password';
	}
?>