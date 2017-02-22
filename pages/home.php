<?php
/*
Mostly for navigation to the other pages through the grid, as well as having the statistics section at the bottom

Change log
==========

19/2/17 - Gareth Nunns
Updated name at the top

14/2/17 - Gareth Nunns
Added changelog

*/

	require_once dirname(__FILE__).'/../check.php'; // check the user is logged in
	require_once dirname(__FILE__).'/../site/secure.php'; // connect to the database

	if(!isset($_SESSION['welcome'])) { // we haven't welcomed the user
		$sql = "SELECT CONCAT(emp.firstName, ' ', emp.surname) AS name
				FROM emp
				WHERE emp.idEmp = {$_SESSION['user']}";

		$sth = $dbh->prepare($sql);

		$sth->execute();

		if(!$sth->rowCount()) // we should really be able to find them in the database
			echo '<p class="error">'.translate('There has been a fundamental here').'</p>';
		else { 
			echo "<h1>".translate('Welcome')." ".$sth->fetchColumn()." #{$_SESSION['user']}</h1>"; // say hellp
			$_SESSION['welcome'] = true; // we've already welcomed them, so don't do it again
		}
	}
?>

<div class="grid">
	<div><a href="call"><?php echo translate('New call') ?></a></div><div><a href="problems"><?php echo translate('Problems') ?></a></div><div><a href="solved"><?php echo translate('Solved') ?></a></div><div><a href="specialists"><?php echo translate('Specialists') ?></a></div><div><a href="software"><?php echo translate('Software') ?></a></div><div><a href="hardware"><?php echo translate('Hardware') ?></a></div><div><a href="settings"><?php echo translate('Settings') ?></a></div>
</div>

<p style="text-align: center"><a href="users">Add users</a></p>

<!--<img src="/img/chart.png">-->

<script type="text/javascript">
	$('.grid > div').each(function () { // for all the tiles in the grid
		$(this).data('link', $(this).children()[0].href);
		$(this).off('click vclick').on('click vclick', function() {
			loadPage($(this).data('link')); // make the whole tile a link
		});
	});
	$('.grid > div > a').each(function() { // make the links not interfere with the tiles
		$(this).replaceWith('<p>'+$(this).text()+'</p>');
	});

	function squareGrid() {
		$('.grid > div').each(function () { // all of the items in the grid
			if(CSS.supports("display", "grid")) $(this).addClass('gridgood'); // future proofing
			$(this).height($(this).outerWidth()); // make the height the same as the width
		});
	}

	squareGrid();

	$(window).resize(squareGrid);
</script>