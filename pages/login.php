<?php
/*
Page to let the user log in

Change log
==========

22/2/17 - Gareth Nunns
Added language support

14/2/17 - Gareth Nunns
Added changelog

*/

	if(isset($_SESSION['user'])) {
		header("refresh:0; url=home");
		exit();
	}

	require_once dirname(__FILE__).'/../site/secure.php'; // connect to the database
	require_once dirname(__FILE__).'/../functions.php'; // use the functions

	if(isset($_POST['lang'])) {
		$sql = "SELECT * FROM lang WHERE idLang = ?";
		$sth = $dbh->prepare($sql);
		$sth->execute(array($_POST['lang']));

		if($sth->rowCount()) $_SESSION['lang'] = $_POST['lang'];
		else echo translate("Language not recognised");
	}
?>

<form action="/forms/login.php" method="POST" id="login">
	<h3><?php echo translate('Please login')?></h3>
	<p><input type="text" name="username" placeholder="<?php echo translate('Username')?>"></p>
	<p><input type="password" name="password" placeholder="<?php echo translate('Password')?>"></p>
	<p><input type="submit" value="<?php echo translate('Login')?>"></p>
</form>

<form method="POST" style="text-align:center" id="lang">
	<select name="lang">
<?php
	$sql = "SELECT * FROM lang ORDER BY name";
	$sth = $dbh->prepare($sql);

	$sth->execute();

	foreach ($sth->fetchAll() as $row)
		echo "<option value='{$row['idLang']}' ".(($_SESSION['lang'] ? $_SESSION['lang'] : 'en')==$row['idLang'] ? 'selected':'').">{$row['name']}</option>";
?>
	</select>
	<br><input type="submit" value='<?php echo translate('Change') ?>' class="noJS">
</form>

<script type="text/javascript">
	$('#login').submit(function(e) {
		e.preventDefault();

		var username = $('#login [name="username"]').val();
		var password = $('#login [name="password"]').val();

		$.ajax({
			type: "POST",
			url: '/forms/login.php',
			data: {username: username, password: password},
			success: function(data, status, xhr) {
				var refresh = xhr.getResponseHeader('refresh');
				if(refresh) loadPage(lastSplit('=',refresh));
				tempError(data);
			},
			error: function(error) { // when there's a link to a page that doesn't exist
				console.log('Tried to load login.php and got a '+error.status);
				tempError("We tried to log you in but there was an unexpected error");
			}
		})
	});

	$('[name="lang"]').change(function () {
		$('#lang').submit();
	})
</script>