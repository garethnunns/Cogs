<?php
/*
Simple facility to add users

Change log
==========

19/2/17 - Gareth Nunns
Created and completed page

*/

	require_once dirname(__FILE__).'/../check.php'; // check the user is logged in
	require_once dirname(__FILE__).'/../site/secure.php'; // connect to the database
?>

<h1>Users</h1>

<form method="POST">
	<h2>Add user</h2>
	<p>Name: <input name="firstName" type="text" placeholder="First Name"> 
	<input name="firstName" type="text" placeholder="Surname"></p>

	<p>Job title: <select name="jobTitle">
<?php
	$sql = "SELECT * FROM jobTitle ORDER BY name";
	$sth = $dbh->prepare($sql);

	$sth->execute();

	foreach ($sth->fetchAll() as $row)
		echo "<option value='{$row['idJobTitle']}'>{$row['name']}</option>";
?>
	</select></p>

	<p>Specialism:<br><select name="type" multiple="multiple">
<?php
	$sql = "SELECT * FROM type ORDER BY name";
	$sth = $dbh->prepare($sql);

	$sth->execute();

	foreach ($sth->fetchAll() as $row)
		echo "<option value='{$row['idType']}'>{$row['name']}</option>";
?>
	</select></p>

	<p>Department:<br><select name="dept" multiple="multiple">
<?php
	$sql = "SELECT * FROM dept ORDER BY name";
	$sth = $dbh->prepare($sql);

	$sth->execute();

	foreach ($sth->fetchAll() as $row)
		echo "<option value='{$row['idDept']}'>{$row['name']}</option>";
?>
	</select></p>

	<p>Site: <select name="site">
<?php
	$sql = "SELECT * FROM site ORDER BY name";
	$sth = $dbh->prepare($sql);

	$sth->execute();

	foreach ($sth->fetchAll() as $row)
		echo "<option value='{$row['idSite']}'>{$row['name']}</option>";
?>
	</select></p>

	<p>Availability: <input name="availability" type="text"></p>

	<p>Phone: <input name="tel" type="tel"></p>
	<p>Email: <input name="email" type="email"></p>

	<p>Timezone: <select name="timezone">
<?php
	$sql = "SELECT * FROM timezone ORDER BY name";
	$sth = $dbh->prepare($sql);

	$sth->execute();

	foreach ($sth->fetchAll() as $row)
		echo "<option value='{$row['idTimezone']}'>".str_replace('/', ' - ', str_replace('_', ' ', $row['name']))."</option>";
?>
	</select></p>

	<p>Language: <select name="lang">
<?php
	$sql = "SELECT * FROM lang ORDER BY name";
	$sth = $dbh->prepare($sql);

	$sth->execute();

	foreach ($sth->fetchAll() as $row)
		echo "<option value='{$row['idLang']}'>{$row['name']}</option>";
?>
	</select></p>

	<h3>Login details</h3>
	<p>Username: <input name="username" type="text"></p>
	<p>Password: <input name="password" type="password"></p>

	<input name="add" type="submit" value="Add user" />
	<input name="edit" type="submit" value="Edit user" />
</form>

<h2>Current users</h2>
<?php

	// list all the current employees and their usernames
	$sql = "SELECT emp.idEmp, emp.firstName, emp.surname, 
			login.username, jobTitle.name AS job
			FROM emp
			LEFT JOIN login ON emp.idEmp = login.idEmp
			LEFT JOIN jobTitle ON emp.jobTitle = jobTitle.idJobTitle
			ORDER BY emp.surname, emp.firstName, emp.idEmp";
	$sth = $dbh->prepare($sql);

	$sth->execute();

	if(!$sth->rowCount()) echo translate('There are currently no users'); // shouldn't happen
	else { // output them all
?>
	<table>
		<tr>
			<th>#</th>
			<th>Name</th>
			<th>Username</th>
			<th>Job</th>
			<th>Edit</th>
		</tr>
<?php
		foreach ($sth->fetchAll() as $row) {
			echo "<tr><td>{$row['idEmp']}</td>
			<td>{$row['firstName']} {$row['surname']}</td>
			<td>".($row['username'] ? $row['username'] : '<a href="users?edit='.$row['idEmp'].'&login=1">Create login</a>')."</td>
			<td>{$row['job']}</td>
			<td><a href='users?edit={$row['idEmp']}'>Edit</a></td>
			</tr>";
		}
?>
	</table>
<?php
	}
?>