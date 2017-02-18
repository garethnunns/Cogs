<?php
/*
Search results for looking up callers

Change log
==========

18/2/17 - Gareth Nunns
Linked to database

14/2/17 - Gareth Nunns
Added changelog

*/
	require_once dirname(__FILE__).'/../check.php'; // check the user is logged in
	require_once dirname(__FILE__).'/../site/secure.php'; // connect to the database

	$sql = "SELECT emp.idEmp, emp.firstName, emp.surname, emp.tel, jobTitle.name AS 'jobTitle', site.name AS 'site'
			FROM emp, jobTitle, site
			WHERE emp.jobTitle = jobTitle.idJobTitle
			AND emp.site = site.idSite
			AND (emp.idEmp = :s
			OR emp.firstName LIKE :first
			OR emp.surname LIKE :second
			OR emp.tel LIKE :tel)
			GROUP BY emp.idEmp
			ORDER BY emp.surname, emp.firstName
			LIMIT 10";

	$sth = $dbh->prepare($sql);

	// search variables
	$s = $_GET['s'];
	$psp = '%'.$s.'%';
	// this bit attempts to look at names intelligently
	// if the user searches for 'foo bar', then it will look up 'foo' in the first name column
	$first = explode(' ', $s)[0].'%';
	// similarly this will search for 'bar' in the surname column if there was a space in the search
	$second = stripos($s, ' ') !== false ? explode(' ', $s)[1].'%' : $s.'%';

	// sanitize inputs
	$sth->bindParam(':s', $s);
	$sth->bindParam(':first', $first);
	$sth->bindParam(':second', $second);
	$sth->bindParam(':tel', $psp);

	$sth->execute();

	// output the results (if there were any)
	if(!$sth->rowCount()) echo translate('There were no employees found');
	else
		foreach ($sth->fetchAll() as $row) 
			echo "<p data-id='{$row['idEmp']}'><b>{$row['firstName']} {$row['surname']}</b> <i>#{$row['idEmp']}</i> - {$row['jobTitle']} in {$row['site']} on {$row['tel']}</p>";
?>