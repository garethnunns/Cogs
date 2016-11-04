<?php
	session_start();
	unset($_SESSION['user']);
?>

<p class="noJS">Logged out</p>

<script type="text/javascript">
	noJS();
	loadPage('login');
</script>