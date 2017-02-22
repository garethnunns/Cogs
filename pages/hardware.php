<?php
/*
A list of hardware, split into types, makes, models and then each individual item. It also let's you know how many problems there have been within each level. The user can add items, models, makes and types. They can mark a item as no longer owned but none of the items can be deleted.

Change log
==========

19/2/17 - Joe Yelland, Gareth Nunns
Did some additional work on the SQL, as well as add more user friendly features

19/2/17 - Joe Yelland, Lewys Bonds, Ryan Roberts
Fixed the SQL and made it functional

19/2/17 - Joe Yelland
Added ability to output the table data

17/2/17 - Danny Jaine
Added SQL

14/2/17 - Gareth Nunns
Added changelog
*/

	require_once dirname(__FILE__).'/../check.php'; //cbeck the user is logged in
	require_once dirname(__FILE__).'/../site/secure.php'; //connect to the database

	if(isset($_POST['addHard'])) {
		try {
			$sql="INSERT INTO hard
						VALUES
						(NULL, ?, ?, ?, ?)";

			$sth = $dbh->prepare($sql);
			$sth->execute(array( //executing SQL
				$_POST['hardType'],
				$_POST['hardMake'],
				$_POST['hardModel'],
				$_POST['hardNotes']
			));


			if($sth->rowCount()) echo "<p>{$_POST['hardMake']} {$_POST['hardModel']} successfully added</p>";
		}
		catch (PDOException $e) {
			return false;
			echo $e->getMessage();
		}
	}
?>

<h1>Hardware</h1>

	<button onclick="$('.newHard').slideToggle();">Add Hardware</button>
	<form class="newHard" method="POST"> 
		<h1>Add hardware to the database</h1>
		<p>Hardware Make<?php asterisk('hard.make'); ?>: <br><input name="hardMake" type="text" placeholder="New make of hardware"></p> 
		<p>Hardware Type<?php asterisk('hardType.idHardType'); ?>: 
		<br><select name="hardType">
		<?php
			$sqltype = "SELECT hardType.idHardType, CONCAT(hardType.idHardType, ' - ' ,hardType.name) AS 'hardwareType2'
			FROM hardType";
			$sth = $dbh->prepare($sqltype);
			$sth->execute();
			foreach ($sth->fetchAll() as $row)
				echo "<option value='{$row['idHardType']}'>{$row['hardwareType2']}</option>";
		?>
		</select></p>
		<p>Hardware Model<?php asterisk('hard.model'); ?>: <br><input name="hardModel" type="text" placeholder="New hardware model"></p> 
		<p>Notes<?php asterisk('hard.notes'); ?>: <br><textarea name="hardNotes" type="text" placeholder="Information about hardware"></textarea></p>
		<p><input type="submit" value="Commit to database" name="addHard"></p> 
	</form>
<table class="hardware">
	<tr>
		<th>ID</th>
		<th>Name</th>
		<th>Type</th>
		<th colspan="2">Problems</th>
	</tr>

<?php
	$sql="SELECT hard.idHard, hard.make, hard.model, hard.notes, hardProb.idProblem, problem.title,

	(SELECT COUNT(s3.idProblem) FROM solved as s3
	WHERE problem.idProblem = s3.idProblem) AS beenSolved,
	(SELECT COUNT(hp2.idProblem) FROM hardProb AS hp2
	WHERE hp2.idProblem NOT IN (
	SELECT s2.idProblem FROM solved as s2)
	AND hp2.idHard = hard.idHard) AS numUnsolved,
	(SELECT COUNT(hp.idProblem) FROM hardProb AS hp
	WHERE hp.idProblem IN (
	SELECT s1.idProblem FROM solved as s1)
	AND hp.idHard = hard.idHard) AS numSolved

	FROM hard
	LEFT JOIN hardType
    ON hard.idHardType = hardType.idHardType
	LEFT JOIN hardProb
	ON hard.idHard = hardProb.idHard
	LEFT JOIN problem
	ON hardProb.idProblem = problem.idProblem";
	

	$sth = $dbh->prepare($sql); //executing SQL
	$sth->execute();

	$id = -1;

	foreach ($sth->fetchAll() as $row) {//Outputing the information onto the page
		if($id != $row['idHard'] && $id>-1)
			echo "</div>
			</td>
			</tr>";

		if($id != $row['idHard'])
			echo "<tr>
			<td>{$row['idHard']}</td>
			<td>{$row['make']}</td>
			<td>{$row['model']}</td>
			<td class='numProbs'><span>{$row['numUnsolved']}</span><br>Unsolved</td>
			<td class='numProbs'><span>{$row['numSolved']}</span><br>Solved</td>
			</tr>

			<tr>
			<td colspan='5'>
			<div class='wareDeets'>
			<h2>{$row['make']}</h2
			<p>{$row['notes']}</p>
			<h3>Problems with {$row['make']}</h3>";

		if(empty($row['idProblem']))
			echo "There are no problems related to this hardware.";
		else
			echo "<p><a href='".($row['beenSolved'] ? 'solved' : 'problems')."#prob{$row['idProblem']}'><strong>{$row['idProblem']}</strong> - {$row['title']}</a></p>";

		$id = $row['idHard'];
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
	$('.newHard').hide();

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