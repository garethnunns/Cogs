<?php
/*
Logs the user out by destroying the necessary session variables

Change log
==========

20/2/17 - Gareth Nunns
Unset timezone variable

14/2/17 - Gareth Nunns
Added changelog

*/

	session_start();
	unset($_SESSION['user']);
	unset($_SESSION['welcome']);
	unset($_SESSION['timezone']);
?>

<p class="noJS">Logged out</p>

<script type="text/javascript">
	noJS();
	loadPage('login');
</script>