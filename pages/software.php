<?php
/*
A list of software, where the user can see & update all the software and operating systems

Change log
==========

14/2/17 - Gareth Nunns
Added changelog

*/

	require_once dirname(__FILE__).'/../check.php';
?>

<h1>Software</h1>

<table class="software">
	<tr>
		<th>ID</th>
		<th>Name</th>
		<th>OS</th>
		<th>License</th>
		<th colspan="2">Problems</th>
	</tr>

<?php
	foreach ($tSoft as $id => $soft) {
		echo "<tr id='soft$id'>
		<td>{$id}</td>
		<td>{$soft['name']}</td>
		<td>{$soft['os']}</td>
		<td>{$soft['license']}</td>
		<td class='numProbs'><span>".mt_rand(0,14)."</span><br>Unsolved</td>
		<td class='numProbs'><span>".mt_rand(0,21)."</span><br>Solved</td>
		</tr>

		<tr>
		<td colspan='5'>
		<div class='wareDeets'>
		<h2>{$soft['name']}</h2></h2>
		<p>Here would be some notes about {$soft['name']}, however here is some filler text instead:<br>
		Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum augue libero, lobortis semper laoreet vel, commodo sit amet leo. Proin pretium ipsum ipsum, sit amet pharetra orci condimentum in. Interdum et malesuada fames ac ante ipsum primis in faucibus. Mauris bibendum nisl hendrerit, auctor enim sed, sollicitudin velit. Proin vitae convallis elit, hendrerit auctor metus. Praesent consequat efficitur erat, nec sagittis nibh volutpat at. Proin vel porttitor tortor, in consectetur odio. Fusce hendrerit congue consectetur.</p>
		<h3>Problems with {$soft['name']}</h3>
		<p>Here would be a list of porblems that there have been with the {$soft['name']}</p>
		</div>
		</td>
		</tr>";
	}
?>
</table>

<script type="text/javascript">
	$('tr:nth-of-type(2n+3)').hide();
	$('tr:nth-of-type(2n+3) .wareDeets').slideUp();

	$('tr:nth-of-type(2n+2)').on('click vclick', function() {
		$(this).next().toggle().children().first().children().slideToggle();
	});

	if(window.location.hash) {
		$(window.location.hash).trigger('click');
		$('html, body').delay(300).animate({
			scrollTop: $(window.location.hash).offset().top-80
		}, 400);
	}
</script>
