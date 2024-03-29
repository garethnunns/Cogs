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

	if(isset($_POST['add'])) { // adding a user
		try {
			if(valid('emp.firstName',$_POST['firstName'])
			&& valid('emp.surname',$_POST['surname'])
			&& valid('emp.tel',$_POST['tel'])
			&& valid('emp.email',$_POST['email'])) { // add the user to the emp table

				$sth = $dbh->prepare("INSERT INTO emp VALUES (NULL, ?, ?, ?, ?, ?, ?);");

				if($sth->execute(array(
					$_POST['site'],
					$_POST['jobTitle'],
					$_POST['firstName'],
					$_POST['surname'],
					$_POST['tel'],
					$_POST['email']
				))) { // successfully inserted that
					
					$idemp = $dbh->lastInsertId();

					if(!empty($_POST['username']) && !empty($_POST['password'])) { // adding a login

						if(valid('login.username',$_POST['username'])
						&& valid('login.password',$_POST['password'])
						&& valid('login.password',password_hash($_POST['password'],PASSWORD_DEFAULT))
						&& valid('login.availablity',$_POST['availablity'])) { // valid username and password
							
							$sth = $dbh->prepare("INSERT INTO login VALUES ($idemp, ?, ?, ?, ?, NULL, NULL, ?);");

							$sth->execute(array(
								$_POST['username'],
								password_hash($_POST['password'],PASSWORD_DEFAULT),
								$_POST['timezone'],
								$_POST['lang'],
								$_POST['availablity']
							));
						}
					}

					foreach ($_POST['specialisms'] as $specialism) {
						$sth = $dbh->prepare("INSERT INTO specialist VALUES ($idemp, ?);");
						$sth->execute(array($specialism));
					}

					foreach ($_POST['dept'] as $dept) {
						$sth = $dbh->prepare("INSERT INTO deptEmp VALUES (?, $idemp);");
						$sth->execute(array($dept));
					}

					echo "<p><strong>Successfully added {$_POST['firstName']} {$_POST['surname']} #$idemp</strong></p>";
				}
			}
		}
		catch (PDOException $e) {
			echo "<p class='error'>".$e->getMessage()."</p>";
		}
	}

	if(isset($_POST['edit'])) { // editing a user
		try {
			if(valid('emp.firstName',$_POST['firstName'])
			&& valid('emp.surname',$_POST['surname'])
			&& valid('emp.tel',$_POST['tel'])
			&& valid('emp.email',$_POST['email'])) {

				$sql = "UPDATE emp 
						SET site = ?,
						jobTitle = ?,
						firstName = ?,
						surname = ?,
						tel = ?,
						email = ?
						WHERE idEmp = ?;

						-- delete all the old specialisms
						DELETE FROM specialist WHERE idEmp = ?;

						-- delete all the old departments
						DELETE FROM deptEmp WHERE idEmp = ?;";

				$sth = $dbh->prepare($sql);

				$sth->execute(array(
					$_POST['site'],
					$_POST['jobTitle'],
					$_POST['firstName'],
					$_POST['surname'],
					$_POST['tel'],
					$_POST['email'],
					$_POST['idemp'],
					$_POST['idemp'],
					$_POST['idemp']
				));

				if((!empty($_POST['username']) && !empty($_POST['password']))
				&& (valid('login.username',$_POST['username'])
				&& valid('login.password',$_POST['password'])
				&& valid('login.password',password_hash($_POST['password'],PASSWORD_DEFAULT))
				&& valid('login.availablity',$_POST['availablity']))) { 
					$sql = "UPDATE login 
						SET username = ?,
						password = ?,
						timezone = ?,
						lang = ?,
						availablity = ?
						WHERE idEmp = ?;";

					$sth = $dbh->prepare($sql);

					$sth->execute(array(
						$_POST['username'],
						password_hash($_POST['password'],PASSWORD_DEFAULT),
						$_POST['timezone'],
						$_POST['lang'],
						$_POST['availablity'],
						$_POST['idemp']
					));
				}

				foreach ($_POST['specialisms'] as $specialism) { // add the new specialisms
					$sth = $dbh->prepare("INSERT INTO specialist VALUES (?, ?);");
					$sth->execute(array($_POST['idemp'],$specialism));
				}

				foreach ($_POST['dept'] as $dept) { // add the new departments
					$sth = $dbh->prepare("INSERT INTO deptEmp VALUES (?, ?);");
					$sth->execute(array($dept,$_POST['idemp']));
				}
			}
		}
		catch (PDOException $e) {
			echo "<p class='error'>".$e->getMessage()."</p>";
		}
	}

	if(isset($_GET['edit'])) { // viewing the edit page
		try {
			$sql = "SELECT emp.*, login.username, login.timezone, login.lang, login.availablity
					FROM emp
					LEFT JOIN login ON emp.idEmp = login.idEmp
					WHERE emp.idEmp = ?";

			$sth = $dbh->prepare($sql);

			$sth->execute(array($_GET['edit']));

			$user = $sth->fetch(PDO::FETCH_OBJ);
		}
		catch (PDOException $e) {
			echo "<p class='error'>".$e->getMessage()."</p>";
		}
	}
?>

<h1>Users</h1>

<form method="POST">
	<h2><?php echo (!isset($_GET['edit']) ? 'Add' : 'Edit'); ?> user</h2>
	<p>Name<?php asterisk('emp.firstName'); ?>: <input name="firstName" type="text" placeholder="First Name" value="<?php echo $user->firstName ?>"> 
	<input name="surname" type="text" placeholder="Surname" value="<?php echo $user->surname ?>"></p>

	<p>Job title<?php asterisk('emp.jobTitle'); ?>: <select name="jobTitle">
<?php
	$sql = "SELECT * FROM jobTitle ORDER BY name";
	$sth = $dbh->prepare($sql);

	$sth->execute();

	foreach ($sth->fetchAll() as $row)
		echo "<option value='{$row['idJobTitle']}' ".($user->jobTitle==$row['idJobTitle'] ? 'selected':'').">{$row['name']}</option>";
?>
	</select></p>

	<p>Specialism:<br><select name="specialisms[]" multiple="multiple">
<?php
	try {
		$sql = "SELECT *, (
					SELECT COUNT(t2.idType) FROM type AS t2 WHERE t2.idType IN (
						SELECT specialist.idType 
						FROM specialist 
						WHERE specialist.idEmp = ".($user->idEmp ? $user->idEmp : "'*'")."
						AND t1.idType = specialist.idType
					)
				) AS selected
				FROM type AS t1";
		$sth = $dbh->prepare($sql);

		$sth->execute();
	}
	catch (PDOException $e) {
		echo "<p class='error'>".$e->getMessage()."</p>";
	}

	// we're going to use PHP to resolve the recursive relationship
	$types = array();

	// so we'll empty the search results out into this style of array
	foreach ($sth->fetchAll() as $row)
		$types[$row['idType']] = array(
			'name' => $row['name'],
			'cat' => $row['category'],
			'selected' => ($row['selected'] ? 'selected' : '')
		);

	foreach ($types as $id => $type)
		$types[$id]['path'] = categories($type,$types);

	uasort($types, function($a, $b) {
		return strcmp($a["path"], $b["path"]);
	});

	foreach ($types as $id => $type)
		echo "<option value='$id' {$type['selected']}>{$type['path']}</option>";
?>
	</select></p>

	<p>Department:<br><select name="dept[]" multiple="multiple">
<?php
	$sql = "SELECT *, (
				SELECT COUNT(d2.idDept) 
				FROM deptEmp AS d2 
				WHERE d2.idEmp = ".($user->idEmp ? $user->idEmp : "'*'")."
				AND d1.idDept = d2.idDept
			) AS selected
			FROM dept AS d1
			ORDER BY name";
	$sth = $dbh->prepare($sql);

	$sth->execute();

	foreach ($sth->fetchAll() as $row)
		echo "<option value='{$row['idDept']}' ".($row['selected'] ? 'selected':'').">{$row['name']}</option>";
?>
	</select></p>

	<p>Site<?php asterisk('emp.site'); ?>: <select name="site">
<?php
	$sql = "SELECT * FROM site ORDER BY name";
	$sth = $dbh->prepare($sql);

	$sth->execute();

	foreach ($sth->fetchAll() as $row)
		echo "<option value='{$row['idSite']}' ".($user->site==$row['idSite'] ? 'selected':'').">{$row['name']}</option>";
?>
	</select></p>

	<p>Phone<?php asterisk('emp.tel'); ?>: <input name="tel" type="tel" value="<?php echo $user->tel ?>"></p>
	<p>Email<?php asterisk('emp.email'); ?>: <input name="email" type="email" value="<?php echo $user->email ?>"></p>

	<h3>Login details</h3>
	<p><em>Optional section</em></p>

	<p>Username<?php asterisk('login.username'); ?>: <input name="username" type="text" value="<?php echo $user->username ?>"></p>
	<p>Password<?php asterisk('login.password'); ?>: <input name="password" type="password"></p>

	<p>Availability<?php asterisk('login.availablity'); ?>: <input name="availablity" type="text" value="<?php echo $user->availablity ?>"></p>

	<p>Timezone<?php asterisk('login.timezone'); ?>: <select name="timezone">
<?php
	$sql = "SELECT * FROM timezone ORDER BY name";
	$sth = $dbh->prepare($sql);

	$sth->execute();

	foreach ($sth->fetchAll() as $row)
		echo "<option value='{$row['idTimezone']}' ".($user->timezone==$row['idTimezone'] ? 'selected':'').">".str_replace('/', ' - ', str_replace('_', ' ', $row['name']))."</option>";
?>
	</select></p>

	<p>Language<?php asterisk('login.lang'); ?>: <select name="lang">
<?php
	$sql = "SELECT * FROM lang ORDER BY name";
	$sth = $dbh->prepare($sql);

	$sth->execute();

	foreach ($sth->fetchAll() as $row)
		echo "<option value='{$row['idLang']}' ".($user->lang==$row['idLang'] ? 'selected':'').">{$row['name']}</option>";
?>
	</select></p>

<?php
	if(!isset($_GET['edit'])) echo '<input name="add" type="submit" value="Add user" />';
	else echo '<input type="hidden" name="idemp" value="'.$user->idEmp.'"><input name="edit" type="submit" value="Edit user" />';
?>

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