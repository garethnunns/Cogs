<?php
/*
A list of hardware, split into types, makes, models and then each individual item. It also let's you know how many problems there have been within each level. The user can add items, models, makes and types. They can mark a item as no longer owned but none of the items can be deleted.

Change log
==========
18/2/17 - Joe Yelland
Updated the SQL and allowed the page to output the data into the table

17/2/17 - Danny Jaine
Added SQL

14/2/17 - Gareth Nunns
Added changelog

*/
	require_once dirname(__FILE__).'/../check.php'; //cbeck the user is logged in
	require_once dirname(__FILE__).'/../site/secure.php'; //connect to the database
?>

<h1>Hardware</h1>

<table class="hardware">
	<tr>
		<th>ID</th>
		<th>Name</th>
		<th>Type</th>
		<th colspan="2">Problems</th>
	</tr>

<?php
	$sql="SELECT hard.idHard, hard.make, hard.model, hard.notes
	FROM hard
	LEFT JOIN hardType
	ON hard.idHardType = hardType.idHardType
	LEFT JOIN hardItem
	ON hard.idHard = hardItem.idHard
	LEFT JOIN hardProb 
	ON hard.idHard = hardProb.idHard
	LEFT JOIN problem
	ON hardProb.idProblem = problem.idProblem
	LEFT JOIN type
	ON problem.idType = type.idType
	ORDER BY hard.idHard ASC";

	$sth = $dbh->prepare($sql); //executing SQL
	$sth->execute();

	foreach ($sth->fetchAll() as $row) //Outputing the information onto the page
		echo "<tr>
		<td>{$row['idHard']}</td>
		<td>{$row['make']}</td>
		<td>{$row['model']}</td>
		<td class='numProbs'><span>".mt_rand(0,14)."</span><br>Unsolved</td>
		<td class='numProbs'><span>".mt_rand(0,21)."</span><br>Solved</td>
		</tr>

		<tr>
		<td colspan='5'>
		<div class='wareDeets'>
		<h2>{$row['make']}</h2></h2>
		<p>{$row['notes']}</p>
		<h3>Problems with {$row['make']}</h3>
		<p>Here would be a list of porblems that there have been with the {$row['make']}</p>
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
