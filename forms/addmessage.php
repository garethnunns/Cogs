<?php
/*
Add a message to a problem

Change log
==========

22/2/17 - Gareth Nunns
Created and completed page

*/
	require_once dirname(__FILE__).'/../check.php'; // check the user is logged in
	require_once dirname(__FILE__).'/../site/secure.php'; // connect to the database

	if(valid('message.subject',$_POST['subject'])
	&& valid('message.message',$_POST['message'])) { // valid subject and message
		//try {
			$sth = $dbh->prepare("INSERT INTO `message` VALUES (NULL, ?, ?, ?, ?, {$_SESSION['user']})");

			$sth->execute(array(
				$_POST['prob'],
				gmdate('Y-m-d H:i:s'),
				$_POST['subject'],
				$_POST['message']
			));
		//}
		//catch (PDOException $e) {
			//echo $e->getMessage();
		//}
	}

	header("Location: ".$_SERVER['HTTP_REFERER']);
?>