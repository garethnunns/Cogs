<?php
/*
A list of software, where the user can see & update all the software and operating systems

Change log
==========

14/2/17 - Gareth Nunns
Added changelog
18/2/17 - Danny Jaine
Added SQL 
18/2/17- Joe Yelland
Updated SQL and added ability for the website to outout the query

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
	$sql="SELECT soft.idSoft, soft.name,soft.license,soft.notes 
	FROM soft
	LEFT JOIN softProb
	ON soft.idSoft = softProb.idSoft
	LEFT JOIN problem
	ON softProb.idProblem = problem.idProblem
	LEFT JOIN type
	ON problem.idType = type.idType
	LEFT JOIN specialist
	ON type.idType = specialist.idType
	LEFT JOIN OS
	ON softProb.idOS = OS.idOS
	ORDER BY soft.idSoft ASC";

	$sth = $dbh->prepare($sql); //executing SQL
	$sth->execute();

		foreach ($sth->fetchAll() as $row) //Outputing the information onto the page
			echo "<tr>
			<td>{$row['idSoft']}</td>
			<td>{$row['name']}</td>
			<td>Install OS here :)</td>
			<td>{$row['license']}</td>
			<td class='numProbs'><span>".mt_rand(0,14)."</span><br>Unsolved</td>
			<td class='numProbs'><span>".mt_rand(0,21)."</span><br>Solved</td>
			</tr>

			<tr>
			<td colspan='5'>
			<div class='wareDeets'>
			<h2>{$row['name']}</h2></h2>
			<p>{$row['notes']}</p>
			<h3>Problems with {$row['name']}</h3>
			<p>Here would be a list of porblems that there have been with the {$row['name']}</p>
			</div>
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
