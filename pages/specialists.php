<?php
/*
Add or update specialists in a list. See their details, like phone, site, availability.

Change log
==========

19/2/17 - Ryan Roberts
Connected it to the database

14/2/17 - Gareth Nunns
Added changelog

*/

	require_once dirname(__FILE__).'/../check.php'; // check the user is logged in
	require_once dirname(__FILE__).'/../site/secure.php'; // connect to the database
?>

<h1>Specialists</h1>

<table class="specialists">
	<tr>
		<th>ID</th>
		<th>Name</th>
		<th>Phone</th>
		<th colspan="2">Problems</th>
		<th>Availability</th>
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
LEFT JOIN login ON emp.idEmp = login.idEmp";


	$sth = $dbh->prepare($sql);
	$sth->execute();


	if($sth->rowCount())
		foreach ($sth->fetchAll() as $row) {
			echo "<tr>
			<td>{$row['idEmp']}</td>
			<td>{$row['name']}</td>
			<td>{$row['tel']}</td>
			<td class='numProbs'><span>{$row['unsolved']}</span><br>Unsolved</td>
			<td class='numProbs'><span>{$row['numSolved']}</span><br>Solved this week</td>
			<td>{$row['availablity']}</td>
			</tr>";

			// FIXME
			echo "<tr><td colspan='6'><p>List of problems for the specialist with ID: {$row['idEmp']}</p></td></tr>";
	}
?>
</table>