<?php
/*
Outputs a list of unsolved problems assigned to that user, or all of them if they're a receptionist they'll see all of the problems, that the user then interacts with and adds details to, like calls, messages, solutions, etc. This can then be updated by adding these attributes and editing existing ones.

Change log
==========

14/2/17 - Gareth Nunns
Added changelog
14/2/17 - Lewys Bonds
Added SQL and made it user specific

*/

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
$stmt = $conn->prepare(SELECT * FROM problem LEFT JOIN message ON problem.idProblem = message.idProblem LEFT JOIN emp ON message.specialist = emp.idEmp LEFT JOIN jobTitle ON emp.jobTitle =jobTitle.idJobTitle LEFT JOIN assign ON problem.idProblem = assign.idProblem LEFT JOIN calls ON problem.idProblem = calls.idProblem LEFT JOIN type ON problem.idType =type.idType WHERE emp.idEmp = :empid GROUP BY problem.idProblem, message.date);
if ($_SESSION['sudo']){
	$stmt->bindParam(':empid', *);
} else{
	$stmt->bindParam(':empid', $_SESSION[empid]);	
}
	/*foreach ($tproblems as $problem)
		if(!$problem['solution']) // it hasn't been solved
			outputProblem($problem,false);*/
?>
</table>

<script type="text/javascript">
	/*$('tr:nth-of-type(3n+4)').hide().each(function() {
		$(this).children().first().children().slideUp();
	});

	$('tr:nth-of-type(3n+2), tr:nth-of-type(3n+3)').on('click vclick', function() {
		$(this).nextUntil('tr:nth-of-type(3n+2)','.responses').toggle().children().first().children().slideToggle(300);
	});

	if(window.location.hash) {
		$(window.location.hash).trigger('click');
		$('html, body').delay(300).animate({
			scrollTop: $(window.location.hash).offset().top-80
		}, 400);
	}*\
</script>
