<?php
/*
Sets the users settings - could probably move to the AJAX folder?

Change log
==========

22/2/17 - Gareth Nunns
Moved to forms folder
Connected to database

14/2/17 - Gareth Nunns
Added changelog

*/
	require_once dirname(__FILE__).'/../check.php'; // check the user is logged in
	require_once dirname(__FILE__).'/../site/secure.php'; // connect to the database

	// update session vars
	$_SESSION['fonts'] = isset($_POST['fonts']);

	if($tFormats[$_POST['format']]) $_SESSION['format'] = $_POST['format'];
	else $errors['format'] = "Date format not recognised";

	$sql = "SELECT * FROM lang WHERE idLang = ?";
	$sth = $dbh->prepare($sql);
	$sth->execute(array($_POST['lang']));

	if($sth->rowCount()) $_SESSION['lang'] = $_POST['lang'];
	else $errors['language'] = "Language not recognised";

	$_SESSION['autoTrans'] = isset($_POST['guessLang']);

	if(isValidTimezone($_POST['timezone'])) $_SESSION['timezone'] = $_POST['timezone'];
	else $errors['timezone'] = "Invalid timezone selected - '{$_POST['timezone']}'";

	if(!$errors) {
		// update db
		try {
			// first off we need to see if we've already stored that timezone
			$sql = "SELECT idTimezone FROM timezone WHERE name = ?";
			$sth = $dbh->prepare($sql);
			$sth->execute(array($_SESSION['timezone']));
			if ($sth->rowCount()) $tz = $sth->fetchColumn(); // we got it
			else { // wasn't already in there, so we need to put it in
				$sql = "INSERT INTO timezone VALUES(NULL, ?)";
				$sth = $dbh->prepare($sql);
				$sth->execute(array($_SESSION['timezone']));
				$tz = $dbh->lastInsertId(); // get the timezone we just popped in
			}

			$sql = "UPDATE login
					SET timezone = ?,
					lang = ?,
					autoTrans = ?,
					impaired = ?,
					availablity = ?
					WHERE idEmp = {$_SESSION['user']}";

			$sth = $dbh->prepare($sql);

			$sth->execute(array(
				$tz,
				$_SESSION['lang'],
				$_SESSION['autoTrans'],
				$_SESSION['fonts'],
				''
			));
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}

		header("refresh:0; url=settings");
	}
	else
		foreach ($errors as $error)
			echo $error.'<br>';
?>