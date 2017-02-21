<?php
/*
Search results when searching for specialists

Change log
==========

21/2/17 - Gareth Nunns
Connected to database

14/2/17 - Gareth Nunns
Added changelog

*/

	require_once dirname(__FILE__).'/../check.php'; // check the user is logged in
	require_once dirname(__FILE__).'/../site/secure.php'; // connect to the database

	if(!empty($_GET['s'])) {
?>

<table class="specialists">
	<tr>
		<th>ID</th>
		<th>Name</th>
		<th>Phone</th>
		<th colspan="2">Problems</th>
		<th>Availability</th>
		<th>Assign</th>
	</tr>
<?php
		$sql = "
SELECT emp.idEmp, CONCAT(emp.firstName, ' ', emp.surname) AS 'name', emp.tel, 
(SELECT COUNT(a2.idAssign) FROM assign AS a2
	WHERE a2.assTo = emp.idEmp
	AND a2.assTo IN (
        SELECT a3.assTo FROM assign as a3
		WHERE a3.idProblem IN (
            SELECT problem.idProblem FROM problem 
            WHERE problem.idProblem NOT IN (
                SELECT idProblem FROM solved
            )
        )
    ) AND a2.assDate = (SELECT MAX(a1.assDate) FROM assign AS a1 WHERE a1.idProblem = a2.idProblem)
) as unsolved, 
(SELECT COUNT(s1.idProblem) FROM solved AS s1 WHERE s1.specialist = emp.idEmp AND s1.date >= curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY) AS numSolved, login.availablity
FROM emp
LEFT JOIN login ON emp.idEmp = login.idEmp
WHERE emp.idEmp = :s
OR emp.firstName LIKE :first
OR emp.surname LIKE :second
OR emp.tel LIKE :tel";


		try {
			$sth = $dbh->prepare($sql);

			// search variables
			$s = $_GET['s'];
			$psp = '%'.$s.'%';
			// this bit attempts to look at names intelligently
			// if the user searches for 'foo bar', then it will look up 'foo' in the first name column
			$first = explode(' ', $s)[0].'%';
			// similarly this will search for 'bar' in the surname column if there was a space in the search
			$second = (stripos($s, ' ') !== false ? explode(' ', $s)[1] : $s).'%';

			// sanitize inputs
			$sth->bindParam(':s', $s);
			$sth->bindParam(':first', $first);
			$sth->bindParam(':second', $second);
			$sth->bindParam(':tel', $psp);

			$sth->execute();

			if($sth->rowCount())
				foreach ($sth->fetchAll() as $row)
					echo "<tr>
					<td>{$row['idEmp']}</td>
					<td>{$row['name']}</td>
					<td>{$row['tel']}</td>
					<td class='numProbs'><span>{$row['unsolved']}</span><br>Unsolved</td>
					<td class='numProbs'><span>{$row['numSolved']}</span><br>Solved this week</td>
					<td>{$row['availablity']}</td>
					<td><button data-id='{$row['idEmp']}'>Assign</button></td>
					</tr>";
			else 
				echo '<td colspan="7">'.translate('There were no specialists found').'</td>';
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
?>