<?php
/*
Search results for problems

Change log
==========

18/2/17 - Gareth Nunns
Linked to database

14/2/17 - Gareth Nunns
Added changelog

*/
	require_once dirname(__FILE__).'/../check.php'; // check the user is logged in
	require_once dirname(__FILE__).'/../site/secure.php'; // connect to the database

	$sql = "SELECT problem.idProblem, problem.title, COUNT(calls.idCalls) AS 'calls', COUNT(solved.idProblem) AS 'solved'
			FROM problem
			LEFT JOIN calls ON problem.idProblem = calls.idProblem
			LEFT JOIN solved ON problem.idProblem = solved.idProblem
			WHERE problem.title LIKE :psp
			OR problem.idProblem LIKE :sp
			GROUP BY problem.idProblem
			ORDER BY problem.idProblem
			LIMIT 10";

	$sth = $dbh->prepare($sql);

	// sanitize inputs
	$sp = $_GET['s'].'%';
	$psp = '%'.$sp;

	$sth->bindParam(':sp', $sp);
	$sth->bindParam(':psp', $psp);

	$sth->execute();

	// output the results (if there were any)
	echo "<p>Create a new problem with the title <strong>'{$_GET['s']}'</strong></p>";
	if($sth->rowCount())
		foreach ($sth->fetchAll() as $row) 
			echo "<p data-id='{$row['idProblem']}'><strong>{$row['idProblem']}</strong> - {$row['title']} ({$row['calls']} ".translate($row['calls'] == 1 ? 'call' : 'calls').") - ".translate($row['solved'] ? 'Solved' : 'Unsolved')."</p>";
?>