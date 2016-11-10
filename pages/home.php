<?php
	require_once dirname(__FILE__).'/../check.php';
?>

<?php
	if(!isset($_SESSION['welcome']))
		echo "<h1>Welcome {$tlogin[$_SESSION['user']]['name']}</h1>";
	$_SESSION['welcome'] = false;
?>

<div class="grid">
	<div><a href="call">New call</a></div><div><a href="problems">Problems</a></div><div><a href="solved">Solved</a></div><div><a href="specialists">Specialists</a></div><div><a href="software">Software</a></div><div><a href="hardware">Hardware</a></div><div><a href="settings">Settings</a></div>
</div>

<script type="text/javascript">
	$('.grid > div').each(function () {
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