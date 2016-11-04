<?php
	require_once dirname(__FILE__).'/../check.php';
?>

<h1>Welcome Gareth</h1>

<div class="grid">
	<div><a href="call">New call</a></div><div><a href="problems">Problems</a></div><div><a href="solved">Solved</a></div><div><a href="specialists">Specialists</a></div><div><a href="software">Software</a></div><div><a href="hardware">Hardware</a></div><div><a href="settings">Settings</a></div>
</div>

<script type="text/javascript">
	function squareGrid() {
		$('.grid > div').each(function () { // all of the items in the grid
			if(CSS.supports("display", "grid")) $(this).addClass('gridgood'); // future proofing
			$(this).height($(this).outerWidth()); // make the height the same as the width
			$(this).off('click vclick').on('click vclick', function() {
				loadPage($(this).children()[0].href); // make the whole tile a link
			});
		});
		$('.grid > div > a').each(function() { // turn off the text links
			$(this).off('click touchend').on('click touchend', function (e) {
				e.preventDefault();
			});
		});
	}

	squareGrid();

	$(window).resize(squareGrid);
</script>