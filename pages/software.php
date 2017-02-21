<?php
/*
A list of software, where the user can see & update all the software and operating systems

Change log
==========
20/2/17 - Joe Yelland, Gareth Nunns
Added some more functionality to the SQL and gave the page tha ability to list the problems for each piece of software

20/2/17 - Joe Yelland
Added the ability for query to output the OS for each software

19/2/17 - Joe Yelland, Lewys Bonds, Ryan Roberts
Fixed the SQL and made it functional

18/2/17- Joe Yelland
Updated SQL and added ability for the website to output the query

19/2/17 - Joe Yelland 
made the page output the table 

18/2/17 - Danny Jaine
Added SQL

14/2/17 - Gareth Nunns
Added changelog

*/

	require_once dirname(__FILE__).'/../check.php'; //cbeck the user is logged in
	require_once dirname(__FILE__).'/../site/secure.php'; //connect to the database
?>

<h1>Software</h1>

<table class="software">
	<tr>
		<th>ID</th>
		<th>Name</th>
		<th>OS</th>
		<th>License</th>
		<th colspan="2">Problems</th>
	</tr>

<?php // SQL creating table with all information needed for the software page
	$sql="SELECT soft.idSoft, soft.name, soft.notes, soft.license, problem.idProblem, problem.title, OS.osname,	
    
    (SELECT COUNT(s3.idProblem) FROM solved as s3
    WHERE problem.idProblem = s3.idProblem) AS beenSolved,
	(SELECT COUNT(hp2.idProblem) FROM softProb AS hp2
 	WHERE hp2.idProblem NOT IN (
    SELECT s2.idProblem FROM solved as s2)
 	AND hp2.idSoft = soft.idSoft) AS numUnsolved,
	(SELECT COUNT(hp.idProblem) FROM softProb AS hp
 	WHERE hp.idProblem IN (
    SELECT s1.idProblem FROM solved as s1)
 	AND hp.idSoft = soft.idSoft) AS numSolved
    
    FROM soft
	LEFT JOIN softProb
	ON soft.idSoft = softProb.idSoft
    LEFT JOIN problem
    ON softProb.idProblem = problem.idProblem
    LEFT JOIN OS
    ON softProb.idOS = OS.idOS";
	$sth = $dbh->prepare($sql); //executing SQL
	$sth->execute();

	$id = -1;

	foreach ($sth->fetchAll() as $row) { //Outputing the information onto the page
		if($id != $row['idSoft'] && $id>-1)
			echo "</div>
			</td>
			</tr>";

		if($id != $row['idSoft'])
			echo "<tr>
			<td>{$row['idSoft']}</td>
			<td>{$row['name']}</td>
			<td>{$row['osname']}</td>
			<td>{$row['license']}</td>
			<td class='numProbs'><span>{$row['numUnsolved']}</span><br>Unsolved</td>
			<td class='numProbs'><span>{$row['numSolved']}</span><br>Solved</td>
			</tr>

			<tr>
			<td colspan='6'>
			<div class='wareDeets'>
			<h2>{$row['name']}</h2></h2>
			<p>{$row['notes']}</p>
			<h3>Problems with {$row['name']}</h3>";


			if(empty($row['idProblem']))
				echo "There are no problems related to this software.";
			else
				echo "<p><a href='".($row['beenSolved'] ? 'solved' : 'problems')."#prob{$row['idProblem']}'><strong>{$row['idProblem']}</strong> - {$row['title']}</a></p>";

		$id = $row['idSoft'];	
		}

	if($id>-1)
		echo "</div>
		</td>
		</tr>";

?>
</table>

<script type="text/javascript">
	$('tr:nth-of-type(2n+3)').hide();
	$('tr:nth-of-type(2n+3) .wareDeets').slideUp();

	$('tr:nth-of-type(2n+2)').on('click vclick', function() {
		$(this).next().toggle().children().first().children().slideToggle();
	});

	if(window.location.hash) {
		$(window.location.hash).trigger('click');
		$('html, body').delay(300).animate({
			scrollTop: $(window.location.hash).offset().top-80
		}, 400);
	}
</script>
