<?php
/*
A list of hardware, split into types, makes, models and then each individual item. It also let's you know how many problems there have been within each level. The user can add items, models, makes and types. They can mark a item as no longer owned but none of the items can be deleted.

Change log
==========

14/2/17 - Gareth Nunns
Added changelog
17/2/17 - Danny Jaine
Added SQL

*/

	require_once dirname(__FILE__).'/../check.php';
?>

<h1>Hardware</h1>

<table class="hardware">
	<tr>
		<th>ID</th>
		<th>Name</th>
		<th>Type</th>
		<th colspan="2">Problems</th>
	</tr>

<?php
$sql="	
SELECT * 
FROM hard
LEFT JOIN hardType
ON hard.idHardType = hardType.idHardType
LEFT JOIN hardItem
ON hard.idHard = hardItem.idHard
LEFT JOIN hardProb 
ON hard.idHard = hardProb.idHard
LEFT JOIN problem
ON hardProb.idProblem = problem.idProblem
LEFT JOIN type
ON problem.idType = type.idType
ORDER BY hard.idHard ASC
	
	foreach ($tHard as $id => $hard) {
		echo "<tr id='hard$id'>
		<td>{$id}</td>
		<td>{$hard['name']}</td>
		<td>{$hard['type']}</td>
		<td class='numProbs'><span>".mt_rand(0,14)."</span><br>Unsolved</td>
		<td class='numProbs'><span>".mt_rand(0,21)."</span><br>Solved</td>
		</tr>

		<tr>
		<td colspan='5'>
		<div class='wareDeets'>
		<h2>{$hard['name']}</h2></h2>
		<p>Here would be some notes about {$hard['name']}, however here is some filler text instead:<br>
		Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum augue libero, lobortis semper laoreet vel, commodo sit amet leo. Proin pretium ipsum ipsum, sit amet pharetra orci condimentum in. Interdum et malesuada fames ac ante ipsum primis in faucibus. Mauris bibendum nisl hendrerit, auctor enim sed, sollicitudin velit. Proin vitae convallis elit, hendrerit auctor metus. Praesent consequat efficitur erat, nec sagittis nibh volutpat at. Proin vel porttitor tortor, in consectetur odio. Fusce hendrerit congue consectetur.</p>
		<h3>Problems with {$hard['name']}</h3>
		<p>Here would be a list of porblems that there have been with the {$hard['name']}</p>
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
