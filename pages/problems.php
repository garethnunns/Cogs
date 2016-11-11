<?php
	require_once dirname(__FILE__).'/../check.php';
?>

<h1>Problems</h1>

<table class="problems">
	<tr>
		<th>ID</th>
		<th>Title</th>
		<th>Customers</th>
		<th>Operators</th>
		<th colspan="3">Calls</th>
	</tr>

<?php
	foreach ($tproblems as $problem)
		if(!$problem['solution']) // it hasn't been solved
			outputProblem($problem,false);
?>
</table>

<script type="text/javascript">
	$('tr:nth-of-type(3n+4)').hide().each(function() {
		$(this).children().first().children().slideUp();
	});

	$('tr:nth-of-type(3n+2), tr:nth-of-type(3n+3)').on('click vclick', function() {
		$(this).nextUntil('tr:nth-of-type(3n+2)','.responses').toggle().children().first().children().slideToggle(300);
	});
</script>