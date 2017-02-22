<?php
/*
Logs the user in and sets session variable

Change log
==========

22/2/17 - Gareth Nunns
Moved to forms folder
Added session variables

19/2/17 - Ryan Roberts, Gareth Nunns
Made it work

14/2/17 - Gareth Nunns
Added changelog

*/

	require_once dirname(__FILE__).'/../site/secure.php'; // connect to the database
	require_once dirname(__FILE__).'/../functions.php';
	session_start();
	
	if(isset($_POST['username']) && isset($_POST['password'])) {  
		
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		$sql = "SELECT login.idEmp, login.password, emp.jobTitle, login.timezone, login.lang
				FROM login, emp
				WHERE login.username = :user
				AND login.idEmp = emp.idEmp";

		$sth = $dbh->prepare($sql);

		// sanitize inputs
		$sth->bindParam(':user', $username);

		$sth->execute();

		// output the results (if there were any)
		if(!$sth->rowCount()) 
			echo translate('Your userame or password is invalid');
		else {
			$user = $sth->fetch(PDO::FETCH_OBJ);
			if(password_verify($password, $user->password)) {
				$_SESSION['user'] = $user->idEmp;
				$_SESSION['sudo'] = $user->jobTitle != 2;
				if(isValidTimezone($user->timezone)) $_SESSION['timezone'] = $user->timezone;
				$_SESSION['lang'] = $user->lang;
				header("refresh:0; url=home");
			}
			else 
				echo translate('Your userame or password is invalid');
		}
	}
	else 
		echo translate('Please enter a username and password');
?>