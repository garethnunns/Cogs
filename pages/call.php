<?php
	require_once dirname(__FILE__).'/../check.php';
?>

<form method="POST" class="call">

<h1>New Call</h1>

<div class="item">
	<h3>Caller</h3>
	<div class="data">
		<input type="text" name="caller" placeholder="Name or ext. of caller" />
	</div>
</div>

<div class="item">
	<h3>Problem</h3>
	<div class="data">
		<input type="text" name="problem" placeholder="Existing problem or new problem" />
	</div>
</div>

<h2>Call Details</h2>

<div class="item">
	<h3>Title</h3>
	<div class="data">
		<input type="text" name="title" placeholder="Title of your call" />
	</div>
</div>

<h3>Notes</h3>
<textarea placeholder="Summary of the call"></textarea>

<div class="ware">
	<div class="hardware">
		<h3>Related Hardware</h3>
		<p><input type="text" name="hardware" placeholder="Search hardware"></p>
	</div><div class="software">
	<h3>Related Software</h3>
	<p><input type="text" name="software" placeholder="Search software"></p>
	</div>
</div>

</form>

<h1>All problems</h1>

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
		else
			outputProblem($problem,true);
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