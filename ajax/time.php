<?php
/*
Outputs the date in the users preferred format.

Change log
==========

20/2/17 - Gareth Nunns
Moved to the ajax folder

14/2/17 - Gareth Nunns
Added changelog

*/

	require_once dirname(__FILE__).'/../check.php';
	echo date($format)
?>