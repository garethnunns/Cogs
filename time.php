<?php
/*
Outputs the date in the users preferred format.

Change log
==========

14/2/17 - Gareth Nunns
Added changelog

*/

	require_once dirname(__FILE__).'/check.php';
	echo date($format)
?>