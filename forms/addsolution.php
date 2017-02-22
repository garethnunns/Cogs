<?php
/*
Add a solution to a problem

Change log
==========

22/2/17 - Gareth Nunns
Created and completed page

*/
	require_once dirname(__FILE__).'/../check.php'; // check the user is logged in
	require_once dirname(__FILE__).'/../site/secure.php'; // connect to the database

	if(valid('message.message',$_POST['message'])) { // valid subject and message
		try {
			$sth = $dbh->prepare("INSERT INTO solved VALUES (?, {$_SESSION['user']}, ?, ?)");

			$sth->execute(array(
				$_POST['prob'],
				$_POST['message'],
				gmdate('Y-m-d H:i:s')
			));

			header("Location: /".(solved($_POST['prob']) ? 'solved':'problems')."#prob{$_POST['prob']}");
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
?>