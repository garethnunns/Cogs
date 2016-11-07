<?php
	session_start();

	if(isset($_SESSION['user'])) {
		header("refresh:0; url=home");
		exit();
	}
?>

<form action="/login.php" method="POST" id="login">
	<h3>Please login</h3>
	<p><input type="text" name="username" placeholder="Username"></p>
	<p><input type="password" name="password" placeholder="Password"></p>
	<p><input type="submit" value="Login"></p>
</form>

<script type="text/javascript">
	$('#login').submit(function(e) {
		e.preventDefault();

		var username = $('#login [name="username"]').val();
		var password = $('#login [name="password"]').val();

		$.ajax({
			type: "POST",
			url: 'login.php',
			data: {username: username, password: password},
			success: function(data, status, xhr) {
				var refresh = xhr.getResponseHeader('refresh');
				if(refresh) loadPage(lastSplit('=',refresh));
				tempError(data);
			}
		})
	});
</script>