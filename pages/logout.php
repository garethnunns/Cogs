<?php
	session_start();
	unset($_SESSION['user']);
	exit('Logged out');
?>

<script type="text/javascript">
	loadPage('login');
</script>