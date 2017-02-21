<?php
/*
Simple facility to add hardware

Change log
==========

19/2/17 - Danny Jaine
Created and completed page

*/

	require_once dirname(__FILE__).'/../check.php'; // check the user is logged in
	require_once dirname(__FILE__).'/../site/secure.php'; // connect to the database
?>

<h1>Hardware</h1>

<form method="POST">
	<h2>Add Hardware</h2>
	<p>Hardware Make: <input name="hardwareMake" type="text" placeholder="Hardware make"> 
    <p>Hardware Type: <input name="hardwareType" type="text" placeholder="Hardware type"> 
    <p>Notes: <input name="hardwareNotes" type="text" placeholder="Notes"> 

<h2>Existing Hardware</h2>
<?php
	// list all the current pieces of hardware
	$sql = "SELECT hard.idHard, hard.make, hard.model, hard.notes
            FROM hard
            ORDER BY hard.idHard";
	$sth = $dbh->prepare($sql);

	$sth->execute();

	if(!$sth->rowCount()) echo translate('There is currently no hardware'); // shouldn't happen
	else { // output them all
?>
    <table>
		<tr>
			<th>#</th>
			<th>Make</th>
			<th>Model</th>
			<th>Notes</th>
		</tr>
<?php
		foreach ($sth->fetchAll() as $row) {
			echo "<tr><td>{$row['idHard']}</td>
			<td>{$row['make']}</td>
			<td>{$row['model']}</td>
			<td>{$row['notes']}</td>
			</tr>";
		}
?>
	</table>
<?php
	}
?>
