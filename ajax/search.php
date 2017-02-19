<?php
/*
Search results.
When you're on a specific page it will search the items on that page (e.g. on the problems page it will search all problems), otherwise it will search all the tables

Change log
==========

16/2/17 - Joe Yelland (The boss)
Added ability for the search bar to query the database when user is typing.

14/2/17 - Gareth Nunns
Added changelog

*/

	require_once dirname(__FILE__).'/../check.php'; // check the user is logged in
	require_once dirname(__FILE__).'/../site/secure.php'; // connect to the database

	if(isset($_GET['s'])) {
		echo "<h1>Searching for '".htmlspecialchars($_GET['s'])."'</h1>";
		//Searching through problems when on the problems page
		if(!$_GET['page'] || $_GET['page']=='problems') {
			
			$sqlproblems = "SELECT problem.idProblem, problem.title, COUNT(calls.idCalls) AS 'calls', COUNT(solved.idProblem) AS 'solved'
			FROM problem
			LEFT JOIN calls ON problem.idProblem = calls.idProblem
			LEFT JOIN solved ON problem.idProblem = solved.idProblem
			WHERE problem.idProblem NOT IN (SELECT solved.idProblem FROM solved)
			AND (problem.title LIKE :psp
			OR problem.idProblem LIKE :sp)
			GROUP BY problem.idProblem
			ORDER BY problem.idProblem";

			$sth = $dbh->prepare($sqlproblems);

			// sanitize inputs
			$sp = $_GET['s'].'%';
			$psp = '%'.$sp;

			$sth->bindParam(':sp', $sp);
			$sth->bindParam(':psp', $psp);

			$sth->execute();

			echo "<h3>Problems</h3>";

			// output the results (if there were any)
			if($sth->rowCount())
				foreach ($sth->fetchAll() as $row) 
					echo "<p><a href='/problems#prob{$row['idProblem']}'><strong>{$row['idProblem']}</strong> - {$row['title']} ({$row['calls']} ".translate($row['calls'] == 1 ? 'call' : 'calls').") - ".translate($row['solved'] ? 'Solved' : 'Unsolved')."</a></p>";
			else echo '<p class="error">No unsolved problems could be found</p>';
		}
		//Searching through solved problems when on the solved page page
		if(!$_GET['page'] || $_GET['page']=='solved') {
			
			$sqlsolved = "SELECT problem.idProblem, problem.title, COUNT(calls.idCalls) AS 'calls', COUNT(solved.idProblem) AS 'solved'
			FROM problem
			LEFT JOIN calls ON problem.idProblem = calls.idProblem
			LEFT JOIN solved ON problem.idProblem = solved.idProblem
			WHERE problem.idProblem IN (SELECT solved.idProblem FROM solved)
			AND (problem.title LIKE :psp
			OR problem.idProblem LIKE :sp)
			GROUP BY problem.idProblem
			ORDER BY problem.idProblem";
			$sth = $dbh->prepare($sqlsolved);

			// sanitize inputs
			$sp = $_GET['s'].'%';
			$psp = '%'.$sp;

			$sth->bindParam(':sp', $sp);
			$sth->bindParam(':psp', $psp);

			$sth->execute();

			echo "<h3>Solved Problems</h3>";

			// output the results (if there were any)
			if($sth->rowCount())
				foreach ($sth->fetchAll() as $row) 
					echo "<p><a href='/solved#prob{$row['idProblem']}'><strong>{$row['idProblem']}</strong> - {$row['title']} ({$row['calls']} ".translate($row['calls'] == 1 ? 'call' : 'calls').") - ".translate($row['solved'] ? 'Solved' : 'Unsolved')."</a></p>";
			else echo '<p class="error">No solved problems could be found</p>';
		}
		//Searching through specialists when on the specialists page
		if(!$_GET['page'] || $_GET['page']=='specialists') {
			$sqlspecialist = "SELECT emp.idEmp, emp.firstName, emp.surname 
			FROM emp 
			WHERE jobTitle = 2
			AND (emp.firstName LIKE :psp
			OR emp.firstName LIKE :sp)
			GROUP BY emp.idEmp
			ORDER BY emp.idEmp";
			$sth = $dbh->prepare($sqlspecialist);

			// sanitize inputs
			$sp = $_GET['s'].'%';
			$psp = '%'.$sp;

			$sth->bindParam(':sp', $sp);
			$sth->bindParam(':psp', $psp);

			$sth->execute();

			echo "<h3>Specialists</h3>";

			// output the results (if there were any)
			if($sth->rowCount())
				foreach ($sth->fetchAll() as $row) 
					echo "<p><a href='/specialists#prob{$row['idEmp']}'><strong>{$row['idEmp']}</strong> - {$row['firstName']} {$row['surname']}</a></p>";
			else echo '<p class="error">No specialists could be found</p>';

		}
		//Searching through software on the software page
		if(!$_GET['page'] || $_GET['page']=='software') {
			$sqlsoftware = "SELECT soft.idSoft, soft.name, soft.license 
			FROM soft 
			WHERE (soft.name LIKE :psp
			OR soft.name LIKE :sp)
			GROUP BY soft.idSoft
			ORDER BY soft.idSoft";
			$sth = $dbh->prepare($sqlsoftware);

			// sanitize inputs
			$sp = $_GET['s'].'%';
			$psp = '%'.$sp;

			$sth->bindParam(':sp', $sp);
			$sth->bindParam(':psp', $psp);

			$sth->execute();

			echo "<h3>Software</h3>";

			// output the results (if there were any)
			if($sth->rowCount())
				foreach ($sth->fetchAll() as $row) 
					echo "<p><a href='/software#prob{$row['idSoft']}'><strong>{$row['idSoft']}</strong> - {$row['name']} - {$row['license']}</a></p>";
			else echo '<p class="error">No software could be found</p>';

		}
		//Searching through hardware on the hardware page
		if(!$_GET['page'] || $_GET['page']=='hardware') {
			$sqlhardware = "SELECT hard.idHard, hard.make, hard.model 
			FROM hard
			WHERE (hard.make LIKE :psp
			OR hard.make LIKE :sp)
			GROUP BY hard.idHard
			ORDER BY hard.idHard";
			$sth = $dbh->prepare($sqlhardware);

			// sanitize inputs
			$sp = $_GET['s'].'%';
			$psp = '%'.$sp;

			$sth->bindParam(':sp', $sp);
			$sth->bindParam(':psp', $psp);

			$sth->execute();

			echo "<h3>Hardware</h3>";

			// output the results (if there were any)
			if($sth->rowCount())
				foreach ($sth->fetchAll() as $row) 
					echo "<p><a href='/hardware#prob{$row['idHard']}'><strong>{$row['idHard']}</strong> - {$row['make']} - {$row['model']}</a></p>";
			else echo '<p class="error">No hardware could be found</p>';

		}
	}
?>
