<?php
/*
Outputs a list of unsolved problems assigned to that user, or all of them if they're a receptionist they'll see all of the problems, that the user then interacts with and adds details to, like calls, messages, solutions, etc. This can then be updated by adding these attributes and editing existing ones.

Change log
==========

20/2/17 - Gareth Nunns
Added the problems that the user hasn't been assigned too

18/2/17 - Gareth Nunns
Used the provided SQL to output table


14/2/17 - Lewys Bonds
Added SQL and made it user specific

14/2/17 - Gareth Nunns
Added changelog

*/

	require_once dirname(__FILE__).'/../check.php'; // check the user is logged in
	require_once dirname(__FILE__).'/../site/secure.php'; // connect to the database
?>

<h1>Problems</h1>

<h2>Your problems</h2>

<?php

	session_start();

	$sql = "
SELECT problem.idProblem, problem.title, type.idType, type.name AS type,

-- messages
message.idMessage, message.date AS messDate, message.subject AS messSub, message.message AS messMess, message.specialist AS messSpec,
CONCAT(memp.firstName, ' ', memp.surname) AS messName, memp.tel AS messTel, mempJob.name AS messJob,

-- assignments
assign.idAssign, assign.assBy, CONCAT(assByemp.firstName, ' ', assByemp.surname) AS assByName,
assign.assTo, CONCAT(assToemp.firstName, ' ', assToemp.surname) AS assToName, assDate,

-- calls
calls.idCalls, calls.caller, CONCAT(calleremp.firstName, ' ', calleremp.surname) AS callerName, calleremp.tel AS callerTel, callerJob.name AS callerJob,
calls.op, CONCAT(opemp.firstName, ' ', opemp.surname) AS opName, opemp.tel AS opTel, opJob.name AS opJob,
calls.date AS callDate, calls.subject AS callSubject, calls.notes AS callNotes

FROM problem

-- JOINS
-- type
LEFT JOIN type ON problem.idType = type.idType 

-- messages
LEFT JOIN message ON problem.idProblem = message.idProblem
LEFT JOIN emp AS memp ON message.specialist = memp.idEmp
LEFT JOIN jobTitle as mempJob ON memp.jobTitle = mempJob.idJobTitle

-- assignments
LEFT JOIN assign ON problem.idProblem = assign.idProblem
LEFT JOIN emp AS assByemp ON assign.assBy = assByemp.idEmp
LEFT JOIN emp AS assToemp ON assign.assTo = assToemp.idEmp

-- calls
LEFT JOIN calls ON problem.idProblem = calls.idProblem
LEFT JOIN emp AS calleremp ON calls.caller = calleremp.idEmp
LEFT JOIN jobTitle as callerJob ON calleremp.jobTitle = callerJob.idJobTitle
LEFT JOIN emp AS opemp ON calls.op = opemp.idEmp
LEFT JOIN jobTitle as opJob ON opemp.jobTitle = opJob.idJobTitle

WHERE problem.idProblem NOT IN (SELECT solved.idProblem FROM solved) ";

	if(!$_SESSION['sudo'])
		$sql .= " AND assign.assTo = '{$_SESSION['user']}' AND (SELECT MAX(assDate) FROM assign AS a1 WHERE a1.idProblem = assign.idProblem) ";

	$sql .= "ORDER BY idProblem, message.date DESC, assDate DESC, calls.date DESC";

	$sth = $dbh->prepare($sql);

	$sth->execute();

	if($sth->rowCount()) {
?>
	<table class="problems">
		<tr>
			<th>ID</th>
			<th>Title</th>
			<th>Customers</th>
			<th>Operators</th>
			<th colspan="3">Activity</th>
		</tr>
<?php
		$problems = storeProblems($sth->fetchAll());

		foreach ($problems as $id => $problem)
			outputProblem($id,$problem);

		echo '</table>';
	}
	else
		echo '<p>'.translate('There are currently no problems assigned to you :)').'</p>';
?>

<h2>Other problems</h2>

<?php

	session_start();

	$sql = "
SELECT problem.idProblem, problem.title, type.idType, type.name AS type,

-- messages
message.idMessage, message.date AS messDate, message.subject AS messSub, message.message AS messMess, message.specialist AS messSpec,
CONCAT(memp.firstName, ' ', memp.surname) AS messName, memp.tel AS messTel, mempJob.name AS messJob,

-- assignments
assign.idAssign, assign.assBy, CONCAT(assByemp.firstName, ' ', assByemp.surname) AS assByName,
assign.assTo, CONCAT(assToemp.firstName, ' ', assToemp.surname) AS assToName, assDate,

-- calls
calls.idCalls, calls.caller, CONCAT(calleremp.firstName, ' ', calleremp.surname) AS callerName, calleremp.tel AS callerTel, callerJob.name AS callerJob,
calls.op, CONCAT(opemp.firstName, ' ', opemp.surname) AS opName, opemp.tel AS opTel, opJob.name AS opJob,
calls.date AS callDate, calls.subject AS callSubject, calls.notes AS callNotes

FROM problem

-- JOINS
-- type
LEFT JOIN type ON problem.idType = type.idType 

-- messages
LEFT JOIN message ON problem.idProblem = message.idProblem
LEFT JOIN emp AS memp ON message.specialist = memp.idEmp
LEFT JOIN jobTitle as mempJob ON memp.jobTitle = mempJob.idJobTitle

-- assignments
LEFT JOIN assign ON problem.idProblem = assign.idProblem
LEFT JOIN emp AS assByemp ON assign.assBy = assByemp.idEmp
LEFT JOIN emp AS assToemp ON assign.assTo = assToemp.idEmp

-- calls
LEFT JOIN calls ON problem.idProblem = calls.idProblem
LEFT JOIN emp AS calleremp ON calls.caller = calleremp.idEmp
LEFT JOIN jobTitle as callerJob ON calleremp.jobTitle = callerJob.idJobTitle
LEFT JOIN emp AS opemp ON calls.op = opemp.idEmp
LEFT JOIN jobTitle as opJob ON opemp.jobTitle = opJob.idJobTitle

WHERE problem.idProblem NOT IN (SELECT solved.idProblem FROM solved) ";

	if(!$_SESSION['sudo'])
		$sql .= " AND assign.assTo <> '{$_SESSION['user']}' AND (SELECT MAX(assDate) FROM assign AS a1 WHERE a1.idProblem <> assign.idProblem) ";

	$sql .= "ORDER BY idProblem, message.date DESC, assDate DESC, calls.date DESC";

	$sth = $dbh->prepare($sql);

	$sth->execute();

	if($sth->rowCount()) {
?>
	<table class="problems">
		<tr>
			<th>ID</th>
			<th>Title</th>
			<th>Customers</th>
			<th>Operators</th>
			<th colspan="3">Activity</th>
		</tr>
<?php
		$problems = storeProblems($sth->fetchAll());

		foreach ($problems as $id => $problem)
			outputProblem($id,$problem);

		echo '</table>';
	}
	else
		echo '<p>'.translate('There are currently no other problems').'</p>';
?>

<script type="text/javascript">
	$('tr:nth-of-type(3n+4)').hide().each(function() {
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
	}
</script>
